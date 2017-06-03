<?php

namespace Model;
use Framework\Core\Model;

class CategoryModel extends Model{
	protected $tableName = 'shop_category';
	protected $pk = 'cid';
	
	/**
	 * 新增类别信息
	 * 
	 * @return true表示新增成功
	 */
	public function addCategory() {
		// 指示事务当中的操作是否正常
		$isAddSucc = false; 
		$isUpdateSucc = false;
		
		// 在一个事务中执行
		$this->beginTransaction();
		// 先新增
		$data = $this->data();
		$isAddSucc = $this->add();
		// 获取新增数据的cid
		$maxId = $this->select('max(cid) as maxId')[0]['maxId'];
        // 获取新增数据的父类别的path
		$parentPath = '0';
		$rows = $this->where('cid=?',array($data['parent_id']))->select('path');
		
		if ($rows != null && count($rows) > 0) {
			$parentPath = $rows[0]['path'];
		}
		// 新增类别的path＝父类path . 自身的id
        $path = $parentPath . '-' . $maxId;
        
        // 更新，更新新增类别数据的path字段
        $data = array(
        		'cid' => $maxId,
        		'path' => $path
        );
        $this->data($data);
        $isUpdateSucc = $this->update();
        
        if ($isAddSucc && $isUpdateSucc) {
        	     $this->commit();
        	     return true;
        } else {
        	     $this->rollback();
        }
		return false;
	}
	
	/**
	 * 查询，得到按类别层级整理后的类别列表
	 *
	 * @param string $fields
	 */
	public function getSortOutList($fields = '') {
		if ($fields != '' && !strpos($fields,'path')) {
			// echo '查询字段必须包含path';
			return null;
		}
		
		$list = $this->orderBy('path')->select($fields);
		$sortOutList = array();
		foreach ($list as $ctgr) {
			$path = $ctgr['path'];
			$level = count(explode('-', $path)) - 2;
			$ctgr['cname'] = str_repeat('--', $level) . $ctgr['cname'];
		    array_push($sortOutList, $ctgr);
		}
		return $sortOutList;
	}
	
	/**
	 * 查询，得到按类别层级整理后的类别列表
	 *
	 * @param string $fields        	
	 */
	public function getSortOutList2($fields = '') {
		$list = $this->select ( $fields );
		return $this->sortOut ( $list );
	}
	
	/**
	 * 对原始类别列表，按层级整理
	 *
	 * @param array $originalList        	
	 * @param number $pid        	
	 * @param number $level        	
	 */
	private function sortOut($originalList, $pid = 0, $level = 0) {
		$sortOutList = array ();
		foreach ( $originalList as $ctgr ) {
			if ($ctgr ['parent_id'] == $pid) {
				$ctgr ['cname'] = str_repeat ( '--', $level ) . $ctgr ['cname'];
				$ctgr ['level'] = $level;
				array_push ( $sortOutList, $ctgr );
				$childList = $this->sortOut ( $originalList, $ctgr ['cid'], $level + 1 );
				$sortOutList = array_merge ( $sortOutList, $childList );
			}
		}
		return $sortOutList;
	}
}