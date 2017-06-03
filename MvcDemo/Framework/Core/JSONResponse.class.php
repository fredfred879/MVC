<?php
namespace Framework\Core;

class JSONResponse implements Response {
	private $data;
	public function __construct($data) {
		$this->data = $data;
	}
	
	public function render() {
		header('Content-Type:text/json;Charset=utf-8');
		echo json_encode($this->data);
	}
}