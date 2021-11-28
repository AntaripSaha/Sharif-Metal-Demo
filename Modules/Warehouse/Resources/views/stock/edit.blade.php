<div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">{{ $warehouse_product->product->product_name }} - {{ $warehouse_product->product->head_code }}</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
      </button>
 </div>

<div class="modal-body">

    <form class="ajax-form" method="post" action="{{ route('edit.stock',$warehouse_product->id) }}">
        @csrf
        <div class="row">
            
            <div class="col-md-2 col-3 form-group">
                <div class="form-check">
                      <input class="form-check-input" required type="radio" name="type" id="exampleRadios2" value="In">
                      <label class="form-check-label" for="exampleRadios2">
                        In
                      </label>
                </div>
            </div>
            <div class="col-md-10 col-9 form-group">
                <div class="form-check disabled">
                      <input class="form-check-input" required type="radio" name="type" id="exampleRadios3" value="Out" >
                      <label class="form-check-label" for="exampleRadios3">
                        Out
                      </label>
                </div>
            </div>
            
            <div class="col-md-6 col-12 form-group">
                <label>
                    Chalan No            
                </label>
                <input type="text" class="form-control" required name="chalan_no">
            </div>
            <div class="col-md-6 col-12 form-group">
                <label>
                    Date            
                </label>
                <input type="date" class="form-control" required name="v_date">
            </div>
            
            <div class="col-md-12 form-group">
                <label>Quantity</label>
                <input type="number" required class="form-control" name="quantity">
            </div>

            <div class="col-md-12 form-group text-right">
                <button type="submit" class="btn btn-outline-dark">
                    Update
                </button>
            </div>

        </div>
    </form>
</div>




