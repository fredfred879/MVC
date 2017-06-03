<?php
namespace Filter;
use Framework\Core\Filter;

class RoleAuthFilter extends Filter {
	public function run() {
		if (isset($_SESSION['curr_user']) && !empty($_SESSION['curr_user'])) {
			$user = $_SESSION['curr_user'];
			if ($user['role_id'] != 1) {
				$this->error('权限不足',2,'../goods/getList');
				return false;
			}
		} else {
			$this->redirect('../goods/getList');
			return false;
		}
		return true;
	}
}