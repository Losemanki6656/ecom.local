<?php

namespace App\Http\Controllers\Seller;

use App\Models\BusinessSetting;
use App\Models\ShopDetail;
use File;
use Illuminate\Http\Request;
use App\Models\Shop;
use Auth;
use Illuminate\Support\Facades\Http;
use Mtownsend\XmlToArray\XmlToArray;
use Storage;

class ShopController extends Controller
{
    public function index()
    {
        $shop = Auth::user()->shop;

        try {

            $response = Http::withHeaders([
                "Content-Type" => "text/xml;charset=utf-8"
            ])->send("POST", "https://home.courierexe.ru/api/", [
                        "body" => '<?xml version="1.0" encoding="utf-8"?>
                        <townlist>
                            <auth extra="245" />
                            <conditions>
                                <country>UZ</country>
                            </conditions>
                        </townlist>'
                    ]);

        } catch (\GuzzleHttp\Exception\RequestException $e) {

            flash(translate($e->getMessage()))->warning();
            return back();
        }

        $res = XmlToArray::convert($response->body());
        $townLists = $res['town'];

        return view('seller.shop', compact('shop', 'townLists'));
    }

    public function update(Request $request)
    {
        $shop = Shop::find($request->shop_id);

        if ($request->has('name') && $request->has('address')) {
            if ($request->has('shipping_cost')) {
                $shop->shipping_cost = $request->shipping_cost;
            }

            $shop->name = $request->name;
            $shop->emu_town = $request->adress_emu;
            $shop->address = $request->address;
            $shop->phone = $request->phone;
            $shop->slug = preg_replace('/\s+/', '-', $request->name) . '-' . $shop->id;
            $shop->meta_title = $request->meta_title;
            $shop->meta_description = $request->meta_description;
            $shop->logo = $request->logo;
        }

        if ($request->has('delivery_pickup_longitude') && $request->has('delivery_pickup_latitude')) {

            $shop->delivery_pickup_longitude = $request->delivery_pickup_longitude;
            $shop->delivery_pickup_latitude = $request->delivery_pickup_latitude;
        } elseif (
            $request->has('facebook') ||
            $request->has('google') ||
            $request->has('twitter') ||
            $request->has('youtube') ||
            $request->has('instagram')
        ) {
            $shop->facebook = $request->facebook;
            $shop->instagram = $request->instagram;
            $shop->google = $request->google;
            $shop->twitter = $request->twitter;
            $shop->youtube = $request->youtube;
        } elseif (
            $request->has('top_banner') ||
            $request->has('sliders') ||
            $request->has('banner_full_width_1') ||
            $request->has('banners_half_width') ||
            $request->has('banner_full_width_2')
        ) {
            $shop->top_banner = $request->top_banner;
            $shop->sliders = $request->sliders;
            $shop->banner_full_width_1 = $request->banner_full_width_1;
            $shop->banners_half_width = $request->banners_half_width;
            $shop->banner_full_width_2 = $request->banner_full_width_2;
        } elseif (
            $request->has('secret') ||
            $request->has('key')
        ) {
            $shop->secret = $request->secret;
            $shop->key = $request->key;
        } elseif (
            $request->has('billz2_secret')
        ) {
            $shop->billz2_secret = $request->billz2_secret;
        } elseif (
            $request->has('shop_name')
        ) {
            $shopDetail = ShopDetail::where('shop_id', $shop->id)->first();

            $fileName = time() . $request->d_file->getClientOriginalName();
            Storage::disk('public')->put('shop-details/' . $fileName, File::get($request->d_file));
            $filePath = 'storage/shop-details/' . $fileName;

            $shopDetail->name = $request->shop_name;
            $shopDetail->director = $request->shop_director;
            $shopDetail->inn = $request->inn;
            $shopDetail->bank = $request->bank;
            $shopDetail->mfo = $request->mfo;
            $shopDetail->b_number = $request->b_number;
            $shopDetail->d_file = $filePath;
            $shopDetail->save();
        }

        if ($shop->save()) {
            flash(translate('Your Shop has been updated successfully!'))->success();
            return back();
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    public function verify_form()
    {
        if (Auth::user()->shop->verification_info == null) {
            $shop = Auth::user()->shop;
            return view('seller.verify_form', compact('shop'));
        } else {
            flash(translate('Sorry! You have sent verification request already.'))->error();
            return back();
        }
    }

    public function verify_form_store(Request $request)
    {
        $data = array();
        $i = 0;
        foreach (json_decode(BusinessSetting::where('type', 'verification_form')->first()->value) as $key => $element) {
            $item = array();
            if ($element->type == 'text') {
                $item['type'] = 'text';
                $item['label'] = $element->label;
                $item['value'] = $request['element_' . $i];
            } elseif ($element->type == 'select' || $element->type == 'radio') {
                $item['type'] = 'select';
                $item['label'] = $element->label;
                $item['value'] = $request['element_' . $i];
            } elseif ($element->type == 'multi_select') {
                $item['type'] = 'multi_select';
                $item['label'] = $element->label;
                $item['value'] = json_encode($request['element_' . $i]);
            } elseif ($element->type == 'file') {
                $item['type'] = 'file';
                $item['label'] = $element->label;
                $item['value'] = $request['element_' . $i]->store('uploads/verification_form');
            }
            array_push($data, $item);
            $i++;
        }
        $shop = Auth::user()->shop;
        $shop->verification_info = json_encode($data);
        if ($shop->save()) {
            flash(translate('Your shop verification request has been submitted successfully!'))->success();
            return redirect()->route('seller.dashboard');
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    public function show()
    {
    }
}
