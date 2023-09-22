@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('All Commissions') }}</h1>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header row gutters-5">
            <div class="col">
                <button class="btn btn-primary " type="button" data-toggle="modal" data-target="#addExample">
                    <i class="la la-plus-circle me-2"></i> {{ translate('Add Example') }}</button>

                <div class="modal fade" id="addExample">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{route('comission.store')}}" method="post">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title h6">{{ translate("Add Example") }}</h5>
                                    <button type="button" class="close" data-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="mb-0">{{ translate('Title') }}</label>
                                        <input class="form-control" name="title" placeholder="{{ translate("Title") }}"
                                               required>
                                    </div>
                                    <div class="mb-3">
                                        <button class="btn btn-success add_form_field w-100"><i
                                                class="la la-plus-circle me-2"></i> {{ translate('Add') }}</button>
                                    </div>
                                    <div class="add1">
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label class="mb-0">{{ translate('From price') }}</label>
                                                <input type="number" class="form-control" name="from_price[]" value="">
                                            </div>
                                            <div class="col">
                                                <label class="mb-0">{{ translate('To price') }}</label>
                                                <input type="number" class="form-control" name="to_price[]" value="">
                                            </div>
                                            <div class="col">
                                                <label class="mb-0">{{ translate('Percent') }}</label>
                                                <input type="number" class="form-control" name="percent[]" value="">
                                            </div>
                                            <div class="col">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">

                                    <button type="button" class="btn btn-light"
                                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                                    <button class="btn btn-primary" type="submit"><i
                                            class="la la-save me-2"></i> {{ translate('Save') }}</button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                <tr>
                    <th>{{ translate('Title') }}</th>
                    <th data-breakpoints="lg">{{ translate('Description') }}</th>
                    <th width="10%">{{ translate('Options') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($comissions as $item)
                    <tr>
                        <td>{{$item->title}}</td>
                        <td>{{$item->description}}</td>
                        <td>
                            <button type="button"
                                    class="btn btn-sm btn-danger" data-toggle="modal" data-target="#commission_delete{{$item->id}}">
                                <i class="las la-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <div class="modal fade" id="commission_delete{{$item->id}}">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <form action="{{route('comission.delete', $item->id)}}" method="get">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title h6">{{ translate("Info commission") }}</h5>
                                        <button type="button" class="close" data-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <h5>{{$item->title}} delete ?!</h5>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light"
                                                data-dismiss="modal">{{ translate('Cancel') }}</button>
                                        <button class="btn btn-primary" type="submit"><i
                                                class="la la-save me-2"></i> {{ translate('Delete') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('modal')
    {{--    @include('modals.delete_modal')--}}
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            var max_fields = 6;
            var wrapper = $(".add1");
            var add_button = $(".add_form_field");

            var x = 1;
            $(add_button).click(function (e) {
                e.preventDefault();
                if (x < max_fields) {
                    x++;
                    var form_field = '<div class="row mb-3"><div class="col-md-3"><label class="mb-0">{{ translate('From price') }}</label><input type="number" class="form-control" name="from_price[]" value=""> </div> <div class="col-md-3"> <label class="mb-0">{{ translate('To price') }}</label> <input type="number" class="form-control" name="to_price[]" value=""> </div> <div class="col-md-3"> <label class="mb-0">{{ translate('Percent') }}</label> <input type="number" class="form-control" name="percent[]" value=""> </div><a href="#" class="btn btn-danger mt-3 delete">{{ translate('Delete') }}</a></div> </div>';
                    $(wrapper).append(form_field);
                } else {
                    alert('You Reached the limits')
                }
            });

            $(wrapper).on("click", ".delete", function (e) {
                e.preventDefault();
                $(this).parent('div').remove();
                x--;
            })
        });
    </script>
@endsection
