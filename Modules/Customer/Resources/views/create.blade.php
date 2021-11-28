{!!Form::open(['route'=>'customer.addCustomer','id'=>'customer-create-form','enctype'=>"multipart/form-data"]) !!}
<div class="modal-header">
    <h5 class="modal-title">@lang('customer.new_customer')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="form-group m-form__group row">
        <div class="col-lg-2">
            <label>
                @lang('customer.customer_id')
            </label>
            <input type="text" name="customer_id" class="form-control m-input m-input--solid"
                placeholder="@lang('customer.customer_id')" required>
        </div>
        <div class="col-lg-4">
            <label>
                @lang('customer.customer_name')
            </label>
            <input type="text" name="customer_name" class="form-control m-input m-input--solid"
                placeholder="@lang('customer.customer_name')" required>
        </div>
        <div class="col-lg-3">
            <label>
                @lang('customer.customer_email')
            </label>
            <input type="text" name="customer_email" class="form-control m-input m-input--solid"
                placeholder="@lang('customer.customer_email')" >
        </div>
        <div class="col-lg-3">
            <label>
                @lang('customer.seller')
            </label>
            <select class="form-control" name="seller_id" id="seller_Select">
                @forelse($sellers as $seller)
                    <option value="{{$seller->id}}">{{$seller->user_id}} - {{$seller->name}}</option>
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
                placeholder="@lang('customer.customer_phone')" required>
        </div>
        <div class="col-lg-6">
            <label class="">
                @lang('customer.secondery_phone')
            </label>
            <input type="text" name="phone" class="form-control m-input m-input--solid"
                placeholder="@lang('customer.secondery_phone')" >
        </div>
        <div class="col-lg-6">
            <label class="">
                @lang('layout.address')
            </label>
            <input type="text" name="customer_address" class="form-control m-input m-input--solid"
                placeholder="@lang('layout.address')" required>
        </div>
        <div class="col-lg-6">
            <label class="">
                @lang('layout.secondary_address')
            </label>
            <input type="text" name="address2" class="form-control m-input m-input--solid"
                placeholder="@lang('layout.secondary_address')" >
        </div>

    </div>
    <div class="form-group m-form__group row">

        <div class="col-lg-4">
            <label class="">
                @lang('layout.city')
            </label>
            <input type="text" name="city" class="form-control m-input m-input--solid"
                placeholder="@lang('layout.city')" >
        </div>
        <div class="col-lg-4">
            <label class="">
                @lang('layout.state')
            </label>
            <input type="text" name="state" class="form-control m-input m-input--solid"
                placeholder="@lang('layout.state')" >
        </div>
        <div class="col-lg-4">
            <label class="">
                @lang('layout.country')
            </label>
            <input type="text" name="country" class="form-control m-input m-input--solid"
                placeholder="@lang('layout.country')" >
        </div>
    </div>
    <div class="form-group m-form__group row">

        <div class="col-lg-12">
            <label class="">
                @lang('customer.pre_balance')
            </label>
            <input type="number" name="balance" class="form-control m-input m-input--solid"
                placeholder="@lang('customer.pre_balance')">
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('layout.cancel')</button>
    <button type="button" onclick="AddNewCustomer()" class="btn btn-success">@lang('layout.save')</button>
</div>
{!! Form::close() !!}

<script type="text/javascript">
$("#seller_Select").select2()
    .on("select2:select", function(e) {
        var sel_element = $(e.currentTarget);
        var seller_val = sel_element.val();
        $('#seller_Select').val(seller_val);
    });

    function AddNewCustomer() {
        var form = $('#customer-create-form');
        var successcallback = function (a) {
            toastr.success('Customer Added Successfully !!');
            $('#ajax-modal').modal('hide');
            location.reload();
        }
        ajaxFormSubmit(form.attr('action'), form.serialize(), '', successcallback);
    }
</script>
