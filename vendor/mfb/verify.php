<?php
header("content-type:text/html;charset=utf-8");
$config=include 'config.php';
include 'lib_bonus.php';

$return_data = $_REQUEST;


if(verify_sign($config['bonuse_key'],$return_data, $return_data['dstbdatasign'])){
	echo "00";//交易成功，在此处写后续的订单操作方法。
}else{
	//交易失败，在此处写后续的订单操作方法。
	echo "99";
}
