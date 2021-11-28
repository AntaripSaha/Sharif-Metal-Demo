{{-- {!!Form::open(['route'=>array('customer.import_file'),'id'=>'customer-import-form','enctype' => 'multipart/form-data']) !!} --}}
<form method="POST" action="{{ route('users.importSellers') }}" enctype="multipart/form-data">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Sellers Excel File</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
    </div>
    <div class="modal-body">
        <div class="form-group m-form__group row">
            <div class="col-lg-6">
                <label for="file-upload" class="custom-file-upload btn btn-base btn-md">
                    <i class="fas fa-file-excel mr-1"></i>EXCEL
                </label>
                <input id="file-upload" class="file_upload" name="sellers_file" type="file" multiple="multiple" />
                <span id="filename"></span>
            </div>
            <div class="col-lg-12 row pt-3">
                <div class="col-lg-6">
                    <b>@lang('layout.please_follow_the_instraction')</b>
                    <ul>
                        <li class="text-danger">Name must not empty</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <a href=""><button type="button"
                            class="btn btn-success float-right ">@lang('layout.download_demo')</button></a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer import_submit">
        <button type="button" class="btn btn-danger" data-dismiss="modal">@lang('layout.cancel')</button>
        <button type="submit" id="import_file" class="btn btn-success">@lang('layout.save')</button>
    </div>
</form>
{{-- {!! Form::close() !!} --}}
{{-- <script src="{{asset('js/Modules/Customer/import_file.js')}}"></script> --}}
