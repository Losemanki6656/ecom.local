@extends('seller.layouts.app')

@section('panel_content')

    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Products') }}</h1>
            </div>
        </div>
    </div>

    <div class="row gutters-10 justify-content-center">
        @if (addon_is_activated('seller_subscription'))
            <div class="col-md-4 mx-auto mb-3">
                <div class="bg-grad-1 text-white rounded-lg overflow-hidden">
                    <span
                        class="size-30px rounded-circle mx-auto bg-soft-primary d-flex align-items-center justify-content-center mt-3">
                        <i class="las la-upload la-2x text-white"></i>
                    </span>
                    <div class="px-3 pt-3 pb-3">
                        <div class="h4 fw-700 text-center">
                            {{ max(0,auth()->user()->shop->product_upload_limit -auth()->user()->products()->count()) }}
                        </div>
                        <div class="opacity-50 text-center">{{ translate('Remaining Uploads') }}</div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-md-4 mx-auto mb-3">
            <a href="{{ route('seller.products.create') }}">
                <div class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg has-transition">
                    <span
                        class="size-60px rounded-circle mx-auto bg-primary d-flex align-items-center justify-content-center mb-3">
                        <i class="las la-plus la-3x text-white"></i>
                    </span>
                    <div class="fs-18 text-primary">{{ translate('Add New Product') }}</div>
                </div>
            </a>
        </div>

        @if (auth()->user()->shop->billz_status)
            <div class="col-md-4 mx-auto mb-3">
                <a data-toggle="modal" data-target="#confirmBillz">
                    <div class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg has-transition">
                        <span
                            class="size-60px rounded-circle mx-auto bg-warning d-flex align-items-center justify-content-center mb-3">
                            <i class="las la-sync la-3x text-white"></i>
                        </span>
                        <div class="fs-18 text-primary">{{ translate('Upload Billz products') }}</div>
                    </div>
                </a>
            </div>

            <div class="modal fade" id="confirmBillz">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title h6">{{ translate('Confirmation') }}</h5>
                            <button type="button" class="close" data-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>{{ translate('Do you agree to download the goods from the Billiz system? It may take some time!') }}
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light"
                                data-dismiss="modal">{{ translate('Cancel') }}</button>
                            <a type="button" id="confirmation" onclick="downloadBillz()"
                                class="btn btn-warning">{{ translate('Confirm!') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (auth()->user()->shop->billz2_status)
            <div class="col-md-4 mx-auto mb-3">
                <a data-toggle="modal" data-target="#confirmBillz2">
                    <div class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg has-transition">
                        <span
                            class="size-60px rounded-circle mx-auto bg-success d-flex align-items-center justify-content-center mb-3">
                            <i class="las la-sync la-3x text-white"></i>
                        </span>
                        <div class="fs-18 text-primary">{{ translate('Upload Billz2 products') }}</div>
                    </div>
                </a>
            </div>

            <div class="modal fade" id="confirmBillz2">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title h6">{{ translate('Confirmation') }}</h5>
                            <button type="button" class="close" data-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>{{ translate('Do you agree to download the goods from the Billiz system? It may take some time!') }}
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light"
                                data-dismiss="modal">{{ translate('Cancel') }}</button>
                            <a type="button" id="confirmation" onclick="downloadBillz2()"
                                class="btn btn-success">{{ translate('Confirm!') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif


        <div id="loader" class="modal fade" data-backdrop="static">
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



        @if (addon_is_activated('seller_subscription'))
            @php
                $seller_package = \App\Models\SellerPackage::find(Auth::user()->shop->seller_package_id);
            @endphp
            <div class="col-md-4">
                <a href="{{ route('seller.seller_packages_list') }}"
                    class="text-center bg-white shadow-sm hov-shadow-lg text-center d-block p-3 rounded">
                    @if ($seller_package != null)
                        <img src="{{ uploaded_asset($seller_package->logo) }}" height="44" class="mw-100 mx-auto">
                        <span class="d-block sub-title mb-2">{{ translate('Current Package') }}:
                            {{ $seller_package->getTranslation('name') }}</span>
                    @else
                        <i class="la la-frown-o mb-2 la-3x"></i>
                        <div class="d-block sub-title mb-2">{{ translate('No Package Found') }}</div>
                    @endif
                    <div class="btn btn-outline-primary py-1">{{ translate('Upgrade Package') }}</div>
                </a>
            </div>
        @endif

    </div>

    <div class="card">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('All Products') }}</h5>
            </div>
            <div class="col-md-4">
                <form class="" id="sort_brands" action="" method="GET">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" id="search" name="search"
                            @isset($search) value="{{ $search }}" @endisset
                            placeholder="{{ translate('Search product') }}">
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th width="30%">{{ translate('Name') }}</th>
                        <th data-breakpoints="md">{{ translate('Category') }}</th>
                        <th data-breakpoints="md">{{ translate('Current Qty') }}</th>
                        <th>{{ translate('Base Price') }}</th>
                        @if (get_setting('product_approve_by_admin') == 1)
                            <th data-breakpoints="md">{{ translate('Approval') }}</th>
                        @endif
                        <th data-breakpoints="md">{{ translate('Published') }}</th>
                        <th data-breakpoints="md">{{ translate('Featured') }}</th>
                        <th data-breakpoints="md" class="text-right">{{ translate('Options') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($products as $key => $product)
                        <tr>
                            <td>{{ $key + 1 + ($products->currentPage() - 1) * $products->perPage() }}</td>
                            <td>
                                <a href="{{ route('product', $product->slug) }}" target="_blank" class="text-reset">
                                    {{ $product->getTranslation('name') }}
                                </a>
                            </td>
                            <td>
                                @if ($product->category != null)
                                    {{ $product->category->getTranslation('name') }}
                                @endif
                            </td>
                            <td>
                                @php
                                    $qty = 0;
                                    foreach ($product->stocks as $key => $stock) {
                                        $qty += $stock->qty;
                                    }
                                    echo $qty;
                                @endphp
                            </td>
                            <td>{{ $product->unit_price }}</td>
                            @if (get_setting('product_approve_by_admin') == 1)
                                <td>
                                    @if ($product->approved == 1)
                                        <span class="badge badge-inline badge-success">{{ translate('Approved') }}</span>
                                    @else
                                        <span class="badge badge-inline badge-info">{{ translate('Pending') }}</span>
                                    @endif
                                </td>
                            @endif
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input onchange="update_published(this)" value="{{ $product->id }}" type="checkbox"
                                        <?php if ($product->published == 1) {
                                            echo 'checked';
                                        } ?>>
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input onchange="update_featured(this)" value="{{ $product->id }}" type="checkbox"
                                        <?php if ($product->seller_featured == 1) {
                                            echo 'checked';
                                        } ?>>
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td class="text-right">
                                <a class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                    href="{{ route('seller.products.edit', ['id' => $product->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                    title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="{{ route('seller.products.duplicate', $product->id) }}"
                                    class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                    title="{{ translate('Duplicate') }}">
                                    <i class="las la-copy"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                    data-href="{{ route('seller.products.destroy', $product->id) }}"
                                    title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $products->links() }}
            </div>
        </div>
    </div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        function downloadBillz2() {
            $('#confirmBillz2').modal('hide');
            $('#loader').modal('show');

            $.ajax({
                url: "{{ route('seller.productsNewUpload') }}",
                method: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(res) {
                    $('#loader').modal('hide');
                    AIZ.plugins.notify('success', res.message);
                    location.reload();
                },
                error: function(error) {
                    $('#loader').modal('hide');
                    AIZ.plugins.notify('warning', error.responseJSON.message);
                },
                complete: function(data) {
                    $('#loader').modal('hide');
                }
            });

        }

        function downloadBillz() {
            $('#confirmBillz').modal('hide');
            $('#loader').modal('show');

            $.ajax({
                url: "{{ route('seller.productsUpload') }}",
                method: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(res) {
                    $('#loader').modal('hide');
                    AIZ.plugins.notify('success', res.message);
                    location.reload();
                },
                error: function(error) {
                    AIZ.plugins.notify('warning', error.responseJSON.message);
                    $('#loader').modal('hide');
                }
            });

        }



        function update_featured(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('seller.products.featured') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Featured products updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    location.reload();
                }
            });
        }

        function update_published(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('seller.products.published') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Published products updated successfully') }}');
                } else if (data == 2) {
                    AIZ.plugins.notify('danger', '{{ translate('Please upgrade your package.') }}');
                    location.reload();
                } else if (data == 3) {
                    AIZ.plugins.notify('danger', '{{ translate('Please, select category for product.') }}');

                    setInterval(function() {
                        location.reload();
                    }, 1000);

                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    location.reload();
                }
            });
        }
    </script>
@endsection
