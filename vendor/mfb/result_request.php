<?php
header ( "content-type:text/html;charset=utf-8" );
include 'm2base.php';
$m2 = new m2base ();

$D =$_POST;
$D['app_code']='apc_02000000041';			//应用号
$D['app_version']='1.0.0';					//应用版本
$D['service_code']='sne_00000000002';		//服务号
$D['plat_form']='01';						//平台
$D['login_token']='';						//登录令牌
$D['req_no']=date('YmdHis',time());			//请求流水号
$D['payment_channel_list']=array();



$data = $m2->url_data ( 'RESULT', $D, "POST" );

$res=json_decode($data,true);

//此处处理同步返回的接口信息
var_dump($res);die;
