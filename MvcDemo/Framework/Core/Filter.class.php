<?php

namespace Framework\Core;

/**
 * 过滤器父类
 */
abstract class Filter {
	// 要过滤的action名称
	protected $action = '';
	
	public function __construct($action) {
		$this->action = $action;
	}
	
	/**
	 * 执行对控制器操作方法的过滤
	 * 
	 * @return true 表示可以继续执行控制器的操作
	 */
	public abstract function run();
	
	/**
	 * 获取POST请求参数
	 * 
	 * @param string $name
	 *        	参数名
	 */
	public function postParam($name) {
		if (isset ( $_POST [$name] )) {
			return htmlspecialchars ( stripslashes ( trim ( $_POST [$name] ) ) );
		}
		return '';
	}
	
	/**
	 * 获取GET请求参数
	 * 
	 * @param string $name
	 *        	参数名
	 */
	public function getParam($name) {
		if (isset ( $_GET [$name] )) {
			return htmlspecialchars ( stripslashes ( trim ( $_GET [$name] ) ) );
		}
		return '';
	}
	
	/**
	 * 重定向
	 */
	public function redirect($url) {
		header ( 'Location:' . $url );
	}
	
	/**
	 * 创建JSONResponse对象，并返回，作为ajax请求的响应内容
	 *
	 * @param mixed $resp
	 */
	public function ajaxResponse($resp) {
		return new JSONResponse($resp);
	}
	
	/**
	 * 操作执行成功之后的跳转操作
	 * 
	 * @param string $msg 提示信息
	 * @param string $url 跳转链接
	 * @param $isAjax bool true表示需要ajax响应信息
	 */
	public function success($msg = '操作成功', $url = '', $isAjax = false) {
		if ($isAjax) {
			$data = array(
					'msg' => $msg,
					'url' => $url
			);
			$this->ajaxResponse($data)->render();
			return;
		}
		
		$data = array(
				'message' => $msg,
				'url' => $url
		);
		require_once 'Framework/success.view.php';
	}
	
	/**
	 * 操作执行失败之后的跳转操作
	 *
	 * @param string $msg 提示信息
	 * @param string $url 跳转链接
	 * @param $isAjax bool true表示需要ajax响应信息
	 */
	public function error($msg = '操作成功', $url = '', $isAjax = false) {
		if ($isAjax) {
			$data = array(
					'msg' => $msg,
					'url' => $url
			);
			$this->ajaxResponse($data)->render();
			return;
		}
		
		$data = array(
				'message' => $msg,
				'url' => $url
		);
		require_once 'Framework/fail.view.php';
	}
}