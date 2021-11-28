{{ Form::open(array('route' => array('customer.updateCustomer', $customer->id), 'id'=>'customer-update-form')) }}
<div class="modal-header">
    <h5 class="modal-title">{{$customer->customer_name}} Info Update</h5>
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
            <input type="text" name="customer_name" class="form-control m-input m-input--solid" value="{{$customer->customer_name}}" required>
        </div>
        <div class="col-lg-4">
            <label>
                @lang('customer.customer_email')
            </label>
            <input type="text" name="customer_email" class="form-control m-input m-input--solid" value="{{$customer->customer_email}}">
        </div>
        <div class="col-lg-4">
            <label>
                @lang('customer.seller')
            </label>
            <select class="form-control" name="seller_id" id="seller_Select">
                @forelse($sellers as $seller)
                    <option value="{{$seller->id}}" @if($seller->id == $customer->seller_id) selected @endif>{{$seller->user_id}} - {{$seller->name}}</option>
                @empty
                    <option value="0">@lang('layout.select')</option>
                @endforelse
                </select>
        </div>
    </div>
    <div class="form-group m-form__group row">

        <div class="col-lg-6">
            <label class="">
                @lang('customer.customer_phone')
            </label>
            <input type="text" name="customer_mobile" class="form-control m-input m-input--solid"
                value="{{$customer->customer_mobile}}" required>
        </div>
        <div class="col-lg-6">
            <label class="">
                @lang('customer.secondery_phone')
            </label>
            <input type="text" name="phone" class="form-control m-input m-input--solid"
                value="{{$customer->phone}}" >
        </div>
        <div class="col-lg-6">
            <label class="">
                @lang('layout.address')
            </label>
            <input type="text" name="customer_address" class="form-control m-input m-input--solid"
                value="{{$customer->customer_address}}" required>
        </div>
        <div class="col-lg-6">
            <label class="">
                @lang('layout.secondary_address')
            </label>
            <input type="text" name="address2" class="form-control m-input m-input--solid" value="{{$customer->address2}}" >
        </div>

    </div>
    <div class="form-group m-form__group row">

        <div class="col-lg-4">
            <label class="">
                @lang('layout.city')
            </label>
            <input type="text" name="city" class="form-control m-input m-input--solid"
                value="{{$customer->city}}" >
        </div>
        <div class="col-lg-4">
            <label class="">
                @lang('layout.state')
            </label>
            <input type="text" name="state" class="form-control m-input m-input--solid"
                value="{{$customer->state}}" >
        </div>
        <div class="col-lg-4">
            <label class="">
                @lang('layout.country')
            </label>
            <input type="text" name="country" class="form-control m-input m-input--solid"
                value="{{$customer->country}}" >
        </div>
    </div>
    <div class="form-group m-form__group row">

        <div class="col-lg-12">
            <label class="">
                @lang('customer.pre_balance')
            </label>
            <input type="number" name="balance" class="form-control m-input m-input--solid"
                @if($pre_balance !== null) value="{{$pre_balance->Debit}}" @else value="" @endif>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('layout.cancel')</button>
    <button type="button" onclick="UpdateCustomer()" class="btn btn-success">@lang('layout.save')</button>
</div>
{!! Form::close() !!}

<script type="text/javascript">

$("#seller_Select").select2()
    .on("select2:select", function(e) {
        var sel_element = $(e.currentTarget);
        var seller_val = sel_element.val();
        $('#seller_Select').val(seller_val);
    });
    function UpdateCustomer() {
        var form = $('#customer-update-form');
        var successcallback = function (a) {
            toastr.success('Customer Updated Successfully !!');
            $('#ajax-modal').modal('hide');
            location.reload();
        }
        ajaxFormSubmit(form.attr('action'), form.serialize(), '', successcallback);
    }
</script>
