@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Edit Seller Paymo Information')}}</h5>
</div>

<div class="col-lg-6 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Paymo Information')}}</h5>
        </div>

        <div class="card-body">
          <form action="{{ route('sellers.paymo-update-now', ['id' => $setting[0]['id']] ) }}" method="POST">
                <input name="_method" type="hidden" value="PATCH">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="store_id">{{translate('Store ID')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Store ID')}}" id="store_id" name="store_id" class="form-control" value="{{$setting[0]['store_id']}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="account">{{translate('Account')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Account')}}" id="account" name="account" class="form-control" value="{{$setting[0]['account']}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="terminal_id">{{translate('Terminal ID')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Terminal ID')}}" id="terminal_id" name="terminal_id" value="{{$setting[0]['terminal_id']}}" class="form-control">
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
