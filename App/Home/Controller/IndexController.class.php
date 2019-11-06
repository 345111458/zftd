<?php
namespace Home\Controller;

use Org\Util\Strings;


class IndexController extends CommonController {

    protected $String;


    public function __construct(){
        parent::__construct();

        $this->String = new Strings();

    }

    public function index(){
        


    	return $this->display();    
    }


    /**
    * 单笔打款
    */
    public function Pay(){
    	if (IS_JAXA && IS_POST) {
    		$post = I('post.');

    		if (empty($post['username']) || empty($post['bank']) || empty($post['bankcard']) || empty($post['paypass']) || empty($post['money']) ) {
    			return $this->error('以上都为必填项');
    		}

    		$res = $this->TableUser->where(['id'=>$this->user['id']])->find();
    		if (!empty($res) && $res['paypass'] == MD5($post['paypass'] . C('PASSMD5'))) {
    			$post['paypass'] = $res['paypass'];
    			$post['addtime'] = time();

                OpeLog($this->user['user'] , '单笔打款操作' , CONTROLLER_NAME .'/'. ACTION_NAME);
                return M('payment')->data($post)->add() ? $this->success('提交成功') : $this->error('提交失败');
            }else{
                $this->error('支付密码不正确');
            }

    	}else{

    		$this->display('Pays');
    	}
    }


    /**
    * 批量打款 
    */
    public function SinglePay(){
    	if (IS_AJAX && IS_POST) {
    		$post = I('post.');

    		if (empty($post['file']) ) {
    			return $this->error('以上都为必填项');
    		}

    		$res = $this->TableUser->where(['id'=>$this->user['id']])->find();
    		if (!empty($res) && $res['paypass'] == MD5($post['paypass'] . C('PASSMD5'))) {
                $data = $this->PHPEfile($post['file']);

                OpeLog($this->user['user'] , '批量打款/'.count($data) , CONTROLLER_NAME .'/'. ACTION_NAME);
    			return M('payment')->addAll($data) ? $this->success('提交成功') : $this->error('添加失败');
    		}else{
    			$this->error('支付密码不正确');
    		}
    	}else{

    		$this->display('SinglePays');
    	}
    }


    /**
    * 发起 调用接口支付
    */
    public function CallPay(){
        if (IS_AJAX && IS_POST) {

            $post = I('post.');
            $find = M('payment')->where(['id'=>$post['oid']])->find();

            if(empty($find)){
                $this->error('订单未找到！');
            }
            if($find['status'] != 0){
                $this->error('已调用得订单，不可再次提交！');
            }


            $order_id = time().rand(99, 9999).rand(99, 9999);
            M('payment')->where(['id'=>$find['id']])->save(['status'=>1, 'addtime'=>time(),  'order_id' => $order_id]);
            OpeLog($this->user['user'] , '代付提交/'.$find['id'] , CONTROLLER_NAME .'/'. ACTION_NAME);
            $result = $this->postSubmitPay([
                'order_id' => $order_id,
                'customer_name' => $find['username'],
                'account_number' => $find['bankcard'],
                'issue_bank_name' => $find['bank'],
                'amount' => $find['money']
            ]);


            if($result['op_ret_code'] == '000' && $result['op_err_msg'] == '成功'){
                if(M('payment')->where(['id'=>$find['id']])->save(['status'=>3, 'result_code' => $result['op_err_msg']])){
                    $this->success('代付提交成功');
                }else{
                    $this->error('代付异常，请联系技术');
                }
            }else{
                $this->error($result['op_err_msg']);
            }

        }
    }

    private function postSubmitPay($data)
    {
        require ("./vendor/mfb/m2base.php");
        $m2 = new \m2base ();

        $post_data = [
            'merchant_number' => 'SHID20181123636',
            'order_number' => $data['order_id'],
            'wallet_id' => '0100851927029529',
            'asset_id' => '24d092ec1e3542c09cc37abff235f8d9',
            'business_type' => 1,
            'money_model' => 1,
            'source' => 0,
            'password_type' => '02',
            'encrypt_type' => '02',
            'pay_password' => '8dd87bb3e99868466e4007cb3ddee6f0',
            'customer_type' => '01',
            'customer_name' => $data['customer_name'],
            'account_number' => $data['account_number'],
            'issue_bank_name' => $data['issue_bank_name'],
            'currency' => 'CNY',
            'amount' => $data['amount'],
            'async_notification_addr' => 'http://www.demo.com/paying.php',
            'app_code' => 'apc_02000000041',
            'app_version' => '1.0.0',
            'service_code' => 'sne_00000000002',
            'plat_form' => '01',
            'login_token' => '',
            'req_no' => rand(99, 9999).time(),
            'payment_channel_list' => array()
        ];

        $result = $m2->url_data ( 'PAYING', $post_data, "POST" );

        $res=json_decode($result,true);

        return $res;

    }

