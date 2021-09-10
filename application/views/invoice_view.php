<?php include ("common/header.php"); ?>  

<style type="text/css">
  .container {
    padding-top: 5%;
  }
  td[colspan="4"] {
  text-align: right;

  }
</style>

<div class="container">
  <div class="row">
    <div class="col-md-10"><h2>Invoice</h2></div>
    <div class="col-md-2"><button type="button" class="btn btn-primary" onclick="history.back();">Go Back</button></div>
  </div>
  
  
         
  <table class="table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Quantity</th>
        <th>Unit Price</th>
        <th>Tax</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      $count = count($name);
      for($i=0;$i<$count;$i++) {
      ?>
      <tr>
        <td><?php echo $name[$i]?></td>
        <td><?php echo $qty_arr[$i]?></td>
        <td><?php echo $unit_price_arr[$i]?></td>
        <td><?php echo $tax_arr[$i]?> %</td>
        <td><?php echo $line_total_arr[$i]?></td>
      </tr>
      <?php } ?>
      <tr>
        <td colspan="4"><h4>Subtotal without tax  : <h4></td>
        <td><h4><?php echo $total_without_tax?><h4></td>
      </tr>
      <tr>
        <td colspan="4"><h4>Subtotal with tax  : <h4></td>
        <td><h4><?php echo $total_with_tax?><h4></td>
      </tr>
      <tr>
        <td colspan="4"><h4>Discount  : <h4></td>
        <td><h4><?php echo $discount?><h4></td>
      </tr>
      <tr>
        <td colspan="4"><h3>Total Amount  : <h3></td>
        <td><h3><?php echo $total?><h3></td>
      </tr>
    </tbody>
  </table>
</div>

</body>
</html>
