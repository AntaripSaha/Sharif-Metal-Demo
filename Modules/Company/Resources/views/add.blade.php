{!!Form::open(['route'=>'admin.companies.add','id'=>'company-add-form']) !!}
<div class="modal-header">
    <h5 class="modal-title">@lang('company.new_company')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="form-group m-form__group row">
        <div class="col-lg-6">
            <label>
                @lang('company.company_name')
            </label>
            <input type="text" name="name" class="form-control m-input m-input--solid"
                placeholder="@lang('company.company_name')">
        </div>
        <div class="col-lg-6">
            <label class="">
                @lang('company.no')
            </label>
            <input type="text" name="company_no" class="form-control m-input m-input--solid"
                placeholder="@lang('company.no')">
        </div>
    </div>
    <div class="form-group m-form__group row">
        <div class="col-lg-6">
            <label class="">
                @lang('layout.address')
            </label>
            <input type="text" name="address" class="form-control m-input m-input--solid"
                placeholder="@lang('layout.address')">
        </div>
        <div class="col-lg-2">
            <label>
                @lang('layout.postal_code')
            </label>
            <input type="text" name="postal_code" class="form-control m-input m-input--solid" placeholder="1234">
        </div>
        <div class="col-lg-4">
            <label>
                @lang('layout.city')
            </label>
            <input type="text" name="city" class="form-control m-input m-input--solid"
                placeholder="@lang('layout.city')">
        </div>
    </div>
    <div class="form-group m-form__group row">
        <div class="col-lg-6">
            <label>
                @lang('country.country')
            </label>
            <select type="text" onchange="changeNumberCode(this.value)" name="country_id"
                class="form-control m-input m-input--solid">
                @php echo get_option($country,'id','name', '1') @endphp
            </select>
        </div>
        <div class="col-lg-2">
            <label class="">
                @lang('country.country_code')
            </label>
            <input type="text" name="phone_code" id="phone_code" readonly class="form-control m-input m-input--solid"
                value="880">
        </div>
        <div class="col-lg-4">
            <label class="">
                @lang('layout.phone_number')
            </label>
            <input type="text" name="phone_no" class="form-control m-input m-input--solid"
                placeholder="@lang('layout.phone_number')">
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('layout.cancel')</button>
    <button type="button" onclick="submitAddOrganization()" class="btn btn-success">@lang('layout.save')</button>
</div>
{!! Form::close() !!}

<script type="text/javascript">
    function changeNumberCode(country_code) {
        var callback = function (data) {
            console.log(data);
            $('#phone_code').val(data.country_code);
        }
        if (country_code) {
            var url = '{{url('country-code')}}' + '/' + country_code;
            ajaxGetRequest(url, callback);
        } else {
            $('#phone_code').val('---');
        }
    }

    function submitAddOrganization() {
        var form = $('#company-add-form');
        var successcallback = function (a) {
            toastr.success("@lang('company.company_has_been_added')", "@lang('layout.success')!");
            $('#ajax-modal').modal('hide');
            location.reload();
        }
        ajaxValidationFormSubmit(form.attr('action'), form.serialize(), '', successcallback);
    }

</script>
