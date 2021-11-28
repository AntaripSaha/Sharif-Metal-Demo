<div class="modal-header">
    <h5 class="modal-title">{{$customer->customer_name}} Details</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="form-group m-form__group row">
        <div class="col-lg-4">
            <label>
                @lang('customer.customer_name')
            </label>
            <input type="text" class="form-control m-input m-input--solid"
                readonly value="{{$customer->customer_name}}">
        </div>
        <div class="col-lg-4">
            <label>
                @lang('customer.customer_email')
            </label>
            <input type="text" class="form-control m-input m-input--solid"
                value="{{$customer->customer_email}}" readonly>
        </div>
        <div class="col-lg-4">
            <label>
                @lang('customer.seller')
            </label>
            <input type="text" class="form-control m-input m-input--solid"
                value="{{$customer->seller->user_id}} - {{$customer->seller->name}}" readonly>
        </div>
    </div>
    <div class="form-group m-form__group row">

        <div class="col-lg-6">
            <label class="">
                @lang('customer.customer_phone')
            </label>
            <input type="text" class="form-control m-input m-input--solid"
                value="{{$customer->customer_mobile}}" readonly>
        </div>
        <div class="col-lg-6">
            <label class="">
                @lang('customer.secondery_phone')
            </label>
            <input type="text" class="form-control m-input m-input--solid"
                value="{{$customer->phone}}" readonly>
        </div>
        <div class="col-lg-6">
            <label class="">
                @lang('layout.address')
            </label>
            <input type="text" class="form-control m-input m-input--solid"
                value="{{$customer->customer_address}}" readonly>
        </div>
        <div class="col-lg-6">
            <label class="">
                @lang('layout.secondary_address')
            </label>
            <input type="text" class="form-control m-input m-input--solid"
                value="{{$customer->address2}}" readonly>
        </div>

    </div>
    <div class="form-group m-form__group row">

        <div class="col-lg-4">
            <label class="">
                @lang('layout.city')
            </label>
            <input type="text" class="form-control m-input m-input--solid" value="{{$customer->city}}" readonly>
        </div>
        <div class="col-lg-4">
            <label class="">
                @lang('layout.state')
            </label>
            <input type="text" class="form-control m-input m-input--solid"
                value="{{$customer->state}}" readonly>
        </div>
        <div class="col-lg-4">
            <label class="">
                @lang('layout.country')
            </label>
            <input type="text" class="form-control m-input m-input--solid"
                 value="{{$customer->country}}" readonly>
        </div>
    </div>
    <div class="form-group m-form__group row">

        <div class="col-lg-12">
            <label class="">
                @lang('customer.pre_balance')
            </label>
            <input type="number" class="form-control m-input m-input--solid" @if($pre_balance !== null) value="{{$pre_balance->Debit}}" @else value="0.00" @endif readonly>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('layout.close')</button>
</div>
