<div id="newData">
	{{ Form::open(array('route' => array('accounts.view_code', $data->HeadCode), 'id'=>'account-update-form')) }}
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
        <tr>
            <td>Head Code</td>
            <td><input type="text" name="HeadCode" id="txtHeadCode" class="form-control w-75" value="{{$data->HeadCode}}" readonly /></td>
        </tr>
        <tr>
            <td>Head Name</td>
            <td>
                <input type="text" name="HeadName" id="HeadName" class="form-control w-75" value="{{$data->HeadName}}" />
                <input type="hidden" name="PrevHeadName" id="PrevHeadName" class="form-control w-75" value="{{$data->HeadName}}" />
            </td>
        </tr>
        <tr>
            <td>Parent Head</td>
            <td>
                <select name="PHead" id="txtPHead" class="form-control w-75">
                    @foreach($other_heads as $head)
                        <option value="{{$head->HeadCode}}" @if($data->PHeadCode == $head->HeadCode) selected @endif>{{$head->HeadName}}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td>Head Level</td>
            <td><input type="text" name="HeadLevel" id="txtHeadLevel" class="form-control w-75" readonly value="{{$data->HeadLevel}}" /></td>
        </tr>
        <tr>
            <td>Head Type</td>
            <td><input type="text" name="HeadType" id="txtHeadType" class="form-control w-75" readonly value="{{$data->HeadType}}" /></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <input type="checkbox" name="IsTransaction" value="{{$data->IsTransaction}}" id="IsTransaction" size="28" @if($data->IsTransaction == 1) checked @endif/>
                <label for="IsTransaction"> IsTransaction</label>
                <input type="checkbox" value="{{$data->IsActive}}" name="IsActive" id="IsActive" size="28" @if($data->IsActive == 1) checked @endif/>
                <label for="IsActive"> IsActive</label>
                <input type="checkbox" value="{{$data->IsGL}}" name="IsGL" id="IsGL" size="28" @if($data->IsGL == 1) checked @endif />
                <label for="IsGL"> IsGL</label>
            </td>
        </tr>
        @if($data->IsTransaction == 1)
        <tr id="pr_balance_cr">
            <td>Opening Balance (Credit)</td>
            <td><input type="number" name="opb_credit" value="{{$prb_cr}}" class="form-control w-75"></td>
        </tr>
        <tr id="pr_balance_deb">
            <td>Opening Balance(Debit)</td>
            <td><input type="number" name="opb_debit" value="{{$prb_deb}}" class="form-control w-75"></td>
        </tr>
        @else
        <tr id="pr_balance_cr" class="d-none">
            <td>Opening Balance (Credit)</td>
            <td><input type="number" name="opb_credit" class="form-control w-75"></td>
        </tr>
        <tr id="pr_balance_deb" class="d-none">
            <td>Opening Balance(Debit)</td>
            <td><input type="number" name="opb_debit" class="form-control w-75"></td>
        </tr>
        @endif
        <tr>
            <td>&nbsp;</td>
            <td>
                <input type="button" id="btnNew" value="New" onClick="newHeaddata({{$data->HeadCode}})" />
                <input type="button"  onclick="UpdateAccount()" id="btnUpdate" value="Update" />
            </td>
        </tr>
    </table>
    {!! Form::close() !!}
</div>
<script>
	"use strict";
    function UpdateAccount() {
        var form = $('#account-update-form');
        var successcallback = function (a) {
            toastr.success('Accouns Updated Successfylly !!');
            location.reload();
        }
        ajaxFormSubmit(form.attr('action'), form.serialize(), '', successcallback);
    }

    function newHeaddata(id){
    	var url= baseUrl+"accounts/new_code/"+id;
        getAjaxView(url,data=null,'coa_view',false,'get');
	}

$(function() {
    $("#IsTransaction").on("change", function() {
        var trans = $('#IsTransaction').val();
        if (trans == 1) {
            $('#IsTransaction').val(0);
            $('#pr_balance_cr').addClass('d-none');
            $('#pr_balance_deb').addClass('d-none');
        }else{
            $('#IsTransaction').val(1);
            $('#pr_balance_cr').removeClass('d-none');
            $('#pr_balance_deb').removeClass('d-none');
        }
    });
    $("#IsActive").on("change", function() {
        var trans = $('#IsActive').val();
        if (trans == 1) {
            $('#IsActive').val(0);
        }else{
            $('#IsActive').val(1);
        }
    });
    $("#IsGL").on("change", function() {
        var trans = $('#IsGL').val();
        if (trans == 1) {
            $('#IsGL').val(0);
        }else{
            $('#IsGL').val(1);
        }
    });
});

</script>	