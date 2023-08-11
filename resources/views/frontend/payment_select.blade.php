@extends('frontend.layouts.app')

@section('content')

    <!-- Steps -->
    <section class="pt-5 mb-4">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="row gutters-5 sm-gutters-10">
                        <div class="col done">
                            <a href="{{ route('cart') }}">
                                <div class="text-center border border-bottom-6px p-2 text-success">
                                    <i class="la-3x mb-2 las la-shopping-cart"></i>
                                    <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('1. My Cart') }}</h3>
                                </div>
                            </a>
                        </div>
                        <div class="col done">
                            <a href="{{ route('checkout.shipping_info') }}">
                                <div class="text-center border border-bottom-6px p-2 text-success">
                                    <i class="la-3x mb-2 las la-map"></i>
                                    <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('2. Shipping info') }}
                                    </h3>
                                </div>
                            </a>
                        </div>
                        <div class="col done">
                            <a href="{{ route('checkout.store_shipping_infostore_GET') }}">
                                <div class="text-center border border-bottom-6px p-2 text-success">
                                    <i class="la-3x mb-2 las la-truck"></i>
                                    <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('3. Delivery info') }}
                                    </h3>
                                </div>
                            </a>
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

                            <div class="pt-3 px-4 fs-14" id="payCard">
                                <a href="{{ route('my-cards') }}" class="btn btn-link fs-14 fw-700 px-0">
                                    <i class="las la-plus fs-16"></i>
                                    {{ translate('Add new card') }}
                                </a>
                                <select class="form-control aiz-selectpicker rounded-0" data-live-search="true"
                                    id="bindIDselect">
                                    <option value="">
                                        {{ translate('Select your card for payment ') }}
                                    </option>
                                    @foreach ($bindCards as $card)
                                        <option value="{{ $card->id }}"
                                            data-content="<span class='d-block'>
                                                        <span class='d-block fs-16 fw-600 mb-2'> {{ $card->pan }}</span>
                                                        <span class='d-block opacity-50 fs-12'><i class='las la-user'></i> {{ $card->card_holder }}</span>
                                                        <span class='d-block opacity-50 fs-12'><i class='las la-hourglass'></i> {{ $card->expiry }}</span>
                                                    </span>">
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Agree Box -->
                            <div class="pt-3 px-4 fs-14 mt-4">
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

                <div class="modal fade" id="confirmPayment" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ translate('Select Card') }}</h5>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="">{{ translate('Verification Code:') }}</label>
                                    <input type="text" name="" id="verificationCode" class="form-control"
                                        placeholder="{{ translate('Verification Code ..') }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" data-dismiss="modal" class="btn btn-secondary"><i
                                        class="las la-reply-all"></i> {{ translate('Cancel') }}</button>
                                <button type="button" class="btn btn-success" id="resendButton"
                                    onclick="resendSend()"><i class="las la-redo-alt"></i>
                                    {{ translate('Resend') }}</button>
                                <button type="button" onclick="onSubmitForm()" class="btn btn-primary"><i
                                        class="las la-check-circle"></i> {{ translate('Complete Order') }} </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="col-lg-4 mt-lg-0 mt-4" id="cart_summary">
                    @include('frontend.partials.cart_summary')
                </div>

            </div>
        </div>

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

    </section>

@endsection

@section('script')
    <script>
        let html = document.getElementById("summary").innerHTML;
        document.getElementById("summary_modal").innerHTML = html;
    </script>
    <script>
        function paymentSubmit() {
            if ({{ $bindCards->count() }}) {
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
                                "bindID": $('#bindIDselect').val()
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
                                        $.ajax({
                                            type: 'POST',
                                            url: "{{ route('payment.resend') }}",
                                            data: {
                                                "_token": "{{ csrf_token() }}",
                                                "transaction_id": response
                                                    .transaction_id
                                            },
                                            success: function(response) {

                                            }
                                        });
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

        function timerResend() {
            $('#resendButton').prop('disabled', true);
            let timerOn = true;

            function timer(remaining) {
                var m = Math.floor(remaining / 60);
                var s = remaining % 60;

                m = m < 10 ? '0' + m : m;
                s = s < 10 ? '0' + s : s;
                document.getElementById('resendButton').innerHTML = '<i class="las la-redo-alt"></i> ' + m + ':' + s;
                remaining -= 1;

                if (remaining >= 0 && timerOn) {
                    setTimeout(function() {
                        timer(remaining);
                    }, 1000);
                    return;
                }

                if (!timerOn) {
                    return;
                }

                $('#resendButton').prop('disabled', false);

                document.getElementById('resendButton').innerHTML = '<i class="las la-redo-alt"></i> ' +
                    '{{ translate('Resend') }}';
            }

            timer(20);
        }

        function onSubmitForm() {
            $.ajax({
                type: 'POST',
                url: "{{ route('payment.otpVerify') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "verify_code": $('#verificationCode').val(),
                    "transaction_id": JSON.parse(localStorage.getItem('trans'))
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
        }

        function resendSend() {
            $.ajax({
                type: 'POST',
                url: "{{ route('payment.resend') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "transaction_id": JSON.parse(localStorage.getItem('trans'))
                },
                success: function(response) {
                    timerResend();
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
        }

        function submitOrder(el) {
            if ($('#agree_checkbox').is(":checked")) {

                if (minimum_order_amount_check && $('#sub_total').val() < minimum_order_amount) {
                    AIZ.plugins.notify('danger',
                        '{{ translate('You order amount is less then the minimum order amount') }}');
                } else {
                    if (localStorage.getItem('pay_method')) {

                        if (localStorage.getItem('pay_method') == 'paymo') {
                            let bindID = $('#bindIDselect').val();
                            if ({{ $bindCards->count() }}) {
                                if (bindID) {
                                    $('#loader').modal('show');
                                    $.ajax({
                                        type: 'POST',
                                        url: "{{ route('payment.paySuccess') }}",
                                        data: {
                                            "_token": "{{ csrf_token() }}",
                                            "bindID": $('#bindIDselect').val()
                                        },
                                        success: function(response) {
                                            console.log(response.transaction_id);
                                            localStorage.setItem('trans', JSON.stringify(response
                                                .transaction_id));
                                            $('#loader').modal('hide');
                                            $('#confirmPayment').modal('show');
                                            timerResend();
                                        },
                                        error: function(response) {
                                            $('#loader').modal('hide');
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

                                } else {
                                    AIZ.plugins.notify('danger',
                                        '{{ translate('Please select card for payment !') }}');
                                }
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
            if (method == 'cash_on_delivery') {
                $('#payCard').addClass('d-none');
            } else if (method == 'paymo') {
                $('#payCard').removeClass('d-none');
            }
            localStorage.setItem('pay_method', method);
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#verificationCode').inputmask('999999');
        });
    </script>
@endsection
