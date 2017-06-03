<?php
const EXT = '.class.php';
function __autoload($class) {
	$fileName = str_replace ( "\\", "/", $class ) . EXT;
	if (file_exists ( $fileName )) {
		require_once $fileName;
	}
}

session_start ();

// 分析url，得到控制器和操作名称部分
$url = preg_replace ( '/\?.*/', '', $_SERVER ['REQUEST_URI'] );
$urlParts = explode ( '/', $url );
$ctrlName = ucfirst ( $urlParts [count ( $urlParts ) - 2] );
$actionName = $urlParts [count ( $urlParts ) - 1];


// 动态实例化控制器对象
$ctrlClsName = 'Controller\\' . $ctrlName . 'Controller';
$ctrlObj = new $ctrlClsName ();



// 执行action过滤器
$isFilterSucc = true;
$filterConfig = require 'config/filter.php';
$actionStr = $ctrlName . '/' . $actionName;

foreach ( $filterConfig as $fc ) {
	$actions = $fc ['actions'];
	$filterCls = $fc ['filter'];
	foreach ( $actions as $a ) {
		if (strtolower ( $a ) == strtolower ( $actionStr )) {
			$filter = new $filterCls ( $a );
			$isFilterSucc = $filter->run ();
			break;
		}
	}
}

// 调用操作方法，得到视图对象，渲染视图
if ($isFilterSucc) {

	$response = $ctrlObj->$actionName ();
	if ($response instanceof Framework\Core\Response) {

		$response->render ();

	} else {

		echo $response;
	}
}





