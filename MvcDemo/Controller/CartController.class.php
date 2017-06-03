<?php
namespace Controller;

use Framework\Core\Controller;
use Model\CartModel;

class CartController extends Controller {
	public function index(){
		$cart = new CartModel();
		$cartList = $cart->select('uid,gid,gname,price,art_path,num');
	    return $this->view('Cart/cart')->with('cart', $cartList);
	}
	
	public function add() {
		$cart = new CartModel();
		$uid = $_SESSION['curr_user']['uid'];
		$isSucc = $cart->create();
		$count = $cart->where('gid=? and uid=?',array($cart->gid,$uid))->select('count(*) as count')[0]['count'];
		if($count != 0){
			$isSucc = $cart->execUpdate('
update shop_cart set num=num+1 where gid=? and uid=?',array($cart->gid,$uid));
		}else{
			if($isSucc){
				$cart->uid = $uid;
				$isSucc = $cart->add();
				$resp = array();
				
			}else{
				$resp['status'] = -1;
				$resp['msg'] = '请求失败';
			}
		}
		if($isSucc){
			$resp['status'] = 1;
			$resp['msg'] = 'ok';
		}else{
			$resp['status'] = 0;
			$resp['msg'] = 'error';
		}
		
		return $this->ajaxResponse($resp);
	}
	//减数量
	public function decr(){
		$cart = new CartModel();
		$cart->create();
		if($cart->num > 0){
			$isSuss = $cart->execUpdate('update shop_cart set num=num-1 where gid=? and uid=?',array($cart->gid,$cart->uid));
			if($isSuss){
				$resp['status'] = 1;
				$resp['msg'] = 'ok';
			}else{
				$resp['status'] = 0;
				$resp['msg'] = 'fail';
			}
			return $this->ajaxResponse($resp);
		}
		
	}
	//加数量
	public function incr(){
		$cart = new CartModel();
		$cart->create();
		$isSuss = $cart->execUpdate('update shop_cart set num=num+1 where gid=? and uid=?',array($cart->gid,$cart->uid));
		if($isSuss){
			$resp['status'] = 1;
			$resp['msg'] = 'ok';
		}else{
			$resp['status'] = 0;
			$resp['msg'] = 'fail';
		}
		return $this->ajaxResponse($resp);
	}
	
	public function updateNum() {
		$cart = new CartModel();
		$cart->create();
		if($cart->op == 1){
			$isSuss = $cart->execUpdate('update shop_cart set num=num+1 where gid=? and uid=?',array($cart->gid,$cart->uid));
		}else if($cart->op == 0){
			$isSuss = $cart->execUpdate('update shop_cart set num=num-1 where gid=? and uid=?',array($cart->gid,$cart->uid));
		}else{
			$isSuss = $cart->where('gid=? and uid=?',array($cart->gid,$cart->uid))->delete();
		}
		if($isSuss){
			$resp['status'] = 1;
			$resp['msg'] = 'ok';
		}else{
			$resp['status'] = 0;
			$resp['msg'] = 'fail';
		}
		return $this->ajaxResponse($resp);
	}
	
	public function cartList(){
		if(!empty($_SESSION['curr_user'])){
			$uid = $_SESSION['curr_user']['uid'];
			$cart = new CartModel();
			$cartList = $cart->where('uid=?',array($uid))->select();
			$resp['status'] = 1;
			$resp['msg'] = 'ok';
			$resp['data'] = $cartList;
			return $this->ajaxResponse($resp);
		}else{
			$resp['status'] = 0;
			$resp['msg'] = 'error';
			return $this->ajaxResponse($resp);
		}
		
	}
	
	public function delete() {
		$gid = $this->getParam ( 'gid' );
		$uid = $_SESSION ['curr_user'] ['uid'];
		$sql = 'delete from shop_cart where gid=? and uid=?';
		$resp = array (
				'status' => 0,
				'msg' => '删除购物车记录发生错误'
		);
		$cartModel = new CartModel ();
		if ($cartModel->execUpdate ( $sql, array (
				$gid,
				$uid
		) )) {
			$resp ['status'] = 1;
			$resp ['msg'] = 'ok';
		}
		return $this->ajaxResponse ( $resp );
	}
}