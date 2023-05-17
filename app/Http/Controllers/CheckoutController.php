<?php

namespace App\Http\Controllers;

use App\Utility\PayfastUtility;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Address;
use App\Models\Shop;
use App\Models\Carrier;
use App\Models\CombinedOrder;
use App\Models\Product;
use App\Models\BindedCard;
use App\Utility\PayhereUtility;
use App\Utility\NotificationUtility;
use Session;
use Auth;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

use App\Services\Paymo\Entities\Expiry;
use App\Services\Paymo\Entities\PreConfirmTransaction;
use App\Services\Paymo\Entities\Transaction;
use App\Services\Paymo\Entities\ConfirmTransaction;
use App\Services\Paymo\Token;
use App\Services\Paymo\TransactionProcess;

class CheckoutController extends Controller
{

    public function __construct()
    {
        //
    }

    //check the selected payment gateway and redirect to that controller accordingly
    public function checkout(Request $request)
    {
        // Minumum order amount check

        if(get_setting('minimum_order_amount_check') == 1){
            $subtotal = 0;
            foreach (Cart::where('user_id', Auth::user()->id)->get() as $key => $cartItem){ 
                $product = Product::find($cartItem['product_id']);
                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
            }
            if ($subtotal < get_setting('minimum_order_amount')) {
                flash(translate('You order amount is less then the minimum order amount'))->warning();
                return redirect()->route('home');
            }
        }
        // Minumum order amount check end
        
        if ($request->payment_option != null) {
            (new OrderController)->store($request);

            $request->session()->put('payment_type', 'cart_payment');
            
            $data['combined_order_id'] = $request->session()->get('combined_order_id');
            $request->session()->put('payment_data', $data);

            if ($request->session()->get('combined_order_id') != null) {

                // If block for Online payment, wallet and cash on delivery. Else block for Offline payment
                $decorator = __NAMESPACE__ . '\\Payment\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', $request->payment_option))) . "Controller";

