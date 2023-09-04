<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductTranslation;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class BillzNewController extends Controller
{

    public function productsUpload()
    {


        $secret = auth()->user()->shop->billz2_secret;
        if (!$secret) {
            sleep(3);
            return response()->json([
                'status' => false,
                'message' => 'Please setting your secret for Billz new version!'
            ], 400);

        }

        $login = Http::withHeaders([
            "Accept" => "application/json"
        ])->post("https://api-admin.billz.ai/v1/auth/login", [
                    "secret_token" => $secret
                ]);


        $res = $res = json_decode($login->body(), true);

        if ($res['code'] == 200) {
            $token = $res['data']['access_token'];
        } else {
            return response()->json([
                'status' => false,
                'message' => $res['message']
            ], 400);
        }


        $response = Http::withHeaders([
            "Accept" => "application/json"
        ])
            ->withToken($token)
            ->get("https://api-admin.billz.ai/v2/products");

        $response = json_decode($response->body(), true);

        if ($response['count'] < 1) {
            return response()->json([
                'status' => false,
                'message' => 'Products not found!'
            ], 400);
        }


        DB::beginTransaction();
        try {

            $name = '';
            $x = 0;

            foreach ($response['products'] as $key => $item) {

                $description = $item['description'] ?? '';
                $name = $item['name'] ?? ' ';
                $brand = $item['brand_name'] ?? ' ';

                $category = '';
                $custom_field_value = '';
                $attribut = '';

                if ($item['categories']) {
                    foreach ($item['categories'] as $c => $cat) {
                        $category = $cat['name'];
                        break;
                    }
                }

                if ($item['custom_fields']) {
                    foreach ($item['custom_fields'] as $f => $field) {
                        $custom_field_value = $field['custom_field_value'] ?? '';
                        break;
                    }
                }

                if ($item['product_attributes']) {
                    foreach ($item['product_attributes'] as $a => $att) {
                        $attribut = $att['attribute_value'] ?? '';
                        break;
                    }
                }

                $qty = 0;
                if ($item['shop_measurement_values']) {
                    foreach ($item['shop_measurement_values'] as $q => $quantity) {
                        $qty += $quantity['active_measurement_value'] ?? 0;
                    }
                }


                $desc = '<p>' . $description . '</p>';
                $name = $name . ' ' . $brand . ' ' . $custom_field_value . ' ' . $category . ' ' . $attribut;


                $image = $item['main_image_url'] ?? null;

                $b = 0;
                if ($image) {

                    $path = $image['url'];
                    $imageName = now()->format('YmdHis') . basename($path);
                    Storage::disk('local')->put('uploads/all/' . $imageName, file_get_contents($path));
                    $ext = pathinfo(public_path() . $imageName, PATHINFO_EXTENSION);
                    $orgName = pathinfo(public_path() . $imageName, PATHINFO_FILENAME);

                    $upload = Upload::create([
                        'file_original_name' => $orgName,
                        'file_name' => 'uploads/all/' . $imageName,
                        'user_id' => auth()->user()->id,
                        'file_size' => 200,
                        'extension' => $ext,
                        'type' => 'image'
                    ]);

                    $b = $upload->id;
                }

                $price = 0;
                if ($item['shop_prices']) {
                    foreach ($item['shop_prices'] as $p => $amount) {
                        $price += $amount['retail_price'] ?? 0;
                    }
                }

                $product = new Product();
                $product->user_id = auth()->user()->id;
                $product->added_by = 'seller';
                $product->category_id = 0;

                if ($b)
                    $product->photos = $b;

                $product->video_provider = 'youtube';
                $product->name = $name;
                $product->barcode = $item['barcode'] ?? null;
                $product->published = false;
                $product->unit_price = $price;
                $product->variant_product = 0;
                $product->attributes = '[]';
                $product->choice_options = '[]';
                $product->colors = '[]';
                $product->todays_deal = 0;
                $product->published = 0;
                $product->approved = 1;
                $product->stock_visibility_state = 'quantity';
                $product->cash_on_delivery = 1;
                $product->featured = 0;
                $product->seller_featured = 0;
                $product->current_stock = $qty ?? 1;
                $product->unit = 1;
                $product->weight = 0;
                $product->min_qty = 1;
                $product->starting_bid = 0;
                $product->shipping_cost = 0;
                $product->meta_description = $desc;
                $product->slug = $desc;
                $product->external_link_btn = 'Buy now';
                $product->currency = 29;

                $product->discount_type = 'amount';
                $product->discount = 0;

                $product->description = $description;
                $product->save();


                $stock = new ProductStock();
                $stock->product_id = $product->id;
                $stock->variant = '';
                $stock->sku = $item['sku'] ?? null;
                $stock->price = $price;
                $stock->qty = $qty;
                $stock->save();

                $trans = new ProductTranslation();
                $trans->product_id = $product->id;
                $trans->name = $name;
                $trans->description = $description;
                $trans->unit = 1;
                $trans->lang = 'en';
                $trans->save();

                $x++;
            }

            DB::commit();


        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }

        return response()->json([
            'status' => true,
            'message' => 'Added ' . $x . ' products!'
        ]);

    }
}