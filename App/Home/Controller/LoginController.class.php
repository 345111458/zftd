<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {

	public function index(){
		if (IS_AJAX && IS_POST) {
			$post = I('post.');

			$res = M('user')->where(['user'=>$post['user']])->find();

			if (md5($post['pass'] . C('PASSMD5')) == $res['pass']) {
				Session('userID' , $res['id']);
				cookie('username' , MD5($res['user'] . C('PASSMD5')) , 36000);

				OpeLog($post['user'] , '登陆操作/' , CONTROLLER_NAME .'/'. ACTION_NAME);
				$this->success('登陆成功');
			}else{

				OpeLog($post['user'] , '偿试登陆操作/'.$post['pass'] , CONTROLLER_NAME .'/'. ACTION_NAME);
				$this->error('帐号密码错误');
			}
		}else{

			$this->display();
		}
	}






}



?>