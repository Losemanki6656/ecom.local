<?php

namespace App\Http\Controllers\Seller;

use App\Http\Requests\SellerProfileRequest;
use App\Models\ShopDetail;
use App\Models\User;
use Auth;
use File;
use Hash;
use Illuminate\Http\Request;
use Storage;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $addresses = $user->addresses;
        return view('seller.profile.index', compact('user','addresses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SellerProfileRequest $request , $id)
    {
        if(env('DEMO_MODE') == 'On'){
            flash(translate('Sorry! the action is not permitted in demo '))->error();
            return back();
        }

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->phone = $request->phone;

        if($request->new_password != null && ($request->new_password == $request->confirm_password)){
            $user->password = Hash::make($request->new_password);
        }

        $user->avatar_original = $request->photo;

        $shop = $user->shop;

        if($shop){
            $shop->cash_on_delivery_status = $request->cash_on_delivery_status;
            $shop->bank_payment_status = $request->bank_payment_status;
            $shop->bank_name = $request->bank_name;
            $shop->bank_acc_name = $request->bank_acc_name;
            $shop->bank_acc_no = $request->bank_acc_no;
            $shop->bank_routing_no = $request->bank_routing_no;

            $shop->save();
        }

        $user->save();

        flash(translate('Your Profile has been updated successfully!'))->success();
        return back();
    }

    public function bankSetting(Request $request)
    {
        $shop = ShopDetail::where('shop_id', auth()->user()->shop->id)->first();
        if($shop) return back();

        $mfo = str_replace('_','', $request->mfo);
        $inn = str_replace('_','', $request->inn);
        $b_number = str_replace('_','', $request->b_number);

        if(strlen($mfo) <> 5 || strlen($inn) <> 9 || strlen($b_number) <> 20) {
            flash(translate('Sorry! Valdidate wrong.'))->error();
            return back();
        }

        $fileName = time() . $request->d_file->getClientOriginalName();
        Storage::disk('public')->put('shop-details/' . $fileName, File::get($request->d_file));
        $filePath = 'storage/shop-details/' . $fileName;

        $shopDetail = new ShopDetail();
        $shopDetail->shop_id = auth()->user()->shop->id;
        $shopDetail->name = $request->name;
        $shopDetail->director = $request->director;
        $shopDetail->inn = $request->inn;
        $shopDetail->bank = $request->bank;
        $shopDetail->mfo = $request->mfo;
        $shopDetail->b_number = $request->b_number;
        $shopDetail->d_file = $filePath;
        $shopDetail->save();

        flash(translate('Your Profile has been updated successfully!'))->success();
        return back();

    }
}
