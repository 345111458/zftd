<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
</head>
<form  action = "paying_request.php" method ="POST">
  		<table>
  			<tr><td>商户号</td><td><input type="text" id="merchant_number" name="merchant_number" value="SHID20181123636"/></td><td>商户号</td></tr>
			<tr><td>商户订单号</td><td><input type="text" id="order_number" name="order_number" value="<?php echo rand().rand();?>"/></td><td>订单号，保证对应电商一笔订单</td></tr>
			<tr><td>付款钱包id</td><td><input type="text" id="wallet_id" name="wallet_id" value="0100851927029529"/></td><td>付款钱包id</td></tr>
            <tr><td>付款资产id</td><td><input type="text" id="asset_id" name="asset_id" value="24d092ec1e3542c09cc37abff235f8d9"/></td><td>资产ID,只允许电子账户</td></tr>
			<tr><td>业务类型</td><td><input type="text" id="business_type" name="business_type" value="1"/></td><td>1 代付到个人储蓄卡</td></tr>
			<tr><td>资金模式</td><td><input type="text" id="money_model" name="money_model" value="1"/></td><td>1 T1-预存</td></tr>
			<tr><td>代付渠道</td><td><input type="text" id="source" name="source" value="0"/></td><td>0 API接口</td></tr>
			<tr><td>密码类型</td><td><input type="text" id="password_type" name="password_type" value="02"/></td><td>02支付密码</td></tr>
			<tr><td>加密类型</td><td><input type="text" id="encrypt_type" name="encrypt_type" value="02"/></td><td>02 MD5 加密</td></tr>
			<tr><td>支付密码</td><td><input type="text" id="pay_password" name="pay_password" value="8dd87bb3e99868466e4007cb3ddee6f0"/></td><td></td></tr>
			<tr><td>收款人客户类型</td><td><input type="text" id="customer_type" name="customer_type" value="01"/></td><td>01 对私传入</td></tr>
			<tr><td>收款客户姓名</td><td><input type="text" id="customer_name" name="customer_name" value="曹德霖"/></td><td></td></tr>
            <tr><td>收款人银行卡</td><td><input type="text" id="account_number" name="account_number" value="6217007200073926522"/></td><td></td></tr>
            <tr><td>支行</td><td><input type="text" id="issue_bank_name" name="issue_bank_name" value="中国建设银行深圳分行营业部"/></td><td></td></tr>
			<tr><td>币种</td><td><input type="text" id="currency" name="currency" value="CNY"/></td><td></td></tr>			
			<tr><td>订单金额</td><td><input type="text" id="amount" name="amount" value="0.01"/></td><td>金额：保留两位小数</td></tr>
			<tr><td>异步通知地址</td><td><input type="text" id="async_notification_addr" name="async_notification_addr" value="http://www.demo.com/paying.php"/></td><td></td></tr>
			<tr><td><input type = "submit" ></td></tr>
  		</table>
  	</form>
</html>


