                if (class_exists($decorator)) {
                    return (new $decorator)->pay($request);
                }
                else {
                    $combined_order = CombinedOrder::findOrFail($request->session()->get('combined_order_id'));
                    $manual_payment_data = array(
                        'name'   => $request->payment_option,
                        'amount' => $combined_order->grand_total,
                        'trx_id' => $request->trx_id,
                        'photo'  => $request->photo
                    );
                    foreach ($combined_order->orders as $order) {
                        $order->payment_type = $request->payment_option;
                        $order->payment_status = 'paid';
                        $order->save();
                    }
                    flash(translate('Your order has been placed successfully. Please submit payment information from purchase history'))->success();
                    return redirect()->route('order_confirmed');
                }
            }
        } else {
            flash(translate('Select Payment Option.'))->warning();
            return back();
        }
    }

    public function paySuccess(Request $request)
    {
        $carts = Cart::where('user_id', Auth::user()->id)
            ->get();

        $seller_products = array();
        foreach ($carts as $cartItem) {
            $product_ids = array();
            $product = Product::find($cartItem['product_id']);
            if (isset($seller_products[$product->user_id])) {
                $product_ids = $seller_products[$product->user_id];
            }
            array_push($product_ids, $cartItem);
            $seller_products[$product->user_id] = $product_ids;
        }

        $sum_pay_sellers = 0; $params = []; $sum_pay_admin = 0;
        foreach ($seller_products as $key => $seller_product) {

            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            $coupon_discount = 0;
            $sum_pay_sellers = 0;
            $adminTotal = 0;

            foreach ($seller_product as $cartItem) {

                $product = Product::find($cartItem['product_id']);

                $category = Category::where('id',$product->category_id)->first();

                if($category && $category->commision_rate != 0) {

                    $subtotal += (int)cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'] * (100 - $category->commision_rate)/100;
                    $adminTotal += (int)cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'] * ($category->commision_rate)/100;
                } else {

                    $subtotal += (int)cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                   
                }

                $tax +=  cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                $coupon_discount += $cartItem['discount'];

            }

            // $sum_pay_sellers = $subtotal + $tax + $shipping;
            // $sum_pay_admin += $adminTotal;

            $sum_pay_sellers = 500;
            $sum_pay_admin = 500;

            $shop = Shop::where('user_id', $key)->first();

            if($shop && $shop->paymo_setting!=null)
            {
                $paymo_setting = json_decode($shop->paymo_setting, true);  

                $terminal_id = $paymo_setting['terminal_id'];

                $params[] = [
                    'account' => $key,
                    'terminal_id' => $terminal_id,
                    'amount' => $sum_pay_sellers * 100,
                    'details' => 'Для услуги ' . $key
                ];

            } else {

                return response()->json([
                    'message' => 'Undifined Seller!'
                ], 404);
            }

        }

        if($sum_pay_admin != 0)
            $params[] = [
                'account' => 'Admin',
                'terminal_id' => env('PAYMO_TERMINALID'),
                'amount' => $sum_pay_admin * 100,
                'details' => 'Для услуги Админа'
            ];
        
        $bind = BindedCard::find($request->bindID);

        try {

            $transaction_process = new TransactionProcess();

            $transaction = new Transaction($params, env('PAYMO_STOREID'), 'ru');
            $transaction_response = $transaction_process->createTransaction($transaction);
            
            $transaction_id = $transaction_response['transaction_id'];

            if(empty($transaction_id)){
                return response()->json([
                    'message' => $transaction_response['result']['description']
                ], 400);
            }

            $pre_confirm_transaction = new PreConfirmTransaction($bind->card_token, env('PAYMO_STOREID'), $transaction_id, 'ru');

            $response = $transaction_process->preConfirmTransaction($pre_confirm_transaction);

            return response()->json([
                'transaction_id' => $transaction_id,
                'phone' => $bind->phone
            ]);
            

        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage(),
            ],404);
        }
        
    }

    public function otpVerify(Request $request)
    {

            try {
                
                $transaction_process = new TransactionProcess();
                $confirm_transaction = new ConfirmTransaction( $request->transaction_id , $request->verify_code , env('PAYMO_STOREID'));
                $response = $transaction_process->confirmTransaction($confirm_transaction);
   
                
                if($response['result']['code'] == "OK" ) {
                    
                    return response()->json([
                        'message' => 'Your Payment successfully!'
                    ]);                

                } else {
                    
                    return response()->json([

                        'message' => $response['result']['description']

                    ], 400);
                     
                }
                
    
            } catch (\Exception $e) {
    
                return response()->json([
                    'message' => $e->getMessage(),
                ]);
            }
    }

    public function checkout_done($combined_order_id, $payment)
    {
        $combined_order = CombinedOrder::findOrFail($combined_order_id);

        foreach ($combined_order->orders as $key => $order) {
            $order = Order::findOrFail($order->id);
            $order->payment_status = 'paid';
            $order->payment_details = $payment;
            $order->save();

            calculateCommissionAffilationClubPoint($order);
        }
        Session::put('combined_order_id', $combined_order_id);
        return redirect()->route('order_confirmed');
    }

    public function get_shipping_info(Request $request)
    {
        $carts = Cart::where('user_id', Auth::user()->id)->get();
//        if (Session::has('cart') && count(Session::get('cart')) > 0) {
        if ($carts && count($carts) > 0) {
            $categories = Category::all();
            return view('frontend.shipping_info', compact('categories', 'carts'));
        }
        flash(translate('Your cart is empty'))->success();
        return back();
    }

    public function store_shipping_info(Request $request)
    {
        if ($request->address_id == null) {
            flash(translate("Please add shipping address"))->warning();
            return back();
        }

        $carts = Cart::where('user_id', Auth::user()->id)->get();
        if($carts->isEmpty()) {
            flash(translate('Your cart is empty'))->warning();
            return redirect()->route('home');
        }

        foreach ($carts as $key => $cartItem) {
            $cartItem->address_id = $request->address_id;
            $cartItem->save();
        }

        $carrier_list = array();
        if(get_setting('shipping_type') == 'carrier_wise_shipping'){
            $zone = \App\Models\Country::where('id',$carts[0]['address']['country_id'])->first()->zone_id;

            $carrier_query = Carrier::query();
            $carrier_query->whereIn('id',function ($query) use ($zone) {
                $query->select('carrier_id')->from('carrier_range_prices')
                ->where('zone_id', $zone);
            })->orWhere('free_shipping', 1);
            $carrier_list = $carrier_query->get();
        }
        
        return view('frontend.delivery_info', compact('carts','carrier_list'));
    }

    public function store_delivery_info(Request $request)
    {
        $bindCards = BindedCard::where('user_id', auth()->user()->id)->get();
        $card_info = [];
        foreach($bindCards as $card) {
            $card_info[$card->id] = [
                'card_holder' => $card->card_holder,
                'expiry' => $card->expiry
            ];
        }

        $bind = 0;
        if ($bindCards->count() > 0) {
            $bind = $bindCards[0]->id;
        }

        $carts = Cart::where('user_id', Auth::user()->id)
                ->get();

        if($carts->isEmpty()) {
            flash(translate('Your cart is empty'))->warning();
            return redirect()->route('home');
        }

        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();
        $total = 0;
        $tax = 0;
        $shipping = 0;
        $subtotal = 0;

        if ($carts && count($carts) > 0) {
            
            foreach ($carts as $key => $cartItem) {

                $product = Product::find($cartItem['product_id']);

                $tax += cart_product_tax($cartItem, $product,false) * $cartItem['quantity'];
                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];

                if(get_setting('shipping_type') != 'carrier_wise_shipping' || $request['shipping_type_' . $product->user_id] == 'pickup_point'){
                    if ($request['shipping_type_' . $product->user_id] == 'pickup_point') {
                        $cartItem['shipping_type'] = 'pickup_point';
                        $cartItem['pickup_point'] = $request['pickup_point_id_' . $product->user_id];
                    } else {
                        $cartItem['shipping_type'] = 'home_delivery';
                    }
                    $cartItem['shipping_cost'] = 0;
                    if ($cartItem['shipping_type'] == 'home_delivery') {
                        $cartItem['shipping_cost'] = getShippingCost($carts, $key);
                    }
                }
                else{
                    $cartItem['shipping_type'] = 'carrier';
                    $cartItem['carrier_id'] = $request['carrier_id_' . $product->user_id];
                    $cartItem['shipping_cost'] = getShippingCost($carts, $key, $cartItem['carrier_id']);
                }

                $shipping += $cartItem['shipping_cost'];
                $cartItem->save();
            }
            $total = $subtotal + $tax + $shipping;
            return view('frontend.payment_select', compact('carts', 'shipping_info', 'total', 'bindCards','card_info','bind'));

        } else {

            flash(translate('Your Cart was empty'))->warning();
            return redirect()->route('home');
        }
    }

    public function apply_coupon_code(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->first();
        $response_message = array();

        if ($coupon != null) {
            if (strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date) {
                if (CouponUsage::where('user_id', Auth::user()->id)->where('coupon_id', $coupon->id)->first() == null) {
                    $coupon_details = json_decode($coupon->details);

                    $carts = Cart::where('user_id', Auth::user()->id)
                                    ->where('owner_id', $coupon->user_id)
                                    ->get();

                    $coupon_discount = 0;
                    
                    if ($coupon->type == 'cart_base') {
                        $subtotal = 0;
                        $tax = 0;
                        $shipping = 0;
                        foreach ($carts as $key => $cartItem) { 
                            $product = Product::find($cartItem['product_id']);
                            $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                            $tax += cart_product_tax($cartItem, $product,false) * $cartItem['quantity'];
                            $shipping += $cartItem['shipping_cost'];
                        }
                        $sum = $subtotal + $tax + $shipping;
                        if ($sum >= $coupon_details->min_buy) {
                            if ($coupon->discount_type == 'percent') {
                                $coupon_discount = ($sum * $coupon->discount) / 100;
                                if ($coupon_discount > $coupon_details->max_discount) {
                                    $coupon_discount = $coupon_details->max_discount;
                                }
                            } elseif ($coupon->discount_type == 'amount') {
                                $coupon_discount = $coupon->discount;
                            }

                        }
                    } elseif ($coupon->type == 'product_base') {
                        foreach ($carts as $key => $cartItem) { 
                            $product = Product::find($cartItem['product_id']);
                            foreach ($coupon_details as $key => $coupon_detail) {
                                if ($coupon_detail->product_id == $cartItem['product_id']) {
                                    if ($coupon->discount_type == 'percent') {
                                        $coupon_discount += (cart_product_price($cartItem, $product, false, false) * $coupon->discount / 100) * $cartItem['quantity'];
                                    } elseif ($coupon->discount_type == 'amount') {
                                        $coupon_discount += $coupon->discount * $cartItem['quantity'];
                                    }
                                }
                            }
                        }
                    }

                    if($coupon_discount > 0){
                        Cart::where('user_id', Auth::user()->id)
                            ->where('owner_id', $coupon->user_id)
                            ->update(
                                [
                                    'discount' => $coupon_discount / count($carts),
                                    'coupon_code' => $request->code,
                                    'coupon_applied' => 1
                                ]
                            );
                        $response_message['response'] = 'success';
                        $response_message['message'] = translate('Coupon has been applied');
                    }
                    else{
                        $response_message['response'] = 'warning';
                        $response_message['message'] = translate('This coupon is not applicable to your cart products!');
                    }
                    
                } else {
                    $response_message['response'] = 'warning';
                    $response_message['message'] = translate('You already used this coupon!');
                }
            } else {
                $response_message['response'] = 'warning';
                $response_message['message'] = translate('Coupon expired!');
            }
        } else {
            $response_message['response'] = 'danger';
            $response_message['message'] = translate('Invalid coupon!');
        }

        $carts = Cart::where('user_id', Auth::user()->id)
                ->get();
        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();

        $returnHTML = view('frontend.partials.cart_summary', compact('coupon', 'carts', 'shipping_info'))->render();
        return response()->json(array('response_message' => $response_message, 'html'=>$returnHTML));
    }

    public function remove_coupon_code(Request $request)
    {
        Cart::where('user_id', Auth::user()->id)
                ->update(
                        [
                            'discount' => 0.00,
                            'coupon_code' => '',
                            'coupon_applied' => 0
                        ]
        );

        $coupon = Coupon::where('code', $request->code)->first();
        $carts = Cart::where('user_id', Auth::user()->id)
                ->get();

        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();

        return view('frontend.partials.cart_summary', compact('coupon', 'carts', 'shipping_info'));
    }

    public function apply_club_point(Request $request) {
        if (addon_is_activated('club_point')){

            $point = $request->point;

            if(Auth::user()->point_balance >= $point) {
                $request->session()->put('club_point', $point);
                flash(translate('Point has been redeemed'))->success();
            }
            else {
                flash(translate('Invalid point!'))->warning();
            }
        }
        return back();
    }

    public function remove_club_point(Request $request) {
        $request->session()->forget('club_point');
        return back();
    }

    public function order_confirmed()
    {
        $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));

        Cart::where('user_id', $combined_order->user_id)
                ->delete();

        //Session::forget('club_point');
        //Session::forget('combined_order_id');
        
        // foreach($combined_order->orders as $order){
        //     NotificationUtility::sendOrderPlacedNotification($order);
        // }

        return view('frontend.order_confirmed', compact('combined_order'));
    }
}
