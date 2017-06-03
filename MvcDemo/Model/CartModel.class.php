<?php
namespace Model;
use Framework\Core\Model;

class CartModel extends Model {
	protected $tableName = 'shop_cart';
	protected $pk = 'id';
}