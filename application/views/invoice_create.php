<?php include ("common/header.php"); ?>  
<style type="text/css">
  .margin_bottom_4 {
    margin-bottom: 4%;
  }
</style>
<div class="container">
  <h2>Create Invoice</h2>
            
  <table class="table" id="invoice_tbl">
    <thead>
      <tr>
        <th>Name</th>
        <th>Quantity</th>
        <th>Unit Price</th>
        <th>Tax</th>
        <th>Total</th>
        <th>Add More</th>
      </tr>
    </thead>
    <tbody class="input_fields_wrap">
      <tr>
        <td><input type="text" name="name" class="form-control name required"></td>
        <td><input type="text" name="qty" class="form-control qty required inv_fields"></td>
        <td><input type="text" name="unit_price" class="form-control unit_price required inv_fields"></td>
        <td>
          <select class="form-control inv_fields tax" id="tax" name="tax">
            <option value="0">0 %</option>
            <option value="1">1 %</option>
            <option value="5">5 %</option>
            <option value="10">10 %</option>
          </select>
        </td>
        <td><input name="line_total" readonly type="text" class="form-control line_total"></td>
        <td><button type="button" class="btn btn-primary add_field_button">Add More</button></td>
        <td></td>
      </tr>

    </tbody>
  </table>

  <form method="POST" id="invoice_form" action="invoice/get-invoice-data">

    <div class="row">
      <div class="col-md-6"></div>
      <div class="col-md-2">Subtotal without tax : </div>
      <div class="col-md-3"><input readonly name="total_without_tax" type="text" class="form-control margin_bottom_4" id="total_without_tax"></div>
    </div>

    <div class="row">
      <div class="col-md-6"></div>
      <div class="col-md-2">Subtotal with tax : </div>
      <div class="col-md-3"><input readonly name="total_with_tax" type="text" class="form-control margin_bottom_4" id="total_with_tax"></div>
    </div>

    <div class="row">
      <div class="col-md-6"></div>
      <div class="col-md-2">Discount (in Rs): </div>
      <div class="col-md-3"><input name="discount" type="text" class="form-control margin_bottom_4" id="discount"></div>
    </div>

    <div class="row">
      <div class="col-md-6"></div>
      <div class="col-md-2">Total Amount : </div>
      <div class="col-md-3"><input readonly name="total" type="text" class="form-control margin_bottom_4" id="total"></div>
    </div>

    

    <div class="row">
      <div class="col-md-3"></div>
      <div class="col-md-6">
        
          <input type="hidden" name="item_name" id="item_name_arr"/>
          <input type="hidden" name="qty_arr" id="qty_arr"/>
          <input type="hidden" name="unit_price_arr" id="unit_price_arr"/>
          <input type="hidden" name="tax_arr" id="tax_arr"/>
          <input type="hidden" name="line_total_arr" id="line_total_arr"/>

          <button type="button" id="generate_invoice" class="btn btn-primary btn-lg">Generate Invoice</button>
        
      </div>
     
    </div>

  </form>


</div>

