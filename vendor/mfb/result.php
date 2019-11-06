<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
</head>
<form  action = "result_request.php" method ="POST">
  		<table>
  			<tr><td>订单号</td><td><input type="text" id="order_number" name="order_number" value="<?php echo rand();?>"/></td><td>商家原始订单号</td></tr>  							
			<tr><td>交易类型</td><td><input type="text" id="deal_type" name="deal_type" value=""/></td><td>交易类型</td></tr>
			<tr><td>商户号</td><td><input type="text" id="merchant_number" name="merchant_number" value=""/></td><td>商户号</td></tr>	 						
			<tr><td><input type = "submit" ></td></tr>
  		</table>
  	</form>
</html>