    /**数据未支付表格*/
    public function WPayList(){
        if (IS_GET && IS_AJAX) {
            $get = I('get.');

            //不空加入条件
            foreach ($get['key'] as $k => $v) {
                if (!empty($v)) {
                    $where[$k] = $v;
                }
            }
            if ($where['status'] == '9') {
                $where['status'] = '0';
            }elseif($where['status'] == ''){
                $where['status'] = ['not in' ,'3'];
            }
            
            // 时间
            if (!empty($get['key']['addtime'])) {
                list($t1,$t2) = explode(' - ' , $get['key']['addtime']);
                $t1 = strtotime($t1.' 00:00:00');
                $t2 = strtotime($t2.' 23:59:59');
                $where['addtime'] = ['between',[$t1,$t2]];
            }
            
            $count = M('payment')->where($where)->count();
            $data = M('payment')->where($where)->order('id desc')->field('id,username,bank,bankcard,money,addtime,status,cardid,result_code')->limit($get['page'] == '1' ? '0' : $get['page'] * 10 - 10,$get['limit'])->select();

            // foreach ($data as $k => &$v) {
            //     $v['addtime'] = date('Y-m-d H:i:s' , $v['addtime']);
            // }
    
            $res['code'] = '0';
            $res['msg'] = '';
            $res['count'] = $count;
            $res['data'] = $data;
            echo json_encode($res);
        }else{

            $this->display('WPayList');
        }
    }


    /**数据已支付表格*/
    public function YPayList(){
    	if (IS_GET && IS_AJAX) {
    		$get = I('get.');

            //不空加入条件
            foreach ($get['key'] as $k => $v) {
                if (!empty($v)) {
                    // unset($get['key'][$k]);
                    $where[$k] = $v;
                }
            }
            // 时间
            if (!empty($get['key']['addtime'])) {
        		list($t1,$t2) = explode(' - ' , $get['key']['addtime']);
        		$t1 = strtotime($t1.' 00:00:00');
        		$t2 = strtotime($t2.' 23:59:59');
        		$where['addtime'] = ['between',[$t1,$t2]];
            }
            $where['status'] = 3;

    		$count = M('payment')->where($where)->count();
    		$data = M('payment')->where($where)->order('id desc')->field('id,username,bank,bankcard,money,addtime,cardid')->limit($get['page'] == '1' ? '0' : $get['page'] * 10 - 10,$get['limit'])->select();
    		foreach ($data as $k => &$v) {
    			$v['addtime'] = date('Y-m-d H:i:s' , $v['addtime']);
    		}
    
    		$res['code'] = '0';
    		$res['msg'] = '';
    		$res['count'] = $count;
    		$res['data'] = $data;
    		echo json_encode($res);
    	}else{

    		$this->display('YPayList');
    	}
    }


    /**日志表格*/
    public function LogList(){
        if (IS_GET && IS_AJAX) {
            $get = I('get.');

            //不空加入条件
            foreach ($get['key'] as $k => $v) {
                if (!empty($v)) {
                    // unset($get['key'][$k]);
                    $where[$k] = $v;
                }
            }
            // 时间
            if (!empty($get['key']['addtime'])) {
                list($t1,$t2) = explode(' - ' , $get['key']['addtime']);
                $t1 = strtotime($t1);
                $t2 = strtotime($t2);
                $where['addtime'] = ['between',[$t1,$t2]];
            }

            $count = M('log')->where($where)->count();
            $data = M('log')->where($where)->order('id desc')->limit($get['page'] == '1' ? '0' : $get['page'] * 10 - 10,$get['limit'])->select();
            foreach ($data as $k => &$v) {
                $v['addtime'] = date('Y-m-d H:i:s' , $v['addtime']);
            }
    
            $res['code'] = '0';
            $res['msg'] = '';
            $res['count'] = $count;
            $res['data'] = $data;
            echo json_encode($res);
        }else{

            $this->display('LogList');
        }
    }



