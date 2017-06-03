<?php
namespace Controller;

use Framework\Core\Controller;
use Framework\Util\Verify\VerifyCode;
use Model\UserModel;

class UserController extends Controller {
	public function login(){
		$target = 'Goods/getList';
		if(!empty($this->getParam('a'))){
			$target = $this->getParam('a');
		}
		return $this->view('User/login')->with('a', $target);
	}
	
	public function getVCodeImage(){
		$vcode = new VerifyCode();
		return $vcode->getVerifyCodeImage();
	}
	
	public function cleck(){
		$resp = array();
		$user = new UserModel();
		$vcode = new VerifyCode();
		if($vcode->check($this->postParam('vcode'))){
			$uname = $this->postParam('username');
			$email = $this->postParam('username');
			$pwd = $this->postParam('password');
			$data = $user->where('(email=? or uname=?) and password=?',array($email,$uname,$pwd))->select();
			if(!empty($data)){
				$_SESSION['curr_user'] = $data[0];
			}else{
				$_SESSION['curr_user'] = array();
			}
			
			if(!empty($data)){
				$resp['status'] = 1;
				$resp['msg'] = 'ok';
				$resp['data'] = $data;
			}else{
				$resp['status'] = 0;
				$resp['msg'] = '用户名或者密码错误';
				
			}
		}else{
			$resp['status'] = -1;
			$resp['msg'] = '验证码错误';
			
		}
		return $this->ajaxResponse($resp);
	}
	
	public function logout() {
		$_SESSION['curr_user'] = null;	
		return $this->ajaxResponse(array(
				'status'=>1,
				'msg'=>'ok'
		));
	}
}










