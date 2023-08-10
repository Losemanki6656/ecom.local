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
                    <div class="cc">
                        <div class="cc__front">
                            <div class="cc__brand">
                                <span class="cc__brand-text">{{ $card->card_holder }}</span>
                                <div class="cc__balance-text">{{ translate('Card holder') }}</div>
                            </div>
                            <div class="cc__number">
                                <div class="cc__digits">{{ $card->pan }} </div>
                                <div class="cc__balance-text">{{ translate('Card number') }}</div>
                            </div>
                            <div class="cc__brand-text">{{ $card->expiry }}</div>
                            <div class="cc__balance-text">{{ translate('Expiry date') }}</div>
                        </div>
                        <div class="cc__settings">
                            <div class="cc__settings-menu">
                                <div class="cc__settings-menu-item"
                                    onclick="delete_card({{ $card->id }}, '{{ $card->card_holder }}')">
                                    <div class="cc__settings-menu-item-icon">
                                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            width="20" height="20">
                                            <path
                                                d="M12 1C8.676 1 6 3.676 6 7v1c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2V7c0-3.324-2.676-6-6-6zm0 2c2.276 0 4 1.724 4 4v1H8V7c0-2.276 1.724-4 4-4zm0 10c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z" />
                                        </svg>
                                    </div> {{ translate('Delete Card') }}
                                </div>
                            </div>
                            <div class="cc__settings-bar">
                                <svg class="gear-icon icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    width="24" height="24">
                                    <path
                                        d="M9.668 2l-.492 2.523c-.821.31-1.58.744-2.246 1.291L4.506 4.98 2.174 9.02l1.94 1.686A8.012 8.012 0 004 12c0 .441.045.871.113 1.293l-1.94 1.686 2.333 4.042 2.424-.835c.666.547 1.425.98 2.246 1.29L9.668 22h4.664l.492-2.523c.821-.31 1.58-.744 2.246-1.291l2.424.835 2.332-4.042-1.94-1.686c.07-.422.114-.852.114-1.293 0-.441-.045-.871-.113-1.293l1.94-1.686-2.333-4.042-2.424.835a7.983 7.983 0 00-2.246-1.29L14.332 2H9.668zM12 8a4 4 0 110 8 4 4 0 010-8z" />
                                </svg>
                                <h6 class="apple-pay-icon icon">Paymo </h6>
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
        .cc {
            height: 220px;
            width: 386px;
            position: relative;
            background: #6c7cff;
            border-radius: 30px;
            color: #fff;
        }

        .cc__front {
            display: flex;
            flex-direction: column;
            align-items: left;
            padding: 24px 38px 38px;
        }

        .cc__brand {
            /* display: flex; */
            align-items: center;
            margin-bottom: 20px;
        }

        .cc__brand-logo {
            display: flex;
        }

        .cc__logo-circle {
            width: 30px;
            height: 30px;
            background: #fff;
            display: block;
            border-radius: 50%;
            position: relative;
        }

        .cc__logo-circle--left {
            opacity: 0.5;
        }

        .cc__logo-circle--right {
            left: -10px;
            opacity: 0.3;
        }

        .cc__brand-text {
            font-size: 17px;
            /* margin-left: 5px; */
        }

        .cc__number {
            /* display: flex; */
            align-items: center;
            margin-bottom: 15px;
        }

        .cc__number-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #fff;
            display: inline-block;
            margin-right: 4px;
        }

        .cc__digits {
            font-size: 20px;
            /* margin-left: 10px; */
        }

        .cc__balance-text {
            /* font-weight: 600; */
            font-size: 10px;
        }

        .cc__settings {
            width: 100%;
            height: 100%;
            background: #4c5be8;
            border-radius: 30px;
            position: absolute;
            top: 0;
            display: flex;
            justify-content: space-between;
            padding: 22px 10px;
            clip-path: path("M387.118151,-12.6493894 L387.118151,228.834835 L320.261415,228.834835 C320.261415,219.284137 320.261415,209.299312 320.261415,198.880359 C320.261415,186.645109 320.261415,149.508543 320.261415,110 C320.261415,70.4914571 320.261415,43.8454079 320.261415,21.9752985 C320.261415,3.85122438 320.261415,-7.69033824 320.261415,-12.6493894 L387.118151,-12.6493894 Z");
        }

        .cc__settings--active {
            animation: showSettings 2s ease-in-out forwards;
        }

        .cc__settings--hidden {
            clip-path: path("M387.118151,0.160365095 L387.118151,221.215264 L316.529346,221.215264 C117.007895,237.0198 17.2471697,237.0198 17.2471697,221.215264 C17.2471697,181.988409 17.2471697,133.22115 17.2471697,110 C17.2471697,86.7788496 17.2471697,27.7576259 17.2471697,-6.39488462e-14 C17.2471697,-16.3318577 117.007895,-16.2784027 316.529346,0.160365095 L387.118151,0.160365095 Z");
            animation: hideSettings 2s ease-in-out forwards;
        }

        .cc__settings-menu {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding-left: 32px;
        }

        .cc__settings-menu-item {
            display: flex;
            align-items: center;
        }

        .cc__settings-menu-item-icon {
            display: flex;
            padding: 4px 6px;
            background: #5f6ff6;
            border-radius: 7px;
            margin-right: 15px;
        }

        .cc__settings-bar {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
        }

        .icon {
            fill: #fff;
        }

        .apple-pay-icon {
            transform: rotate(-90deg);
        }

        .gear-icon {
            cursor: pointer;
            opacity: 0.5;
            transition: opacity 1.5s;
        }

        .gear-icon.active {
            opacity: 1;
        }

        @keyframes showSettings {
            25% {
                clip-path: path("M387.118151,-12.6493894 L387.118151,228.834835 L326.156587,228.834835 C316.225565,222.276395 309.894513,217.352564 307.16343,214.063341 C298.576801,203.721899 122.1888,208.462135 122.1888,108.204535 C122.1888,7.94693437 294.206316,25.6769042 315.855488,6.20473783 C320.120314,2.36877547 323.554014,-3.91593359 326.156587,-12.6493894 L387.118151,-12.6493894 Z");
            }

            50% {
                clip-path: path("M387.118151,-12.6493894 L387.118151,228.834835 L48.4642624,238.145348 C18.8858367,241.43457 2.73108257,241.43457 6.66133815e-15,238.145348 C-8.58662882,227.803906 18.4317885,204.651574 18.4317885,104.393974 C18.4317885,4.13637332 -13.6376238,0.776583538 8.01154821,-18.6955828 C12.2763746,-22.5315451 25.7606127,-20.5161473 48.4642624,-12.6493894 L387.118151,-12.6493894 Z");
            }

            65%,
            100% {
                clip-path: path("M387.118151,0.160365095 L387.118151,221.215264 L316.529346,221.215264 C117.007895,237.0198 17.2471697,237.0198 17.2471697,221.215264 C17.2471697,181.988409 17.2471697,133.22115 17.2471697,110 C17.2471697,86.7788496 17.2471697,27.7576259 17.2471697,-6.39488462e-14 C17.2471697,-16.3318577 117.007895,-16.2784027 316.529346,0.160365095 L387.118151,0.160365095 Z");
            }
        }

        @keyframes hideSettings {
            25% {
                clip-path: path("M387.118151,0.160365095 L387.118151,221.215264 L316.529346,221.215264 C100.704923,235.24635 -4.80485907,234.841262 1.15518706e-13,220 C12.4189217,181.640401 18.8873389,174.136909 17.2471697,110 C15.6070006,45.8630911 13.7675117,26.4098973 1.04860565e-13,0.160365095 C-6.27713368,-11.8077978 99.2326483,-11.8077978 316.529346,0.160365095 L387.118151,0.160365095 Z");
            }

            55% {
                clip-path: path("M387.118151,-12.6493894 L387.118151,228.834835 L243.77087,228.834835 C216.843505,221.535077 202.014282,216.240588 199.283199,212.951365 C190.69657,202.609923 14.0082036,210.2576 14.0082036,110 C14.0082036,9.74239964 184.417555,28.4484097 208.443623,13.7028253 C213.730293,10.4582224 224.482747,1.6741509 240.700984,-12.6493894 L387.118151,-12.6493894 Z");
            }

            80% {
                clip-path: path("M387.118151,-12.6493894 L387.118151,228.834835 L315.554768,228.834835 C317.523992,218.414953 319.092875,208.430128 320.261415,198.880359 C321.744382,186.760968 330.890906,150.167879 333.898886,110 C336.906867,69.8321206 320.261415,43.8454079 320.261415,21.9752985 C320.261415,3.85122438 318.692532,-7.69033824 315.554768,-12.6493894 L387.118151,-12.6493894 Z");
            }

            100% {
                clip-path: path("M387.118151,-12.6493894 L387.118151,228.834835 L320.261415,228.834835 C320.261415,219.284137 320.261415,209.299312 320.261415,198.880359 C320.261415,186.645109 320.261415,149.508543 320.261415,110 C320.261415,70.4914571 320.261415,43.8454079 320.261415,21.9752985 C320.261415,3.85122438 320.261415,-7.69033824 320.261415,-12.6493894 L387.118151,-12.6493894 Z");
            }
        }

        /* Base Styles */
        *,
        *:before,
        *:after {
            box-sizing: border-box;
        }

        .dribbble-link {
            position: absolute;
            right: 30px;
            bottom: 30px;
        }
    </style>
@endsection

@section('modal')
    <!-- Address modal -->
    @include('frontend.partials.address_modal')
@endsection

@push('scripts')
    <script>
        const gear = document.querySelector('.gear-icon');
        const ccSettings = document.querySelector('.cc__settings');

        gear.addEventListener('click', () => {
            if (gear.classList.contains('active')) {
                ccSettings.classList.remove('cc__settings--active');
                ccSettings.classList.add('cc__settings--hidden');
            } else {
                ccSettings.classList.remove('cc__settings--hidden');
                ccSettings.classList.add('cc__settings--active');
            }
            gear.classList.toggle('active');
        });
    </script>
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

        }

        function delete_card(id, name) {
            Swal.fire({
                title: 'Do you want to delete this card?',
                text: "CARD HOLDER: " + name,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('delete_card') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "card_id": id
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            );
                            location.reload();
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
            })
        }
    </script>
    {{-- <script>
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
    </script> --}}
    <script>
        $(document).ready(function() {
            $('.card-number').inputmask('9999 9999 9999 9999');
            $('.expiration-date').inputmask('99/99');
        });
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
