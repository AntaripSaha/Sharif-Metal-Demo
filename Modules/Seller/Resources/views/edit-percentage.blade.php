<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Voucher No : {{ $edit_percentage->voucher_no}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">

  <form  method="post" action="{{ route('sales.discount.update',$edit_percentage->id) }}">
      @csrf
      <div class="row">

          <!-- Total Amount -->
          <div class="col-md-12 col-12 form-group">
              <label for="name">Total Amount</label>
              <input type="number" class="form-control" id="sub_total" name="amount" value="{{ $total }}" readonly >
          </div>

          <!-- Sale Discount -->
          <div class="col-md-12 col-12 form-group">
              <label for="name">Sale Discount %</label>
              <input type="number" id="sale_disc" min="0" class="form-control" name="sale_disc" value="{{ $edit_percentage->sale_disc }}">
          </div>

          <!-- Sale Discount -->
          <div class="col-md-12 col-12 form-group">
              <label for="name">Discount</label>
              <input type="number" class="form-control" id="del_discount" name="del_discount" readonly value="{{ $edit_percentage->del_discount }}">
          </div>

          <!-- Total -->
          <div class="col-md-12 col-12 form-group">
              <label for="name">Total</label>
              <input type="number" class="form-control" id="del_amount" name="del_amount" readonly value="{{ $edit_percentage->del_amount }}">
          </div>
          
          </div>

          <div class="col-md-12 form-group text-right">
              <button type="submit" class="btn btn-outline-dark">
                  Update
              </button>
          </div>

      </div>
  </form>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>

<link href="{{ asset('backend/css/select2/form-select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/select2/select2-materialize.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/select2/select2.min.css') }}" rel="stylesheet">

<script src="{{ asset('backend/js/select2/form-select2.min.js') }}"></script>
<script src="{{ asset('backend/js/select2/select2.full.min.js') }}"></script>

<script>
  $(document).ready(function domReady() {
      $(".select2").select2({
          dropdownAutoWidth: true,
          width: '100%',
          dropdownParent: $('#largeModal')
      });
  });
</script>


<script>
  $(document).ready(function(){
      $(".addNewTest").click(function(){
          $(".test-list").append(`
              <div class="row">

                  <div class="col-md-6 form-group">
                      <label>Test Name</label>
                      <input type="text" class="form-control" name="test_name[]" >
                  </div>

                  <div class="col-md-5 form-group">
                      <label>Test Price</label>
                      <input type="text" class="form-control" name="test_price[]" >
                  </div>

                  <div class="col-md-1 form-group">
                      <label></label>
                      <label></label>
                      <button type="button" class="btn btn-danger removeItem">
                          <i class='fas fa-times'></i>
                      </button>
                  </div>

              </div>
          `);
      })
  })

  $(document).on("click",".removeItem",function(){
      let $this = $(this)
      $this.closest(".row").remove()
  })
</script>


<!------ Calculate New Sale Discount ------>
<script>

    // var sub_total = $('#sub_total').val();
    // console.log(sub_total);

    $(document).on('input', "#sale_disc" , function(){
        var sub_total = $('#sub_total').val();        
        var new_sale_discount_amount = $('#sale_disc').val();
        discount_amount = (sub_total * new_sale_discount_amount) / 100;
        new_grand_total_amount = sub_total - discount_amount;
       
        $('#del_discount').val(discount_amount);
        $('#del_amount').val(new_grand_total_amount);
    } );

</script>

<!------ Calculate New Sale Discount ------>


