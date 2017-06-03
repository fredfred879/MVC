<?php
namespace Framework\Core;

class View implements Response {
	private $viewName = '';
	private $data = array();
	
	public function __construct($viewName) {
		$this->viewName = $viewName;
		
	}
	
	public function with($key, $value) {
		$this->data[$key] = $value;
		return $this;
	}
	
	public function render() {

		$data = $this->data;
		header('Content-Type:text/html;Charset=utf-8');
		require_once 'View/' . $this->viewName . '.view.php';
	}
}