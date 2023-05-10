@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="aiz-titlebar mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="fs-20 fw-700 text-dark">{{ translate('My Cards') }}</h1>
            </div>
        </div>
    </div>

    <!-- Basic Info-->
    <div class="card rounded-0 shadow-none border">
        <div class="card-header pt-4 border-bottom-0">
            <h5 class="mb-0 fs-18 fw-700 text-dark">{{ translate('Card Info') }}</h5>
        </div>
        <div class="card-body">
            <form>
                <!-- Name-->

                @if (!$status)
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
                            aria-label="Warning:">
                            <use xlink:href="#exclamation-triangle-fill" />
                        </svg>
                        <div>
                            An example warning alert with an icon
                        </div>
                    </div>
                @endif

                <div>

                    <div class="credit-card">
                        <div class="kb-card">
                            <div class="kb-card__header kb-color--primary">
                                <h3 class="kb-card__title kb-color-text--white">
                                    Payment Details
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="" class="kb-support-text">Card Number</label>
                                        <input type="text" class="kb-input card-number" id="cardNum" name="cardNum"
                                            value="" style="word-spacing: .875rem;" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="" class="kb-support-text">Expiration Date</label>
                                        <input type="text" class="kb-input expiration-date" id="ex_date" name="ex_date"
                                            value="" style="word-spacing: .875rem;" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button-->
                <div class="form-group mb-0 text-right">
                    <button type="button" onclick="add_card()"
                        class="btn btn-primary add_click rounded-1 w-150px mt-3">{{ translate('Add Card') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Address -->
    <div class="card rounded-0 shadow-none border">
        <div class="card-header pt-4 border-bottom-0">
            <h5 class="mb-0 fs-18 fw-700 text-dark">{{ translate('My Cards') }}</h5>
        </div>
        <div class="card-body">

            <div class="row justify-content-center">

                @foreach ($cards as $card)
                    <div class="col-xs-12 col-sm-6 mb-2">
                        <div class="credit-card-s">
                            <img src="{{ asset('public/assets/img/chip.png') }}" class="logo">
                            <div class="numbers">{{ $card->pan }}</div>
                            <div class="name-and-expiry">
                                <span>{{ $card->card_holder }}</span>
                                <span>{{ $card->expiry }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

        </div>
    </div>

    <style lang="scss">
        .kb-card {
            position: relative;
            margin: 0;
            width: 100%;
        }

        .kb-card__header {
            border-top-left-radius: 1.875rem;
            border-top-right-radius: 1.875rem;
            letter-spacing: 1px;
            padding: 20px;
        }

        .kb-card__header {
            *zoom: 1;
        }

        .kb-card__header:before,
        .kb-card__header:after {
            content: "";
            display: table;
        }

        .kb-card__header:after {
            clear: both;
        }

        .kb-card__body {
            background-color: #fff;
            border-bottom-left-radius: 1.875rem;
            border-bottom-right-radius: 1.875rem;
            letter-spacing: 0.5px;
            padding: 2.75rem 2.75rem 2rem;
        }

        .kb-card__title {
            font-size: 1.675rem;
            text-transform: uppercase;
            /* width: 50%; */
        }

        .kb-support-text {
            color: rgba(0, 0, 0, 0.35);
            font-weight: 700;
            display: block;
            font-size: 0.9rem;
            padding-bottom: 0.875rem;
            text-transform: uppercase;
        }

        .kb-input,
        .kb-select {
            border: 3px solid rgba(0, 0, 0, 0.2);
            border-radius: 0.375rem;
            font-size: 1.5rem;
            padding: 0.875rem;
            width: 97.5%;
            transition: all 0.3s;
        }

        .kb-input:focus,
        .kb-select:focus {
            border-color: #0081FF;
        }

        .kb-row {
            margin-bottom: 3rem;
        }

        .kb-row {
            *zoom: 1;
        }

        .kb-row:before,
        .kb-row:after {
            content: "";
            display: table;
        }

        .kb-row:after {
            clear: both;
        }

        .kb-col {
            float: left;
        }

        .kb-col--5 {
            width: 5%;
        }

        .kb-col--7-5 {
            width: 7.5%;
        }

        .kb-col--10 {
            width: 10%;
        }

        .kb-col--30 {
            width: 30%;
        }

        .kb-col--50 {
            width: 50%;
        }

        .kb-col--45 {
            width: 45%;
        }

        .kb-col--33 {
            width: 33.3333%;
        }

        .kb-text-left {
            text-align: left;
        }

        .kb-text-center {
            text-align: center;
        }

        .kb-text-right {
            text-align: right;
        }

        .kb-right {
            float: right;
        }

        .kb-color--primary {
            background-color: #e62e04;
        }

        .kb-color--secondary {
            background-color: #F6BB33;
        }

        .kb-color-text--primary {
            color: #0081FF;
        }

        .kb-color-text--secondary {
            color: #F6BB33;
        }

        .kb-color-text--white {
            color: #fff;
        }
    </style>

    <style>
        .credit-card-s {
            position: relative;
            min-height: 270px;
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            padding: 24px;
            box-sizing: border-box;
            background: linear-gradient(-240deg, #fffc00, #fc00ff, #00fffc);
            justify-content: space-between;
            font-family: "Dosis", sans-serif;
            overflow: hidden;
        }

        .credit-card-s:after {
            content: "";
            position: absolute;
            height: 100%;
            width: 100%;
            left: 0;
            top: 0;
            z-index: 0;
            color: rgb(249 249 249 / 10%);
            background: linear-gradient(135deg, currentColor 25%, transparent 25%) -100px 0,
                linear-gradient(225deg, currentColor 25%, transparent 25%) -100px 0,
                linear-gradient(315deg, currentColor 25%, transparent 25%),
                linear-gradient(45deg, currentColor 25%, transparent 25%);
            background-size: calc(2 * 100px) calc(2 * 100px);
        }

        .logo {
            width: 50px;
            display: flex;
            align-self: flex-end;
            filter: drop-shadow(1px 1px 0 #555);
            z-index: 1;
        }

        .name-and-expiry {
            display: flex;
            justify-content: space-between;
            z-index: 1;
            color: #fff;
            font-size: 18px;
            letter-spacing: 3px;
            filter: drop-shadow(1px 0px 1px #555);
            text-transform: uppercase;
        }

        .numbers {
            font-size: 24px;
            letter-spacing: 9px;
            text-align: center;
            color: #fff;
            filter: drop-shadow(1px 0px 1px #555);
            z-index: 1;
        }
    </style>
@endsection

@section('modal')
    <!-- Address modal -->
    @include('frontend.partials.address_modal')
@endsection

@push('scripts')
    {{-- <script>
        $(document).ready(function() {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $(".add_click").click(function() {
                $.ajax({
                    /* the route pointing to the post function */
                    url: '{{ route('add_card') }}',
                    type: 'POST',
                    /* send the csrf-token and the input to the controller */
                    data: {
                        _token: CSRF_TOKEN,
                        cardNum:  $('#cardNum').val(),
                        ex_date:  $('#ex_date').val(),
                        message: $(".getinfo").val()
                    },
                    dataType: 'JSON',
                    /* remind that 'data' is the response of the AjaxController */
                    success: function(data) {
                        $(".writeinfo").append(data.msg);
                    }
                });
            });
        });
    </script> --}}
    <script>
        function add_card() {
            Swal.fire({
                title: 'Do you want to save the changes?',
                showCancelButton: true,
                confirmButtonText: 'Add and Save',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.ajax({
                        type: 'POST',
                        url: "{{ route('add_card') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "cardNum": $('#cardNum').val(),
                            "exDate": $('#ex_date').val()
                        },
                        success: function(response) {
                            if (response.status) {
                                Swal.fire(
                                    'Good job!',
                                    response.message,
                                    'success'
                                ).then(function() {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Your verification code has been sent to phone number +' +
                                        response.phone,
                                    input: 'text',
                                    inputAttributes: {
                                        autocapitalize: 'off',
                                        placeholder: 'Verification Code'
                                    },
                                    showCancelButton: true,
                                    confirmButtonText: 'Verify',
                                    showLoaderOnConfirm: true,
                                    preConfirm: (login) => {
                                        return $.ajax({
                                            type: 'POST',
                                            url: "{{ route('add_card_success') }}",
                                            data: {
                                                "_token": "{{ csrf_token() }}",
                                                "cardNum": $('#cardNum').val(),
                                                "exDate": $('#ex_date').val(),
                                                "verify_code": login,
                                                "transaction_id": response
                                                    .transaction_id
                                            },
                                            success: function(response) {
                                                Swal.fire(
                                                    'Good job!',
                                                    response.message,
                                                    'success'
                                                ).then(function() {
                                                    location
                                                    .reload();
                                                });
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
                                    allowOutsideClick: () => !Swal.isLoading()
                                })
                            }
                        },
                        error: function(response) {
                            Swal.fire({
                                title: "Error",
                                text: response.responseJSON.message,
                                icon: "error",
                                confirmButtonColor: "#1c84ee",
                            });
                        }
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {

            });

            // let timerInterval
            // Swal.fire({
            //     title: 'Wait please!',
            //     html: 'More <b></b> milliseconds.',
            //     timer: 2000,
            //     timerProgressBar: true,
            //     showLoaderOnConfirm: true,
            //     didOpen: () => {
            //         Swal.showLoading()
            //         const b = Swal.getHtmlContainer().querySelector('b')
            //         timerInterval = setInterval(() => {
            //             b.textContent = Swal.getTimerLeft()
            //         }, 100)
            //     },
            //     // willClose: () => {
            //     //     clearInterval(timerInterval)
            //     // }
            // }).then((result) => {
            //     if (result.dismiss === Swal.DismissReason.timer) {
            //         $.ajax({
            //             type: 'POST',
            //             url: "{{ route('add_card') }}",
            //             data: {
            //                 "_token": "{{ csrf_token() }}",
            //                 "cardNum": $('#cardNum').val(),
            //                 "exDate": $('#ex_date').val()
            //             },
            //             success: function(response) {
            //                 Swal.fire({
            //                     title: 'Please, Enter verification code',
            //                     input: 'text',
            //                     inputAttributes: {
            //                         autocapitalize: 'off'
            //                     },
            //                     showCancelButton: true,
            //                     confirmButtonText: 'Look up',
            //                     showLoaderOnConfirm: true,
            //                     preConfirm: (login) => {
            //                         console.log(login);
            //                     },
            //                     allowOutsideClick: () => !Swal.isLoading()
            //                 }).then((result) => {

            //                     if (result.isConfirmed) {
            //                         Swal.fire({
            //                             title: `${result.value.login}'s avatar`,
            //                             imageUrl: result.value.avatar_url
            //                         })
            //                     }

            //                 })
            //             },
            //             error: function(response) {
            //                 Swal.fire({
            //                     title: "Error",
            //                     text: response.responseJSON.message,
            //                     icon: "error",
            //                     confirmButtonColor: "#1c84ee",
            //                 });
            //             }
            //         });
            //     }
            // });
        }
    </script>
    <script>
        $('form').card({

            // number formatting
            formatting: true,

            // selectors
            formSelectors: {
                numberInput: 'input[name="number"]',
                expiryInput: 'input[name="expiry"]',
                cvcInput: 'input[name="cvc"]',
                nameInput: 'input[name="name"]'
            },
            cardSelectors: {
                cardContainer: '.jp-card-container',
                card: '.jp-card',
                numberDisplay: '.jp-card-number',
                expiryDisplay: '.jp-card-expiry',
                cvcDisplay: '.jp-card-cvc',
                nameDisplay: '.jp-card-name'
            },

            // custom messages
            messages: {
                validDate: 'valid\nthru',
                monthYear: 'month/year'
            },

            // custom placeholders
            placeholders: {
                number: '&bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull;',
                cvc: '&bull;&bull;&bull;',
                expiry: '&bull;&bull;/&bull;&bull;',
                name: 'Full Name'
            },

            // enable input masking 
            masks: {
                cardNumber: false
            },

            // valid/invalid CSS classes
            classes: {
                valid: 'jp-card-valid',
                invalid: 'jp-card-invalid'
            },

            // debug mode
            debug: false

        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.card-number').inputmask('9999 9999 9999 9999');
            $('.expiration-date').inputmask('99/99');
        });
        // $('#cardNumber').mask('00.00');
    </script>
@endpush

@section('script')
    <script type="text/javascript">
        $('.new-email-verification').on('click', function() {
            $(this).find('.loading').removeClass('d-none');
            $(this).find('.default').addClass('d-none');
            var email = $("input[name=email]").val();

            $.post('{{ route('user.new.verify') }}', {
                _token: '{{ csrf_token() }}',
                email: email
            }, function(data) {
                data = JSON.parse(data);
                $('.default').removeClass('d-none');
                $('.loading').addClass('d-none');
                if (data.status == 2)
                    AIZ.plugins.notify('warning', data.message);
                else if (data.status == 1)
                    AIZ.plugins.notify('success', data.message);
                else
                    AIZ.plugins.notify('danger', data.message);
            });
        });
    </script>

    @if (get_setting('google_map') == 1)
        @include('frontend.partials.google_map')
    @endif
@endsection
