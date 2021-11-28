<div class="modal-header">
    <h5 class="modal-title">@lang('user.user_details')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="form-group m-form__group row">
        <div class="col-lg-4">
            <label>
                @lang('user.name')
            </label>
            <span class="form-control">{{$user->name}}</span>
        </div>
        <div class="col-lg-4">
            <label>
                @lang('user.email')
            </label>
            <span class="form-control">
                {{$user->email}}
            </span>
        </div>

        <div class="col-lg-4">
            <label>
                @lang('user.phone_no')
            </label>
            <span class="form-control">
                {{ $user->phone_no }}
            </span>
        </div>

        <div class="col-lg-4">
            <label>
                @lang('user.address')
            </label>
            <span class="form-control">
                {{ $user->address }}
            </span>
        </div>

        <div class="col-lg-4">
            <label>
                @lang('user.role')
            </label>
            <span class="form-control">
                {{ $user->role->name }}
            </span>
        </div>

        <div class="col-lg-4">
            <label>
                @lang('user.status')
            </label>
            <span class="form-control">
                @if($user->status == 1) Active @else Inactive @endif
            </span>
        </div>

        {{-- Parent User Info Start --}}
        <div class="col-lg-6">
            <label>Parent User Name</label>
            <span class="form-control">{{ $parent_info['name'] }}</span>
        </div>
        {{-- Parent User Info End --}}
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('layout.close')</button>
</div>
