<?php 
return array(
		'aid' => '8a179b8c67bcf908016941e983242943',//应用ID
		'key' => 'ftyfj|m2|20190303',//密钥
		'api_id' => array('PAYING'=>'eb_trans@agent_for_paying','RESULT'=>'eb_trans@get_order_deal_result'),//接口ID		 
		'debug' => 'false',//是否调试模式
		'url' => 'https://api.gomepay.com/CoreServlet',//10步模式接口地址
		'mode'=>'0',//mode:模式，可选项（默认：0，0=10步模式；1=4步模式），使用4步模式时打开注释。
		'url_ac' => 'https://api.gomepay.com/CoreServlet',//url_ac：4步模式的M2服务地址，4步模式时必填项。
		'url_ac_token' => 'https://api.gomepay.com/access_token',//url_ac_token：4步模式的得到访问令牌的M2服务地址，4步模式时必填项。
		'max_token' => 2,//获取令牌最大次数
		'is_data_sign'=>'1',//是否对数据包执行签名 1是  0否
		'memcache_open' => false,//是否使用memcache
		'memcached_server'=>'127.0.0.1:11211',//memcache地址
);
?>