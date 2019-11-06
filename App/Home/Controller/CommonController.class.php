<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {
	protected $user;
	protected $TableUser;



	public function __construct(){
		parent::__construct();
		$this->TableUser = M('user');
		$userID = Session('userID');


		$this->user = $suer = $this->TableUser->where(['id'=>$userID])->find();
		if (MD5($suer['user'] . C('PASSMD5')) != cookie('username') ) {
			$this->error('请先登陆后操作' , '/Home/login/index');
		}
		$this->users = $this->user;
	}






}