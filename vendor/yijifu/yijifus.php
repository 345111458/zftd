<?php 

class yijifus{
	//签约的服务平台账号对应的合作方ID（由笨熊金服提供），定长20字符
	private $partnerId = '20170821020000793831';

	// 商户私钥
	private $partnerKey = '6d4108e062023f8a38bd37a58738cf3d';  


	/**
	 * [jointEncrypt 加密]
	 * 代付逻辑
	 * @return [type] [description]
	 */
	public function jointEncrypt($arr){
		// a b c d e f g h i j  k l m n o p q r s t w x y z  
		$data = [
			'merchOrderNo'	=>	$arr['orderNo'],
			'transAmount'	=>	'1',
			'accountName'	=>	$arr['accountName'],
			'certNo'		=>	$arr['certNo'],
			'accountNo'		=>	$arr['accountNo'],
			'accountType'	=>	'PRIVATE',
			// 'bankCode'		=>	'ICBC',
			'purpose'		=>	'打款',
			'service'		=>	'loan',
			// 'version'		=>	'1.0',
			'partnerId'		=>	$this->partnerId,
			'orderNo'		=>	$arr['orderNo'],
			'signType'		=>	'MD5',
			'returnUrl'		=>	'',
			'notifyUrl'		=>	'',
		];

		// 排序 A - Z 
		$joint = md5("accountName={$data['accountName']}&accountNo={$data['accountNo']}&accountType={$data['accountType']}&certNo={$data['certNo']}&merchOrderNo={$data['merchOrderNo']}&notifyUrl={$data['notifyUrl']}&orderNo={$data['orderNo']}&partnerId={$data['partnerId']}&purpose={$data['purpose']}&returnUrl={$data['returnUrl']}&service={$data['service']}&signType={$data['signType']}&transAmount={$data['transAmount']}".$this->partnerKey);

		// 加密后数据
		$data['sign'] = $joint;

		return $data;	
	}







}















 ?>