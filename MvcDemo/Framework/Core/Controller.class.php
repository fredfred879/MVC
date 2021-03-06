<?php
namespace Framework\Core;
use Framework\Core\view;
/**
 * 控制器父类
 */
class Controller {
	/**
	 * 获取POST请求参数
	 * @param string $name 参数名
	 */
	public function postParam($name) {
		if (isset($_POST[$name])) {
			return htmlspecialchars(stripslashes(trim($_POST[$name])));
		}
		return '';
	}
	
	/**
	 * 获取GET请求参数
	 * @param string $name 参数名
	 */
	public function getParam($name) {
		if (isset($_GET[$name])) {
			return htmlspecialchars(stripslashes(trim($_GET[$name])));
		}
		return '';
	}
	
	/**
	 * 重定向
	 */
	public function redirect($url) {
		header('Location:' . $url);
	}
	
	/**
	 * 创建一个View对象并返回
	 * 
	 * @param string $viewName
	 */
	public function view($viewName) {
		return new View($viewName);
	}
	
	/**
	 * 创建JSONResponse对象，并返回，作为ajax请求的响应内容
	 * 
	 * @param mixed $data
	 */
	public function ajaxResponse($data) {
		return new JSONResponse($data);
	}
	
	/**
	 * 操作执行成功之后的跳转操作
	 * 
	 * @param string $msg 提示信息
	 * @param string $url 跳转链接
	 * @param $isAjax bool true表示需要ajax响应信息
	 * @param int $count 跳转倒计时 
	 */
	public function success($msg = '操作成功', $url = '', $isAjax = false, $count = 3) {
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
				'count' => $count,
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
	 * @param int $count 跳转倒计时 
	 */
	public function error($msg = '操作成功', $url = '', $isAjax = false, $count = 3) {
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
				'count' => $count,
				'url' => $url
		);
		require_once 'Framework/fail.view.php';
	}
}