@extends('frontend.layouts.app')

@section('content')

    <!-- Steps -->
    <section class="pt-5 mb-4">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="row gutters-5 sm-gutters-10">
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-shopping-cart"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('1. My Cart') }}</h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-map"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('2. Shipping info') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col active">
                            <div class="text-center border border-bottom-6px p-2 text-primary">
                                <i class="la-3x mb-2 las la-truck cart-animate"
                                    style="margin-left: -100px; transition: 2s;"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('3. Delivery info') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-credit-card"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('4. Payment') }}</h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-check-circle"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('5. Confirmation') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Delivery Info -->
    <section class="py-4 gry-bg">
        <div class="container">
            <div class="row">
                <div class="col-xxl-8 col-xl-10 mx-auto">
                    <div class="border bg-white p-4 mb-4">
                        <form class="form-default" action="{{ route('checkout.store_delivery_info') }}" role="form"
                            method="POST">
                            @csrf
                            @foreach ($seller_products as $key => $item)
                                <div class="mb-4">
                                    <!-- Headers -->
                                    <div class="row gutters-2 border-bottom mb-3 pb-3 text-secondary fs-12">
                                        <div class="col-md-6">
                                            <h5 class="fs-16 fw-700 text-dark mb-0">
                                                {{ \App\Models\Shop::where('user_id', $key)->first()->name ?? 'Seller Not Found' }}
                                            </h5>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <span class="fw-700 fs-16 text-info" id="sellerProduct{{ $key }}"><i
                                                    class="las la-truck"></i> 0
                                                UZS</span>
                                        </div>
                                    </div>
                                    <!-- Cart Items -->
                                    <ul class="list-group list-group-flush">
                                        @php
                                            $total = 0;
                                        @endphp
                                        @foreach ($item as $key => $cartItem)
                                            @php
                                                $product = \App\Models\Product::find($cartItem['product_id']);
                                                $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
                                                // $total = $total + ($cartItem['price']  + $cartItem['tax']) * $cartItem['quantity'];
                                                $total = $total + cart_product_price($cartItem, $product, false) * $cartItem['quantity'];
                                                $product_name_with_choice = $product->getTranslation('name');
                                                if ($cartItem['variation'] != null) {
                                                    $product_name_with_choice = $product->getTranslation('name') . ' - ' . $cartItem['variation'];
                                                }
                                            @endphp
                                            <li class="list-group-item px-0">
                                                <div class="row gutters-2 align-items-center">
                                                    <!-- Product Image & name -->
                                                    <div class="col-md-6 d-flex align-items-center mb-2 mb-md-0">
                                                        <span class="mr-2 ml-0">
                                                            <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                                class="img-fit size-70px"
                                                                alt="{{ $product->getTranslation('name') }}"
                                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                        </span>
                                                        <span class="fs-14">{{ $product_name_with_choice }}</span>
                                                    </div>
                                                    <div class="col-md-6 text order-4 order-md-0 my-3 my-md-0 text-right">
                                                        <span
                                                            class="opacity-60 fs-12 d-block d-md-none">{{ translate('Total') }}</span>
                                                        <span
                                                            class="fw-700 fs-16 text-primary">{{ single_price(cart_product_price($cartItem, $product, false) * $cartItem['quantity']) }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach

                            <div class="mb-4">
                                <div class="row pt-3">
                                    <div class="col-md-6">
                                        <h6 class="fs-14 fw-700 mt-3">
                                            {{ translate('Choose Delivery Type') }}</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row gutters-5">
                                            <!-- Home Delivery -->
                                            @if (get_setting('shipping_type') != 'carrier_wise_shipping')
                                                <div class="col-6">
                                                    <label class="aiz-megabox d-block bg-white mb-0">
                                                        <input type="radio" name="shipping_type_1" value="home_delivery"
                                                            id="home_radio" onchange="show_pickup_point(this, 1)"
                                                            data-target=".pickup_point_id_1" checked>
                                                        <span class="d-flex p-3 aiz-megabox-elem rounded-0"
                                                            style="padding: 0.75rem 1.2rem;">
                                                            <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                            <span
                                                                class="flex-grow-1 pl-3 fw-600">{{ translate('Home Delivery') }}</span>
                                                        </span>
                                                    </label>
                                                </div>
                                                <!-- Carrier -->
                                            @else
                                                <div class="col-6">
                                                    <label class="aiz-megabox d-block bg-white mb-0">
                                                        <input type="radio" id="carrier_radio" name="shipping_type_1"
                                                            value="carrier" onchange="show_pickup_point(this, 1)"
                                                            data-target=".pickup_point_id_1" checked>
                                                        <span class="d-flex p-3 aiz-megabox-elem rounded-0"
                                                            style="padding: 0.75rem 1.2rem;">
                                                            <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                            <span
                                                                class="flex-grow-1 pl-3 fw-600">{{ translate('Carrier') }}</span>
                                                        </span>
                                                    </label>
                                                </div>
                                            @endif
                                            <!-- Local Pickup -->
                                            @if ($localPickups)
                                                <div class="col-6">
                                                    <label class="aiz-megabox d-block bg-white mb-0">
                                                        <input type="radio" name="shipping_type_1" value="pickup_point"
                                                            id="pickup_radio" onchange="show_pickup_point(this, 1)"
                                                            data-target=".pickup_point_id_1">
                                                        <span class="d-flex p-3 aiz-megabox-elem rounded-0"
                                                            style="padding: 0.75rem 1.2rem;">
                                                            <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                            <span
                                                                class="flex-grow-1 pl-3 fw-600">{{ translate('Local Pickup') }}</span>
                                                        </span>
                                                    </label>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Pickup Point List -->

                                        @if ($localPickups)
                                            <div class="mt-4 pickup_point_id_1 d-none">
                                                <select class="form-control aiz-selectpicker rounded-0"
                                                    name="pickup_point_emu" data-live-search="true" id="pickup_select"
                                                    onchange="pickupSelect()">
                                                    <option>
                                                        {{ translate('Select your nearest pickup point') }}
                                                    </option>
                                                    @foreach ($localPickups as $pick_up_point)
                                                        <option value="{{ $pick_up_point['code'] }}"
                                                            data-content="<span class='d-block'>
                                                                                <span class='d-block fs-16 fw-600 mb-2'> {{ $pick_up_point['name'] }}</span>
                                                                                <span class='d-block opacity-50 fs-12'><i class='las la-map-marker'></i> {{ $pick_up_point['address'] }}</span>
                                                                                <span class='d-block opacity-50 fs-12'><i class='las la-map-marker'></i> {{ $pick_up_point['town']['@attributes']['regionname'] }}</span>
                                                                            </span>">
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row pt-3 d-flex" id="pickupMap">
                                    <div class="col-md-12 pt-5 d-flex" id="map" style="width: 100%;">
                                    </div>
                                </div>

                                <!-- Carrier Wise Shipping -->
                                @if (get_setting('shipping_type') == 'carrier_wise_shipping')
                                    <div class="row pt-3 carrier_id_{{ $key }}">
                                        @foreach ($carrier_list as $carrier_key => $carrier)
                                            <div class="col-md-12 mb-2">
                                                <label class="aiz-megabox d-block bg-white mb-0">
                                                    <input type="radio" name="carrier_id_{{ $key }}"
                                                        value="{{ $carrier->id }}"
                                                        @if ($carrier_key == 0) checked @endif>
                                                    <span class="d-flex p-3 aiz-megabox-elem rounded-0">
                                                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                        <span class="flex-grow-1 pl-3 fw-600">
                                                            <img src="{{ uploaded_asset($carrier->logo) }}"
                                                                alt="Image" class="w-50px img-fit">
                                                        </span>
                                                        <span class="flex-grow-1 pl-3 fw-600">{{ $carrier->name }}</span>
                                                        <span
                                                            class="flex-grow-1 pl-3 fw-600">{{ translate('Transit in') . ' ' . $carrier->transit_time }}</span>
                                                        <span
                                                            class="flex-grow-1 pl-3 fw-600">{{ single_price(carrier_base_price($carts, $carrier->id, $key)) }}</span>
                                                    </span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="row pt-3" id="pickupMenu" style="display: none">
                                    <div class="col-md-6 pb-3">
                                        <h6 class="fs-14 fw-700 mt-3" id="pickup_phone">
                                        </h6>
                                        <h6 class="fs-14 fw-700 mt-3" id="pickup_worktime">
                                        </h6>
                                        <h6 class="fs-14 fw-700 mt-3" id="pickup_address">
                                        </h6>
                                        <h6 class="fs-14 fw-700 mt-3" id="price_emu">
                                        </h6>
                                        <h6 class="fs-14 fw-700 mt-3" id="allsumm">
                                        </h6>
                                        <h6 class="fs-14 fw-700 mt-3" id="allmass">
                                        </h6>
                                    </div>
                                    <div class="col-md-6"></div>
                                </div>
                            </div>

                            <div class="pt-4 d-flex justify-content-between align-items-center">
                                <!-- Return to shop -->
                                <a href="{{ route('home') }}" class="btn btn-link fs-14 fw-700 px-0">
                                    <i class="la la-arrow-left fs-16"></i>
                                    {{ translate('Return to shop') }}
                                </a>
                                <!-- Continue to Payment -->
                                <button type="submit"
                                    class="btn btn-primary fs-14 fw-700 rounded-0 px-4">{{ translate('Continue to Payment') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div id="loader" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body c-scrollbar-light position-relative" id="info-modal-content">
                    <div class="c-preloader text-center absolute-center">
                        <i class="las la-spinner la-spin la-3x opacity-70"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            openStreetMap();
            $('#pickupMap').removeClass('d-flex');
            $('#pickupMap').addClass('d-none');

            $('#loader').modal('show');
            $.ajax({
                url: "{{ route('checkout.pickupCodeInfo') }}",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    code: null
                },
                success: function(res) {

                    $('#loader').modal('hide');
                    $("#price_emu").html('<i class="las la-truck"></i> Общая сумма: ' + res[
                            'price_emu'] +
                        ' UZS');
                    $("#allmass").html('<i class="las la-weight-hanging"></i> Общая масса: ' + res[
                            'mass'] +
                        ' кг');
                    $("#pickupMenu").show();
                    let array = res.seller_emu_price;
                    array.forEach(element => {
                        $("#sellerProduct" + element['key']).html(
                            '<i class="las la-weight-hanging"></i> ' + element['mass'] +
                            ' кг;' + '&nbsp &nbsp &nbsp<i class="las la-truck"></i> ' +
                            element[
                                'price'] + ' UZS'
                        );
                    });
                },
                error: function(error) {
                    console.log(error);
                    AIZ.plugins.notify('warning', error.statusText);
                    $('#loader').modal('hide');
                }
            });

        });
    </script>
    <script>
        function pickupSelect() {

            $('#loader').modal('show');

            let code = $("#pickup_select").val();

            var array = @json($localPickups);
            array.forEach(element => {
                if (code == element['code']) {
                    $("#pickup_phone").html('<i class="las la-phone"></i> ' + element['phone']);
                    $("#pickup_worktime").html('<i class="las la-clock"></i> ' + element['worktime']);
                    $("#pickup_address").html('<i class="las la-map-marker"></i> ' + element['town'][
                            '@attributes'
                        ][
                            'regionname'
                        ] +
                        ', ' + element['name'] + ', ' + element['address']);

                }
            });


            $.ajax({
                url: "{{ route('checkout.pickupCodeInfo') }}",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    code: code
                },
                success: function(res) {

                    $('#loader').modal('hide');
                    $("#price_emu").html('<i class="las la-truck"></i> Общая сумма: ' + res['price_emu'] +
                        ' UZS');
                    // $("#allsumm").html('<i class="las la-dollar-sign"></i> Общая сумма: ' + res['summ'] +
                    //     ' UZS');
                    $("#allmass").html('<i class="las la-weight-hanging"></i> Общая масса: ' + res['mass'] +
                        ' кг');
                    $("#pickupMenu").show();
                    let array = res.seller_emu_price;
                    array.forEach(element => {
                        $("#sellerProduct" + element['key']).html(
                            '<i class="las la-weight-hanging"></i> ' + element['mass'] +
                            ' кг;' + '&nbsp &nbsp &nbsp<i class="las la-truck"></i> ' + element[
                                'price'] + ' UZS'
                        );
                    });
                },
                error: function(error) {
                    console.log(error);
                    AIZ.plugins.notify('warning', error.statusText);
                    $('#loader').modal('hide');
                }
            });


        }

        function openStreetMap() {

            var L = window.L;
            var map = L.map('map', {
                // scrollWheelZoom: false,
                tap: false
            });
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var array = @json($localPickups);
            array.forEach(element => {
                L.marker([element['latitude'], element['longitude']]).addTo(map)
                    .bindPopup(element['name'])
                    .openPopup();
            });

            map.setView(new L.LatLng(41.2942336, 69.2518912), 7);

        }
    </script>

    <script type="text/javascript">
        function display_option(key) {

        }

        function show_pickup_point(el, type) {

            if ($("#pickup_radio").is(":checked")) {
                $('#pickupMap').removeClass('d-none');
                $('#pickupMap').addClass('d-flex');
            } else {
                $('#pickupMap').addClass('d-none');
                $('#pickupMap').removeClass('d-flex');

                $('#loader').modal('show');

                $.ajax({
                    url: "{{ route('checkout.pickupCodeInfo') }}",
                    method: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        code: null
                    },
                    success: function(res) {

                        $('#loader').modal('hide');
                        $("#price_emu").html('<i class="las la-truck"></i> Общая сумма: ' + res['price_emu'] +
                            ' UZS');
                        $("#allmass").html('<i class="las la-weight-hanging"></i> Общая масса: ' + res['mass'] +
                            ' кг');
                        $("#pickupMenu").show();
                        let array = res.seller_emu_price;
                        array.forEach(element => {
                            $("#sellerProduct" + element['key']).html(
                                '<i class="las la-weight-hanging"></i> ' + element['mass'] +
                                ' кг;' + '&nbsp &nbsp &nbsp<i class="las la-truck"></i> ' + element[
                                    'price'] + ' UZS'
                            );
                        });
                    },
                    error: function(error) {
                        console.log(error);
                        AIZ.plugins.notify('warning', error.statusText);
                        $('#loader').modal('hide');
                    }
                });
            }

            var value = $(el).val();
            var target = $(el).data('target');

            if (value == 'home_delivery' || value == 'carrier') {
                if (!$(target).hasClass('d-none')) {
                    $(target).addClass('d-none');
                }
                $('.carrier_id_' + type).removeClass('d-none');
            } else {
                $(target).removeClass('d-none');
                $('.carrier_id_' + type).addClass('d-none');
            }
        }
    </script>
@endsection