    /**
    * 上传文件
    */
    public function Upload() {
    	
	    $upload = new \Think\Upload();// 实例化上传类
	    $upload->maxSize   =     6000000;// 设置附件上传大小
	    $upload->exts      =     array('xlsx', 'xls');// 设置附件上传类型
	    $upload->rootPath  =     'Public/Uploads/'; // 设置附件上传根目录
	    $upload->savePath  =     ''; // 设置附件上传（子）目录
	    // 上传文件 
	    $info   =   $upload->upload();
	    $url = $upload->rootPath . $info['file']['savepath'] . $info['file']['savename'];

	    if(!$info) {// 上传错误提示错误信息
	        $this->error($upload->getError());
	    }else{// 上传成功
            OpeLog($this->user['user'] , '打款文件上传/'.$url , CONTROLLER_NAME .'/'. ACTION_NAME);
            $data = $this->PHPEfile($url);
            $res['status'] = 1;
            $res['info'] = '上传成功';
            $res['data']['code'] = '0';
            $res['data']['msg'] = '';
            $res['data']['count'] = $count;
            $res['data']['data'] = $data;
            $res['data']['url'] = $url;
            echo json_encode($res);

	        // $this->success('上传成功！' , ['data'=>json_encode($res),'url'=>$url]);
	    }
    }


    /**
    * 安全退出
    */
    public function logout(){
    	Cookie('username' , '');
    	Session('userID' , '');

        OpeLog($this->user['user'] , '安全退出/' , CONTROLLER_NAME .'/'. ACTION_NAME);
    	$this->success('退出成功','/Home/login/index');
    }



	/**
	*
	* 导入Excel文件
	*/
	public function PHPEfile($file_path){

        Vendor('PHPExcel.PHPExcel');
        Vendor('PHPExcel.PHPExcel.IOFactory');

        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        // $file_path = './Public/Uploads/2019-03-05/5c7e35b9748ad.xls';
        $objPHPExcel = $objReader->load($file_path, $encode='utf-8');
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        
        for($i=2;$i<=$highestRow;$i++){
            $data['username']   = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();
            $data['bank']       = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
            $data['bankcard']   = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
            $data['money']      = $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
            $data['cardid']     = strtoupper($objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue());
            $data['addtime']    = date('Y-m-d H:i:s',time());
            $data['file']       = $file_path;
            $allData[] = $data;
        }

        // echo '<pre>';
        // print_r($allData);
        return $allData;

    }



    /**
    * 删除未付列表订单
    */
    public function Yfdel(){
        $post = I('post.');

        OpeLog($this->user['user'] , '删除未付列表订单/'.$post['id'] , CONTROLLER_NAME .'/'. ACTION_NAME);
        if (IS_AJAX) {
            $where['status'] = ['not in' ,'3']; 
            $where['id'] = $post['id'];
            echo M('payment')->where($where)->delete() ? $this->success('删除成功！'):$this->error('删除失败！');
        }
    }




    /**
     * 易极付 代付
     */
    public function yiJiFu(){
        require ("./vendor/yijifu/yijifus.php");
        $m2 = new \yijifus();
        
        if (IS_AJAX && IS_POST) { //身份
            $post = I('post.');
            $find = M('payment')->where(['id'=>$post['oid']])->find();

            if(empty($find)){
                $this->error('订单未找到！');
            }

            if($find['status'] != 0){
                $this->error('已调用得订单，不可再次提交！');
            }

            if(empty($find['cardid'])){
                $this->error('身份证号不可为空！');
            }

            $order_id = orderId($this->String);// 生成 商户放款订单号
        
            M('payment')->where(['id'=>$find['id']])->save(['status'=>1, 'addtime'=>date('Y-m-d H:i:s',time()),  'order_id' => $order_id]);
            OpeLog($this->user['user'] , '代付提交/'.$find['id'] , CONTROLLER_NAME .'/'. ACTION_NAME);
            $result = $m2->jointEncrypt([
                'accountName'   =>  $find['username'],//收款方账户名称
                'certNo'        =>  $find['cardid'],//收款方身份证号码
                'accountNo'     =>  $find['bankcard'],//收款方账号
                'orderNo'       =>  $order_id,//商户放款订单号
                'transAmount'   =>  $find['money'],//放款金额
            ]);

            $url = 'http://merchantapi.yijifu.net/gateway';
            $resultMessage = httpRequest($url , 'POST' , $result);
            $resultMessage = json_decode($resultMessage);

            if($resultMessage->success == '1' && $resultMessage->resultMessage == '成功'){
                if(M('payment')->where(['id'=>$find['id']])->save(['status'=>3, 'result_code' => $resultMessage->resultMessage])){
                    $this->success('代付提交成功');
                }else{
                    $this->error('代付异常，请联系技术');
                }
            }else{
                M('payment')->where(['id'=>$find['id']])->save(['status'=>2, 'result_code' => $resultMessage->resultMessage]);
                $this->error($resultMessage->resultMessage);
            }
        }

    }



}