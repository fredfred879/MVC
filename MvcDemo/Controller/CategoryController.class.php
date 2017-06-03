<?php
namespace Controller;
use Framework\Core\Controller;
use Model\CategoryModel;

class CategoryController extends Controller {
	public function getList() {
		$category = new CategoryModel();
		$ctgrList = $category->getSortOutList('cid,cname,parent_id,description,path');
	    return $this->view('Category/list')->with('ctgrList', $ctgrList);
	}
	
	public function add() {
		$category = new CategoryModel();
		$ctgrList = $category->getSortOutList('cid,cname,parent_id,description,path');
		return $this->view('Category/add')->with('ctgrList', $ctgrList);
	}
	
	public function doAdd() {
		$category = new CategoryModel();
		if($category->create()) {
			if($category->addCategory()) {
				$this->success('添加类别成功',2,'getList');
			} else {
				$this->error('添加新类别失败',2);
			}
		} else {
			$this->error('表单提交发生错误',2);
		}
	}
	
	public function delete() {
		$path = $_GET['path'];
		$ctgr = new CategoryModel();
		if ($ctgr->where('path like ?"%"',array($path))->delete()) {
			$this->success('删除类别成功',2);
		} else {
			$this->error('删除类别失败',2);
		}
	}
}
