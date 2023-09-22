<?php

    namespace App\Http\Controllers;

    use App\Models\ExampleComission;
    use App\Models\Shop;
    use Illuminate\Http\Request;

    class ExampleComissionController extends Controller
    {

        public function index()
        {
            $comissions = ExampleComission::all();

            return view('backend.sellers.example-comission', [
                'comissions' => $comissions
            ]);
        }

        public function store(Request $request)
        {
            $a = [];
            foreach ($request->from_price as $key => $value) {
                if ($value && $request->to_price[$key] && $request->percent[$key]) {
                    $a[] = [
                        'from_price' => $value,
                        'to_price' => $request->to_price[$key],
                        'percent' => $request->percent[$key]
                    ];
                }

            }

            if (count($a)) {
                ExampleComission::create([
                    'title' => $request->title,
                    'description' => json_encode($a)
                ]);
            }

            flash(translate('Example has been inserted successfully'))->success();

            return back();

        }

        public function update_shop($id, Request $request)
        {
            $shop = Shop::find($id);
            $shop->example_comission_id = $request->example_comission_id;
            $shop->save();

            flash(translate('Example updated successfully'))->success();

            return back();
        }

        public function delete($id)
        {

            ExampleComission::find($id)->delete();

            flash(translate('Example updated successfully'))->success();

            return back();
        }
    }
