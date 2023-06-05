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
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-truck"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('3. Delivery info') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col active">
                            <div class="text-center border border-bottom-6px p-2 text-primary">
                                <i class="la-3x mb-2 las la-credit-card cart-animate"
                                    style="margin-right: -100px; transition: 2s;"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('4. Payment') }}</h3>
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

    <!-- Payment Info -->
    <section class="mb-4">
        <div class="container text-left">
            <div class="row">
                <div class="col-lg-8">
                    <form action="{{ route('payment.checkout') }}" class="form-default" role="form" method="POST"
                        id="checkout-form">
                        @csrf
                        <input type="hidden" name="owner_id" value="{{ $carts[0]['owner_id'] }}">

                        <input type="hidden" name="BindID" id="bindID" value="{{ $bind }}">
                        <input type="hidden" name="otp" id="otp" value="">
                        <input type="hidden" name="trans" id="trans" value="">

                        <div class="card rounded-0 border shadow-none">
                            <!-- Additional Info -->
                            <div class="card-header p-4 border-bottom-0">
                                <h3 class="fs-16 fw-700 text-dark mb-0">
                                    {{ translate('Any additional info?') }}
                                </h3>
                            </div>
                            <div class="form-group px-4">
                                <textarea name="additional_info" rows="5" class="form-control rounded-0"
                                    placeholder="{{ translate('Type your text...') }}"></textarea>
                            </div>

                            <div class="card-header p-4 border-bottom-0">
                                <h3 class="fs-16 fw-700 text-dark mb-0">
                                    {{ translate('Select a payment option') }}
                                </h3>
                            </div>
                            <!-- Payment Options -->
                            <div class="card-body text-center px-4 pt-0">
                                <div class="row gutters-10">
                                    <!-- Paypal -->
                                    {{-- @if (get_setting('paypal_payment') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="paypal" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/paypal.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span class="d-block fw-600 fs-15">{{ translate('Paypal') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif --}}
                                    <!--Stripe -->
                                    {{-- @if (get_setting('stripe_payment') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="stripe" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/stripe.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Stripe') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- Mercadopago -->
                                    @if (get_setting('mercadopago_payment') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="mercadopago" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/mercadopago.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Mercadopago') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- sslcommerz -->
                                    @if (get_setting('sslcommerz_payment') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="sslcommerz" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/sslcommerz.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('sslcommerz') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- instamojo -->
                                    @if (get_setting('instamojo_payment') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="instamojo" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/instamojo.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Instamojo') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- razorpay -->
                                    @if (get_setting('razorpay') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="razorpay" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/rozarpay.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Razorpay') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- paystack -->
                                    @if (get_setting('paystack') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="paystack" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/paystack.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Paystack') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- voguepay -->
                                    @if (get_setting('voguepay') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="voguepay" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/vogue.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('VoguePay') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- payhere -->
                                    @if (get_setting('payhere') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="payhere" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/payhere.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('payhere') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- ngenius -->
                                    @if (get_setting('ngenius') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="ngenius" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/ngenius.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('ngenius') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- iyzico -->
                                    @if (get_setting('iyzico') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="iyzico" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/iyzico.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Iyzico') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- nagad -->
                                    @if (get_setting('nagad') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="nagad" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/nagad.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Nagad') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- bkash -->
                                    @if (get_setting('bkash') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="bkash" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/bkash.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Bkash') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- aamarpay -->
                                    @if (get_setting('aamarpay') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="aamarpay" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/aamarpay.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Aamarpay') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- authorizenet -->
                                    @if (get_setting('authorizenet') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="authorizenet" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/authorizenet.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Authorize Net') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- payku -->
                                    @if (get_setting('payku') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="payku" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/payku.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Payku') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- African Payment Getaway -->
                                    @if (addon_is_activated('african_pg'))
                                        <!-- flutterwave -->
                                        @if (get_setting('flutterwave') == 1)
                                            <div class="col-6 col-xl-3 col-md-4">
                                                <label class="aiz-megabox d-block mb-3">
                                                    <input value="flutterwave" class="online_payment"
                                                        type="radio" name="payment_option" checked>
                                                    <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                        <img src="{{ static_asset('assets/img/cards/flutterwave.png') }}"
                                                            class="img-fit mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('flutterwave') }}</span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        @endif
                                        <!-- payfast -->
                                        @if (get_setting('payfast') == 1)
                                            <div class="col-6 col-xl-3 col-md-4">
                                                <label class="aiz-megabox d-block mb-3">
                                                    <input value="payfast" class="online_payment" type="radio"
                                                        name="payment_option" checked>
                                                    <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                        <img src="{{ static_asset('assets/img/cards/payfast.png') }}"
                                                            class="img-fit mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('payfast') }}</span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        @endif
                                    @endif
                                    <!--paytm -->
                                    @if (addon_is_activated('paytm') && get_setting('paytm_payment') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="paytm" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/paytm.jpg') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Paytm') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- toyyibpay -->
                                    @if (addon_is_activated('paytm') && get_setting('toyyibpay_payment') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="toyyibpay" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/toyyibpay.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('ToyyibPay') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- myfatoorah -->
                                    @if (addon_is_activated('paytm') && get_setting('myfatoorah') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="myfatoorah" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/myfatoorah.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('MyFatoorah') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- khalti -->
                                    @if (addon_is_activated('paytm') && get_setting('khalti_payment') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="Khalti" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem p-3">
                                                    <img src="{{ static_asset('assets/img/cards/khalti.png') }}"
                                                        class="img-fluid mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Khalti') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif --}}
                                    <!-- Cash Payment -->
                                    @if (get_setting('cash_payment') == 1)
                                        @php
                                            $digital = 0;
                                            $cod_on = 1;
                                            foreach ($carts as $cartItem) {
                                                $product = \App\Models\Product::find($cartItem['product_id']);
                                                if ($product['digital'] == 1) {
                                                    $digital = 1;
                                                }
                                                if ($product['cash_on_delivery'] == 0) {
                                                    $cod_on = 0;
                                                }
                                            }
                                        @endphp
                                        @if ($digital != 1 && $cod_on == 1)
                                            <div class="col-6 col-xl-3 col-md-4">
                                                <label class="aiz-megabox d-block mb-3">
                                                    <input value="cash_on_delivery" class="online_payment"
                                                        onclick="pay_method('cash_on_delivery')" type="radio"
                                                        name="payment_option">
                                                    <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                        <img src="{{ static_asset('assets/img/cards/cod.png') }}"
                                                            class="img-fit mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('Cash on Delivery') }}</span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        @endif
                                    @endif
                                    @if (Auth::check())
                                        <!-- Offline Payment -->
                                        @if (addon_is_activated('offline_payment'))
                                            @foreach (\App\Models\ManualPaymentMethod::all() as $method)
                                                <div class="col-6 col-xl-3 col-md-4">
                                                    <label class="aiz-megabox d-block mb-3">
                                                        <input value="{{ $method->heading }}" type="radio"
                                                            name="payment_option" class="offline_payment_option"
                                                            onchange="toggleManualPaymentData({{ $method->id }})"
                                                            data-id="{{ $method->id }}" checked>
                                                        <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                            <img src="{{ uploaded_asset($method->photo) }}"
                                                                class="img-fit mb-2">
                                                            <span class="d-block text-center">
                                                                <span
                                                                    class="d-block fw-600 fs-15">{{ $method->heading }}</span>
                                                            </span>
                                                        </span>
                                                    </label>
                                                </div>
                                            @endforeach

                                            @foreach (\App\Models\ManualPaymentMethod::all() as $method)
                                                <div id="manual_payment_info_{{ $method->id }}" class="d-none">
                                                    @php echo $method->description @endphp
                                                    @if ($method->bank_info != null)
                                                        <ul>
                                                            @foreach (json_decode($method->bank_info) as $key => $info)
                                                                <li>{{ translate('Bank Name') }} -
                                                                    {{ $info->bank_name }},
                                                                    {{ translate('Account Name') }} -
                                                                    {{ $info->account_name }},
                                                                    {{ translate('Account Number') }} -
                                                                    {{ $info->account_number }},
                                                                    {{ translate('Routing Number') }} -
                                                                    {{ $info->routing_number }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif
                                    @endif

                                    <div class="col-6 col-xl-3 col-md-4">
                                        <label class="aiz-megabox d-block mb-3">
                                            <input value="paymo" class="online_payment" type="radio"
                                                onclick="pay_method('paymo')" name="payment_option" checked>
                                            <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                <img src="{{ static_asset('assets/img/cards/paymo.png') }}"
                                                    class="img-fit mb-2">
                                                <span class="d-block text-center">
                                                    <span class="d-block fw-600 fs-15">{{ translate('Paymo') }}</span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>

                                </div>

                                <!-- Offline Payment Fields -->
                                @if (addon_is_activated('offline_payment'))
                                    <div class="d-none mb-3 rounded border bg-white p-3 text-left">
                                        <div id="manual_payment_description">

                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>{{ translate('Transaction ID') }} <span
                                                        class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control mb-3" name="trx_id"
                                                    id="trx_id" placeholder="{{ translate('Transaction ID') }}"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ translate('Photo') }}</label>
                                            <div class="col-md-9">
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                            {{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose image') }}
                                                    </div>
                                                    <input type="hidden" name="photo" class="selected-files">
                                                </div>
                                                <div class="file-preview box sm">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Wallet Payment -->
                                @if (Auth::check() && get_setting('wallet_system') == 1)
                                    <div class="py-4 px-4 text-center bg-soft-warning mt-4">
                                        <div class="fs-14 mb-3">
                                            <span class="opacity-80">{{ translate('Or, Your wallet balance :') }}</span>
                                            <span class="fw-700">{{ single_price(Auth::user()->balance) }}</span>
                                        </div>
                                        @if (Auth::user()->balance < $total)
                                            <button type="button" class="btn btn-secondary" disabled>
                                                {{ translate('Insufficient balance') }}
                                            </button>
                                        @else
                                            <button type="button" onclick="use_wallet()"
                                                class="btn btn-primary fs-14 fw-700 px-5 rounded-0">
                                                {{ translate('Pay with wallet') }}
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Agree Box -->
                            <div class="pt-3 px-4 fs-14">
                                <label class="aiz-checkbox">
                                    <input type="checkbox" required id="agree_checkbox">
                                    <span class="aiz-square-check"></span>
                                    <span>{{ translate('I agree to the') }}</span>
                                </label>
                                <a href="{{ route('terms') }}"
                                    class="fw-700">{{ translate('terms and conditions') }}</a>,
                                <a href="{{ route('returnpolicy') }}"
                                    class="fw-700">{{ translate('return policy') }}</a> &
                                <a href="{{ route('privacypolicy') }}"
                                    class="fw-700">{{ translate('privacy policy') }}</a>
                            </div>

                            <div class="row align-items-center pt-3 px-4 mb-4">
                                <!-- Return to shop -->
                                <div class="col-6">
                                    <a href="{{ route('home') }}" class="btn btn-link fs-14 fw-700 px-0">
                                        <i class="las la-arrow-left fs-16"></i>
                                        {{ translate('Return to shop') }}
                                    </a>
                                </div>
                                <!-- Complete Ordert -->
                                <div class="col-6 text-right">
                                    <button type="button" onclick="submitOrder(this)"
                                        class="btn btn-primary fs-14 fw-700 rounded-0 px-4">{{ translate('Complete Order') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Cart Summary -->
                <div class="col-lg-4 mt-lg-0 mt-4" id="cart_summary">
                    @include('frontend.partials.cart_summary')
                </div>

                <div class="modal fade" id="new-payment" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ translate('Select Card') }}</h5>
                            </div>
                            <div class="modal-body">
                                <div class='window'>
                                    <div class='order-info'>
                                        <div class='order-info-content'>
                                            <h5>Order Summary</h5>
                                            {{-- <table class='table'>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <span class='thin'>{{ translate('Subtotal') }}</span>

                                                        </td>
                                                        <td style="max-width: 160px">
                                                            <div class='price'>{{ single_price($subtotal) }}</div>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span class='thin'>{{ translate('Tax') }}</span>

                                                        </td>
                                                        <td style="max-width: 160px">
                                                            <div class='price'>{{ single_price($tax) }}</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span class='thin'>{{ translate('Total Shipping') }}</span>

                                                        </td>
                                                        <td style="max-width: 160px">
                                                            <div class='price'>{{ single_price($shipping) }}</div>
                                                        </td>

                                                    </tr>
                                                    @php
                                                        $total = $subtotal + $tax + $shipping;
                                                        if (Session::has('club_point')) {
                                                            $total -= Session::get('club_point');
                                                        }
                                                        if ($coupon_discount > 0) {
                                                            $total -= $coupon_discount;
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <span class='thin'
                                                                style="font-weight: bold">{{ translate('Total') }}</span>

                                                        </td>
                                                        <td style="max-width: 160px">
                                                            <div class='price' style="font-weight: bold">
                                                                {{ single_price($total) }}</div>
                                                        </td>

                                                    </tr>
                                                </tbody>
                                            </table> --}}

                                            <div id="summary_modal">
                                            </div>
                                            
                                            <div class='line'></div>

                                            <a type="button" class="btn btn-primary"
                                                href="{{ route('my-cards') }}" style="width: 100%"> <i
                                                    class="fa fa-plus"></i> {{ translate('Add Card') }} </a>
                                        </div>
                                    </div>
                                    <div class='credit-info'>
                                        <div class='credit-info-content'>
                                            <div class="mb-3">
                                                Please select your card:
                                            </div>
                                            <div class="mb-3">
                                                <select id="mounth">
                                                    @foreach ($bindCards as $card)
                                                        <option value="{{ $card->id }}">{{ $card->pan }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <img src='{{ static_asset('assets/img/cards/paymo-transparent.png') }}'
                                                height='80' class='credit-card-image' />
                                            @php
                                                $cardHolder = '';
                                                $exDate = '';
                                                if ($bindCards->count() > 0) {
                                                    $cardHolder = $bindCards[0]->card_holder;
                                                    $exDate = $bindCards[0]->expiry;
                                                }
                                            @endphp
                                            <div class="row mb-3">
                                                <div class="col-9">
                                                    Card Holder <br>
                                                    <h6 id="cardHolder">{{ $cardHolder }}</h6>
                                                </div>
                                                <div class="col-3">
                                                    Expiry <br>
                                                    <h6 id="ExpDate">{{ $exDate }}</h6>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" data-dismiss="modal"
                                    class="btn btn-secondary">{{ translate('Cancel') }}</button>
                                <button type="button" onclick="paymentSubmit()" class="btn btn-primary">
                                    {{ translate('Complete Order') }} </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <style lang="scss">
        .select-hidden {
            display: none;
            visibility: hidden;
            /* padding-right: 10px; */
        }

        .select {
            cursor: pointer;
            display: inline-block;
            position: relative;
            font-size: 16px;
            color: #fff;
            width: 100%;
            height: 40px;
            border-radius: 4px;
        }

        .select-styled {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            border-radius: 4px;
            background-color: #5794E0;
            padding: 8px 15px;
            -moz-transition: all 0.2s ease-in;
            -o-transition: all 0.2s ease-in;
            -webkit-transition: all 0.2s ease-in;
            transition: all 0.2s ease-in;
        }

        .select-styled:after {
            content: "";
            width: 0;
            height: 0;
            border: 7px solid transparent;
            border-color: #fff transparent transparent transparent;
            position: absolute;
            top: 16px;
            right: 10px;
        }

        .select-styled:hover {
            background-color: #5794E0;
        }

        .select-styled:active,
        .select-styled.active {
            background-color: #5794E0;
        }

        .select-styled:active:after,
        .select-styled.active:after {
            top: 9px;
            border-radius: 4px;
            border-color: transparent transparent #fff transparent;
        }

        .select-options {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            left: 0;
            z-index: 999;
            margin: 0;
            padding: 0;
            list-style: none;
            background-color: #5794E0;
        }

        .select-options li {
            margin: 0;
            padding: 12px 0;
            text-indent: 15px;
            border-top: 1px solid #5794E0;
            -moz-transition: all 0.15s ease-in;
            -o-transition: all 0.15s ease-in;
            -webkit-transition: all 0.15s ease-in;
            transition: all 0.15s ease-in;
        }

        .select-options li:hover,
        .select-options li.is-selected {
            color: #5794E0;
            background: #fff;
            border-radius: 4px;
        }

        .select-options li[rel="hide"] {
            display: none;
        }

        .thin {
            font-weight: 400;
        }

        .small {
            font-size: 12px;
            font-size: .8rem;
        }

        .half-input-table {
            border-collapse: collapse;
            width: 100%;
        }

        .half-input-table td:first-of-type {
            border-right: 10px solid #4488dd;
            width: 50%;
        }

        .window {
            /* height: 540px; */
            /* width: 800px; */
            /* background: #bab8b8; */
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            /* box-shadow: 0px 15px 50px 10px rgba(0, 0, 0, 0.2); */
            /* border-radius: 30px; */
            /* z-index: 10; */
        }

        .order-info {
            height: 100%;
            width: 50%;
            padding-left: 25px;
            padding-right: 25px;
            box-sizing: border-box;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -webkit-justify-content: center;
            -ms-flex-pack: center;
            justify-content: center;
            position: relative;
        }

        .price {
            bottom: 0px;
            /* position: absolute; */
            right: 0px;
            color: #4488dd;
        }

        .order-table td:first-of-type {
            width: 25%;
        }

        .order-table {
            position: relative;
        }

        .line {
            height: 1px;
            width: 100%;
            background: #ddd;
        }

        .order-table td:last-of-type {
            vertical-align: top;
            padding-left: 25px;
        }

        .order-info-content {
            table-layout: fixed;

        }

        .full-width {
            width: 100%;
        }

        .pay-btn {
            border: none;
            background: #22b877;
            line-height: 2em;
            border-radius: 10px;
            font-size: 19px;
            font-size: 1.2rem;
            color: #fff;
            cursor: pointer;
            position: absolute;
            bottom: 25px;
            width: calc(100% - 50px);
            -webkit-transition: all .2s ease;
            transition: all .2s ease;
        }

        .pay-btn:hover {
            background: #22a877;
            color: #eee;
            -webkit-transition: all .2s ease;
            transition: all .2s ease;
        }

        .total {
            margin-top: 25px;
            font-size: 20px;
            font-size: 1.3rem;
            position: absolute;
            bottom: 30px;
            right: 27px;
            left: 35px;
        }

        .dense {
            line-height: 1.2em;
            font-size: 16px;
            font-size: 1rem;
        }

        .input-field {
            background: rgba(255, 255, 255, 0.1);
            margin-bottom: 10px;
            margin-top: 3px;
            line-height: 1.5em;
            font-size: 20px;
            font-size: 1.3rem;
            border: none;
            padding: 5px 10px 5px 10px;
            color: #fff;
            box-sizing: border-box;
            width: 100%;
            margin-left: auto;
            margin-right: auto;
        }

        .credit-info {
            background: #4488dd;
            height: 100%;
            width: 50%;
            color: #eee;
            -webkit-box-pack: center;
            -webkit-justify-content: center;
            -ms-flex-pack: center;
            justify-content: center;
            font-size: 14px;
            font-size: .9rem;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            box-sizing: border-box;
            padding-left: 25px;
            padding-right: 25px;
            border-top-right-radius: 30px;
            border-bottom-right-radius: 30px;
            position: relative;
        }

        /* .dropdown-btn {
                                                                                                                background: rgba(255, 255, 255, 0.1);
                                                                                                                width: 100%;
                                                                                                                border-radius: 5px;
                                                                                                                text-align: center;
                                                                                                                line-height: 1.5em;
                                                                                                                cursor: pointer;
                                                                                                                position: relative;
                                                                                                                -webkit-transition: background .2s ease;
                                                                                                                transition: background .2s ease;
                                                                                                            }

                                                                                                            .dropdown-btn:after {
                                                                                                                content: '\25BE';
                                                                                                                right: 8px;
                                                                                                                position: absolute;
                                                                                                            }

                                                                                                            .dropdown-btn:hover {
                                                                                                                background: rgba(255, 255, 255, 0.2);
                                                                                                                -webkit-transition: background .2s ease;
                                                                                                                transition: background .2s ease;
                                                                                                            }

                                                                                                            .dropdown-select {
                                                                                                                display: none;
                                                                                                            } */

        .credit-card-image {
            display: block;
            height: 100px;
            margin-left: auto;
            margin-right: auto;
            /* margin-top: 35px; */
            /* margin-bottom: 15px; */
        }

        .credit-info-content {
            margin-top: 25px;
            -webkit-flex-flow: column;
            -ms-flex-flow: column;
            flex-flow: column;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            width: 100%;
        }

        @media (max-width: 600px) {
            .window {
                width: 100%;
                height: 100%;
                display: block;
                border-radius: 0px;
            }

            .order-info {
                width: 100%;
                height: auto;
                /* padding-bottom: 100px; */
                border-radius: 0px;
            }

            .credit-info {
                width: 100%;
                height: auto;
                /* padding-bottom: 100px; */
                border-radius: 0px;
            }

            .pay-btn {
                border-radius: 0px;
            }
        }
    </style>
@endsection

@section('script')
    <script>
        let html = document.getElementById("summary").innerHTML;
        document.getElementById("summary_modal").innerHTML = html;
    </script>
    <script>
        function paymentSubmit() {
            if ({{ $bindCards->count() }}) {
                $('#new-payment').modal('hide');
                Swal.fire({
                    title: 'Do you really want to do this?',
                    showCancelButton: true,
                    confirmButtonText: 'Apply',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return $.ajax({
                            type: 'POST',
                            url: "{{ route('payment.paySuccess') }}",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "bindID": $('#bindID').val()
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Your verification code has been sent to phone number +' +
                                        response.phone,
                                    input: 'text',
                                    inputAttributes: {
                                        autocapitalize: 'off',
                                        placeholder: 'Verification Code'
                                    },
                                    // showCancelButton: true,
                                    showDenyButton: true,
                                    denyButtonText: 'Resend',
                                    confirmButtonText: 'Verify',
                                    reverseButtons: true,
                                    showLoaderOnConfirm: true,
                                    preConfirm: (login) => {
                                        return $.ajax({
                                            type: 'POST',
                                            url: "{{ route('payment.otpVerify') }}",
                                            data: {
                                                "_token": "{{ csrf_token() }}",
                                                "verify_code": login,
                                                "transaction_id": response
                                                    .transaction_id
                                            },
                                            success: function(response) {
                                                $('#checkout-form')
                                                    .submit();
                                            },
                                            error: function(response) {
                                                Swal.fire({
                                                    title: "Error",
                                                    text: response
                                                        .responseJSON
                                                        .message,
                                                    icon: "error",
                                                    confirmButtonColor: "#1c84ee",
                                                });
                                            }
                                        });
                                    },
                                    preDeny: () => {
                                        Swal.fire('Changes are not saved', '',
                                            'info')
                                    },
                                    allowOutsideClick: () => !Swal.isLoading()
                                })
                            },
                            error: function(response) {
                                console.log(response);
                                Swal.fire({
                                    title: "Error",
                                    text: response
                                        .responseJSON
                                        .message,
                                    icon: "error",
                                    confirmButtonColor: "#1c84ee",
                                });
                            }
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                })
            } else {
                AIZ.plugins.notify('danger',
                    '{{ translate('Please Add card to Your profile !') }}');
            }
        }
    </script>
    <script type="text/javascript">
        localStorage.setItem('pay_method', 'paymo');

        $(document).ready(function() {
            $(".online_payment").click(function() {
                $('#manual_payment_description').parent().addClass('d-none');
            });
            toggleManualPaymentData($('input[name=payment_option]:checked').data('id'));
        });

        var minimum_order_amount_check = {{ get_setting('minimum_order_amount_check') == 1 ? 1 : 0 }};
        var minimum_order_amount =
            {{ get_setting('minimum_order_amount_check') == 1 ? get_setting('minimum_order_amount') : 0 }};

        function use_wallet() {
            $('input[name=payment_option]').val('wallet');
            if ($('#agree_checkbox').is(":checked")) {
                ;
                if (minimum_order_amount_check && $('#sub_total').val() < minimum_order_amount) {
                    AIZ.plugins.notify('danger',
                        '{{ translate('You order amount is less then the minimum order amount') }}');
                } else {
                    $('#checkout-form').submit();
                }
            } else {
                AIZ.plugins.notify('danger', '{{ translate('You need to agree with our policies') }}');
            }
        }

        function submitOrder(el) {
            if ($('#agree_checkbox').is(":checked")) {

                if (minimum_order_amount_check && $('#sub_total').val() < minimum_order_amount) {
                    AIZ.plugins.notify('danger',
                        '{{ translate('You order amount is less then the minimum order amount') }}');
                } else {
                    if (localStorage.getItem('pay_method')) {

                        if (localStorage.getItem('pay_method') == 'paymo') {
                            $('#new-payment').modal('show');
                            if ({{ $bindCards->count() }}) {

                            } else {
                                AIZ.plugins.notify('danger',
                                    '{{ translate('Please Add card to Your profile !') }}');
                            }
                        } else {
                            var offline_payment_active = '{{ addon_is_activated('offline_payment') }}';
                            if (offline_payment_active == 'true' && $('.offline_payment_option').is(":checked") && $(
                                    '#trx_id')
                                .val() == '') {
                                AIZ.plugins.notify('danger',
                                    '{{ translate('You need to put Transaction id') }}');
                                $(el).prop('disabled', false);
                            } else {
                                $(el).prop('disabled', true);
                                $('#checkout-form').submit();
                            }
                        }

                    } else {
                        AIZ.plugins.notify('danger',
                            '{{ translate('Please check payment method !') }}');
                    }

                }

            } else {
                AIZ.plugins.notify('danger', '{{ translate('You need to agree with our policies') }}');
                $(el).prop('disabled', false);
            }
        }

        function toggleManualPaymentData(id) {
            if (typeof id != 'undefined') {
                $('#manual_payment_description').parent().removeClass('d-none');
                $('#manual_payment_description').html($('#manual_payment_info_' + id).html());
            }
        }

        $(document).on("click", "#coupon-apply", function() {
            var data = new FormData($('#apply-coupon-form')[0]);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: "{{ route('checkout.apply_coupon_code') }}",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data, textStatus, jqXHR) {
                    AIZ.plugins.notify(data.response_message.response, data.response_message.message);
                    $("#cart_summary").html(data.html);
                    let html = document.getElementById("summary").innerHTML;
                    document.getElementById("summary_modal").innerHTML = html;
                }
            })
        });

        $(document).on("click", "#coupon-remove", function() {
            var data = new FormData($('#remove-coupon-form')[0]);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: "{{ route('checkout.remove_coupon_code') }}",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data, textStatus, jqXHR) {
                    $("#cart_summary").html(data);
                    let html = document.getElementById("summary").innerHTML;
                    document.getElementById("summary_modal").innerHTML = html;
                }
            })
        })
    </script>
    <script>
        function pay_method(method) {
            localStorage.setItem('pay_method', method);
        }
    </script>
    <script>
        $('select').each(function() {
            var $this = $(this),
                numberOfOptions = $(this).children('option').length;

            $this.addClass('select-hidden');
            $this.wrap('<div class="select"></div>');
            $this.after('<div class="select-styled"></div>');

            var $styledSelect = $this.next('div.select-styled');
            $styledSelect.text($this.children('option').eq(0).text());

            var $list = $('<ul />', {
                'class': 'select-options'
            }).insertAfter($styledSelect);

            for (var i = 0; i < numberOfOptions; i++) {
                $('<li />', {
                    text: $this.children('option').eq(i).text(),
                    rel: $this.children('option').eq(i).val()
                }).appendTo($list);
                //if ($this.children('option').eq(i).is(':selected')){
                //  $('li[rel="' + $this.children('option').eq(i).val() + '"]').addClass('is-selected')
                //}
            }

            var $listItems = $list.children('li');

            $styledSelect.click(function(e) {
                e.stopPropagation();
                $('div.select-styled.active').not(this).each(function() {
                    $(this).removeClass('active').next('ul.select-options').hide();
                });
                $(this).toggleClass('active').next('ul.select-options').toggle();
            });

            $listItems.click(function(e) {
                e.stopPropagation();
                $styledSelect.text($(this).text()).removeClass('active');
                $this.val($(this).attr('rel'));
                $list.hide();

                let id = $this.val();
                let card_info = @json($card_info);
                document.getElementById("cardHolder").innerHTML = card_info[id]['card_holder'];
                document.getElementById("ExpDate").innerHTML = card_info[id]['expiry'];
                $('#bindID').val(id);
            });

            $(document).click(function() {
                $styledSelect.removeClass('active');
                $list.hide();
            });

        });
    </script>
@endsection