</body>
</html>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script type="text/javascript">

  var subtotal_without_tax = 0;
  var subtotal_with_tax = 0;
  var item_arr = []; 
  var qty_arr = []; 
  var unit_price_arr = []; 
  var tax_arr = [];
  var line_total_arr = [];

  $(document).ready(function() {

    $('.unit_price').inputmask('9{1,9}.9{1,2}');
    $('.qty').inputmask('9{1,9}');

    var wrapper       = $(".input_fields_wrap"); //Fields wrapper
    $(wrapper).on('click', '.add_field_button', function(e){ //on add input button click
      e.preventDefault();

      if(validateRow($(this))) {

        var html = '';
        html += '<tr>';
        html += '<td><input type="text" name="name" class="form-control name"></td>';
        html += '<td><input type="text" name="qty" class="form-control inv_fields qty"></td>';
        html += '<td><input type="text" name="unit_price" class="form-control unit_price inv_fields"></td>';
        html += '<td>';
        html += '<select class="form-control inv_fields tax" id="tax" name="tax">';
        html += '<option value="0">0 %</option>';
        html += '<option value="1">1 %</option>';
        html += '<option value="5">5 %</option>';
        html += '<option value="10">10 %</option>';
        html += '</select>';
        html += '</td>';
        html += '<td><input readonly name="line_total" type="text" class="form-control line_total"></td>';
        html += '<td><button type="button" class="btn btn-primary add_field_button">Add More</button></td>';
        html += '<td><button type="button" class="btn btn-danger remove_field">X</button></td>'
        html += '</tr>';

        $(wrapper).append(html); //add input box

        calculatePrice($(this));
        calculateTotal();

      }

    });
    
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
      e.preventDefault(); $(this).closest('tr').remove();
      calculatePrice($(this));
      calculateTotal();
    });

    $(wrapper).on("change",".inv_fields", function(e){ //user click on remove text
      e.preventDefault();
      calculatePrice($(this));
      calculateTotal();
    });

    $(".required").blur(function(){
      if(validateInput(this.name)) {

      } else {
        showError(this.name);

      }
    });

    $("#discount").blur(function(){
      if($(this).val().length > 0) {
        calculateTotal();
      }
    });

    $("#generate_invoice").click(function(e){
      e.preventDefault();

      let item_name_array = [];
      let qty_array = [];
      let unit_price_array = [];
      let tax_array = []
      let line_total_array = [];

       $(".name").each(function(){
        item_name_array.push($(this).val())
      });
      $(".qty").each(function(){
        qty_array.push($(this).val())
      });
      $(".unit_price").each(function(){
        unit_price_array.push($(this).val())
      });
      $(".tax").each(function(){
        tax_array.push($(this).val())
      });
      
      $(".line_total").each(function(){
        line_total_array.push($(this).val())
      });

      $('#item_name_arr').val(JSON.stringify(item_name_array));
      $('#qty_arr').val(JSON.stringify(qty_array));
      $('#unit_price_arr').val(JSON.stringify(unit_price_array));
      $('#tax_arr').val(JSON.stringify(tax_array));
      $('#line_total_arr').val(JSON.stringify(line_total_array));

      if($('#total').val()!= '') {
        $('form#invoice_form').submit();
      } else {
        alert('Please add items to create invoice!');
      }
      
    });



  });

  function validateInput(name = '') {
    if (name == 'name' || name == 'qty' || name == 'unit_price') {
      let val = $("[name="+name+"]").val();
      if (val.length == 0) 
        return false;
      else
        return true;
    }
  }

  function showError(name) {
    $("[name="+name+"]").parent().addClass('has-error');
  }

  function removeError(name) {
    $("[name="+name+"]").parent().removeClass('has-error');
  }

  function calculatePrice(ele) {
    let item = ele.closest("tr").find("input[name='name']").val();
    let qty = ele.closest("tr").find("input[name='qty']").val();
    let unit_price = ele.closest("tr").find("input[name='unit_price']").val();
    let tax = ele.closest("tr").find("select[name='tax']").val();


    if(qty!='' && unit_price!='' && tax!='') {
      let line_total = (parseFloat(unit_price) + (parseInt(unit_price)/100 * parseFloat(tax))) * parseInt(qty);
      ele.closest("tr").find("input[name='line_total']").val(line_total.toFixed(2));

      let line_total_sum = 0;
      $(".line_total").each(function(){
          line_total_sum += +$(this).val();
      });
      $('#total_with_tax').val(line_total_sum);

      let total_without_tax_sum = 0;
      let unit_price_array = [];
      let qty_sum_array = [];
      $(".unit_price").each(function(){
        unit_price_array.push($(this).val())
      });
      $(".qty").each(function(){
        qty_sum_array.push($(this).val())
      });

      var count = unit_price_array.length;
      for(var i=0;i<count;i++) {
        total_without_tax_sum += unit_price_array[i]*qty_sum_array[i];
      }
      $('#total_without_tax').val(total_without_tax_sum);

    }
  }

  function calculateTotal() {
    let discount = $('#discount').val();
    let total_with_tax_val = $('#total_with_tax').val();
    let total_amt = 0;
    if(discount!='') {
      total_amt = total_with_tax_val - discount;
    } else {
      total_amt = total_with_tax_val
    }
    $('#total').val(total_amt);
  }

  function validateRow(ele) {
    let item = ele.closest("tr").find("input[name='name']").val();
    let qty = ele.closest("tr").find("input[name='qty']").val();
    let unit_price = ele.closest("tr").find("input[name='unit_price']").val();
    let tax = ele.closest("tr").find("select[name='tax']").val();

    if(item!='' && qty!='' && unit_price!='' && tax!='') {
      return true;
    } else {
      alert("Please add a valid item name, quantity and price");
      return false;
    }
  }
</script>
