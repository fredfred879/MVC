<?php
namespace Controller;
use Framework\Core\Controller;
use Model\GoodsModel;
use Model\CategoryModel;

class GoodsController extends Controller {
	public function getList() {
		$cid = 0;
		$path = '0';
		$currCtgr = '';
		
		if(!empty($this->getParam('cid'))){
			$cid = $this->getParam('cid');
			$path = $this->getParam('path');
			$currCtgr = $this->getParam('parent');
		}
	
		$pathed = $path . '%';
		$ctgr = array();
		$category = new CategoryModel();
		$ctgrList = $category->getSortOutList('cid,cname,parent_id,description,path');
		if(!empty($path)){
			$ctgr = $category->query('select cid,cname,path,parent_id from shop_category where path like ? and parent_id=?',array($pathed,$cid));
			$c = $category->query('select cid,cname,path,parent_id from shop_category where cid like ?',array($currCtgr));
	
			$goods = new GoodsModel();
			$goodsList = $goods->query('select * from shop_goods where cid in (select cid from shop_category where path like ?"-%");',array($path));
			return $this->view('Goods/list_goods')->with('goodsList', $goodsList)->with('category', $ctgrList)->with('ctgr', $ctgr)->with('c',$c);
			
		}else{
			foreach ($ctgrList as $k){
				if($k['parent_id'] == 0){
					array_push($ctgr, $k);
				}
			}
			$goods = new GoodsModel();
			$c = $category->query('select cid,cname,path,parent_id from shop_category where cid like ?',array($currCtgr));
			$goodsList = $goods->query('select * from shop_goods where cid in (select cid from shop_category where path like ?"-%");',array($path));
			return $this->view('Goods/list_goods')->with('goodsList', $goodsList)->with('category', $ctgrList)->with('ctgr', $ctgr)->with('c',$c);
		}
		
		
	}
	
	public function add() {
	
		$category = new CategoryModel();
		$ctgrList = $category->getSortOutList('cid,cname,parent_id,description,path');
		return $this->view('Goods/add_goods')->with('ctgrList', $ctgrList);
	}
	
	public function doAdd() {
		$fileInfo = $_FILES['art'];
		if($fileInfo['size'] == 0 || $fileInfo['size'] > 2 * 1024 * 1024) {
			$this->error('请上传小于2M的图片哦，亲~');
			return;
		}
		if (($fileInfo['type'] != 'image/png') && ($fileInfo['type'] != 'image/jpg') && ($fileInfo['type'] != 'image/jpeg') && ($fileInfo['type'] != 'image/gif')) {
			$this->error('请上传正确格式的图片哦，亲~');
			return;
		}
		if ($fileInfo['error'] > 0) {
			$this->error('上传图片发生错误');
			return;
		}
		if (!preg_match('/^[\w-]+\.[a-zA-Z]{3,4}$/', $fileInfo['name'])) {
			$this->error('图片名不合法');
			return;
		}
		// 处理图片上传
		$filePath = 'upload/image/' . md5('' . mt_rand(1,10000) . time()) . '.jpg';
		if (!move_uploaded_file($fileInfo['tmp_name'], $filePath)) {
			$this->error('上传图片发生错误');
			return;
		}
		$goods = new GoodsModel();
		if($goods->create()) {
			$goods->art_path = $filePath;
			if($goods->add()) {
				$this->success('添加商品成功',2,'getList');
			} else {
				$this->error('添加商品失败',2);
			}
		} else {
			$this->error('表单提交发生错误',2);
		}
	}
	
	public function delete() {
		$gid = $_GET['gid'];
		$goods = new GoodsModel();
		if($goods->delete($gid)){
			$this->success('删除成功',2,'getList');
		}else {
			$this->error('删除失败',2);
		}
	}
}