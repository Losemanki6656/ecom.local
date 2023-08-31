<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductTranslation;
use App\Models\Upload;
use File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class BillzController extends Controller
{

    public function getToken()
    {
        $sec = auth()->user()->shop->secret;
        $name = auth()->user()->shop->key;

        $headerArray = array(
            'typ' => 'JWT',
            'alg' => 'HS256'
        );
        $payloadArray = array(
            'iss' => 'https://api.billz.uz/v1/',
            'iat' => 1638553677,
            'exp' => 1738553677,
            'sub' => $name
        );

        $header = $this->base64_url_encode(json_encode($headerArray, JSON_FORCE_OBJECT));
        $payload = $this->base64_url_encode(json_encode($payloadArray, JSON_FORCE_OBJECT));

        $unsignedToken = $header . '.' . $payload;

        $signature = hash_hmac("sha256", $unsignedToken, $sec, true);
        $encodedSignature = $this->base64_url_encode($signature);
        $token = $unsignedToken . '.' . $encodedSignature;
        return $token;
    }

    function base64_url_encode($input)
    {
        return trim(strtr(base64_encode($input), '+/', '-_'), '=');
    }

    public function productsUpload()
    {
        try {

            $data[] = [
                "jsonrpc" => "2.0",
                "method" => "products.get",
                "params" => [
                    "LastUpdatedDate" => "2018-03-21T18:19:25Z",
                    "WithProductPhotoOnly" => 1,
                    "IncludeEmptyStocks" => 0
                ],
                "id" => "1"
            ];

            $response = Http::withHeaders([
                "Content-Type" => "application/json"
            ])
                ->withToken($this->getToken())
                ->post("https://api.billz.uz/v1/", $data);

            $res = json_decode($response->body(), true)[0];

            $status = $res['error'] ?? null;

            if ($status) {
                return response()->json([
                    'status' => false,
                    'message' => $res['error']['message']
                ], 400);

            } else {

                DB::beginTransaction();
                try {

                    $name = '';
                    $x = 0;

                    foreach ($res['result'] as $key => $item) {

                        $description = $item['DESCRIPTION'] ?? '';
                        $collection = $item['COLLECTION'] ?? '';
                        $gender = $item['GENDER'] ?? '';
                        $season = $item['SEASON'] ?? '';
                        $size = $item['SIZE'] ?? '';
                        $sub_category = $item['SUB_CATEGORY'] ?? '';
                        $name = $item['name'] ?? ' ';
                        $brand = $item['properties']['BRAND'] ?? ' ';
                        $color = $item['properties']['COLOR'] ?? ' ';
                        $category = $item['properties']['CATEGORY'];

                        $meta_desc = $description . ' ' . $collection . ' ' . $gender . ' ' . $season . ' ' . $size . ' ' . $sub_category;
                        $desc = '<p>' . $meta_desc . '</p>';
                        $name = $name . ' ' . $brand . ' ' . $color . ' ' . $category;


                        $images = $item['imageUrls'] ?? null;
                        $b = [];
                        if ($images) {
                            foreach ($images as $key => $image) {
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

                                $b[] = $upload->id;
                            }
                        }


                        $product = new Product();
                        $product->user_id = auth()->user()->id;
                        $product->added_by = 'seller';
                        $product->category_id = 0;

                        if ($b)
                            $product->photos = implode(',', $b);

                        $product->video_provider = 'youtube';
                        $product->name = $name;
                        $product->barcode = $item['barCode'] ?? null;
                        $product->published = false;
                        $product->unit_price = $item['price'] ?? 0;
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
                        $product->current_stock = $item['qty'] ?? 1;
                        $product->unit = 1;
                        $product->weight = 0;
                        $product->min_qty = 1;
                        $product->starting_bid = 0;
                        $product->shipping_cost = 0;
                        $product->meta_description = $meta_desc;
                        $product->slug = $meta_desc;
                        $product->external_link_btn = 'Buy now';
                        $product->currency = 29;

                        $product->discount_type = 'amount';
                        $product->discount = $item['discountAmount'] ?? 0;

                        $product->description = $desc;
                        $product->save();


                        $stock = new ProductStock();
                        $stock->product_id = $product->id;
                        $stock->variant = '';
                        $stock->sku = $item['sku'] ?? null;
                        $stock->price = $item['price'] ?? 0;
                        $stock->qty = $item['qty'] ?? 0;
                        $stock->save();

                        $trans = new ProductTranslation();
                        $trans->product_id = $product->id;
                        $trans->name = $name;
                        $trans->description = $desc;
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

        } catch (\GuzzleHttp\Exception\RequestException $e) {

            flash(translate($e->getMessage()))->warning();
            return back();
        }

    }
}