<div id="newData">
	{{ Form::open(array('route' => array('accounts.new_code', $info['HeadCode']), 'id'=>'account-add-form')) }}
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
        <tr>
            <td>Head Code</td>
            <td><input type="text" name="HeadCode" id="txtHeadCode" class="form-control w-75" value="{{$info['HeadCode']}}" readonly /></td>
        </tr>
        <tr>
            <td>Head Name</td>
            <td>
                <input type="text" name="HeadName" id="HeadName" class="form-control w-75" value="" />
            </td>
        </tr>
        <tr>
            <td>Parent Head</td>
            <td>
                <input type="text" name="PHeadName" id="PHead" class="form-control w-75" readonly value="{{$info['PHeadName']}}" />
                <input type="hidden" name="PHeadCode" id="PHeadCode" value="{{$info['PHeadCode']}}" />
            </td>
        </tr>
        <tr>
            <td>Head Level</td>
            <td><input type="text" name="HeadLevel" id="txtHeadLevel" class="form-control w-75" readonly value="{{$info['HeadLevel']}}" /></td>
        </tr>
        <tr>
            <td>Head Type</td>
            <td><input type="text" name="HeadType" id="txtHeadType" class="form-control w-75" readonly value="{{$info['HeadType']}}" /></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <input type="checkbox" name="IsTransaction" value="1" id="IsTransaction" size="28" onchange="IsTransaction_change()"/>
                <label for="IsTransaction"> IsTransaction</label>
                <input type="checkbox" value="1" name="IsActive" id="IsActive" size="28" checked />
                <label for="IsActive"> IsActive</label>
                <input type="checkbox" value="1" name="IsGL" id="IsGL" size="28"/>
                <label for="IsGL"> IsGL</label>
            </td>
        </tr>
        <tr id="pr_balance_cr" class="d-none">
            <td>Opening Balance (Credit)</td>
            <td><input type="number" name="opb_credit" class="form-control w-75"></td>
        </tr>
        <tr id="pr_balance_deb" class="d-none">
            <td>Opening Balance(Debit)</td>
            <td><input type="number" name="opb_debit" class="form-control w-75"></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <input type="button" onclick="AddNewAccount()" id="btnSave" value="Save" />
            </td>
        </tr>
    </table>
    {!! Form::close() !!}
</div>
<script>
	"use strict";
    function IsTransaction_change() {
        if ($('#IsTransaction').val() == 1) {
            $( "#pr_balance_cr" ).removeClass( "d-none" );
            $( "#pr_balance_deb" ).removeClass( "d-none" );
        }else{
            $( "#pr_balance_cr" ).addClass( "d-none" );
            $( "#pr_balance_deb" ).addClass( "d-none" );
        }
    }
    function AddNewAccount() {
        var form = $('#account-add-form');
        var successcallback = function (a) {
            toastr.success("@lang('account.account_has_been_added')", "@lang('layout.success')!");
            location.reload();
        }
        ajaxFormSubmit(form.attr('action'), form.serialize(), '', successcallback);
    }
</script>	