<?php
namespace Framework\Core;

/**
 * 实现了AR(Active Record)模式的ORM的数据库操作类，模型类
 *
 * @author Lee
 */
class Model {
	private $driver;
	
	// 表名映射
	protected  $tableName = '';
	
	// 主键映射
	protected $pk = '';
	
	// 字段映射 (表当中的所有字段信息)
	private $fields = array ();
	
	// 映射对应的表的单行记录 (关联数组)
	private $records = array ();
	
	// sql查询语句的where条件 
	private $whereClause = '';
	
	// sql查询条件中的参数
	private $args = array();
	
	// sql查询order by
	private $orderBy = '';
	
	// limit
	private $limitOffset = -1;
	private $limitSize = -1;
	
	public function __construct() {
		$this->driver = MySQLDriver::getInstance();
	}
	
	public function __get($name) {
		return $this->records [$name];
	}
	
	public function __set($name, $value) {
		$this->records [$name] = htmlspecialchars(stripslashes(trim($value)));
	}
	
	/**
	 * 魔术方法__call,通过该方法，模拟data方法的重载
	 * @param string $name 模拟的函数名
	 * @param array $args 参数数组
	 */
	public function __call($name,$args) {
		if ($name == 'data') {
			if (empty($args)) {
				return $this->records;
			}
			if (gettype($args[0]) == 'array') {
				$this->records = $args[0];
			}
		}
	}
	
	/**
	 * 从已有数组当中，创建实体对象，默认从表单提交的Post数据当中创建
	 * 
	 * @param array $data
	 * @return true表示创建成功 
	 */
	public function create($data = null) {
		if ($data == null) {
			$data = $_POST;
		}
		foreach ($data as $key => $value) {
			$this->records[$key] = htmlspecialchars(stripslashes(trim($value)));
		}
		return true;
	}
	
	/**
	 * 执行查询操作，返回二维数组形式的记录集合
	 * 
	 * @param string $fields 查询的字段列表,为空则查询所有字段
	 * @return 以二维数组形式返回查询到的记录集合
	 */
	public function select($fields = '') {
		if (trim($fields) == '') {
			$fields = $this->getFields ();
		}
		// 生成select语句
		$sql = 'SELECT' . ' ' . $fields . ' ' . 'FROM' . ' ' . $this->tableName;
		if (trim($this->whereClause) != '') {
			$sql .= ' ' . 'WHERE' . ' '. $this->whereClause;
		}
		if (trim($this->orderBy) != '') {
			$sql .= ' ' . 'ORDER BY' . ' ' .$this->orderBy;
		}
		if (($this->limitOffset > -1) && ($this->limitSize > 0)) {
			$sql .= ' ' . 'LIMIT' . ' ' . $this->limitOffset . ','. $this->limitSize;
		}
		// 执行查询 
		$data = $this->query($sql, $this->args);
		$this->whereClause = '';
		$this->orderBy = '';
		$this->limitOffset = -1;
		$this->limitSize = -1;
		$this->args = array();
		return $data;
	}
	
	/**
	 * 根据主键值，填充当前实体对象
	 * @param $pk 主键值
	 */
	public function get($pk) {
		$rows = $this->where($this->pk . '=?',array($pk))->select();
		if (!empty($rows) && count($rows) > 0) {
			$this->records = $rows[0];
			return $rows[0];
		}
		return null;
	}
	
	/**
	 * 把当前实体记录持久化，保存到对应的表当中
	 * 
	 * @return true表示新增成功 
	 */
	public function add() {
		$this->getFields();
		$columns = '';
		$values = '';
		$args = array();
		for ($i = 0; $i < count($this->fields); $i++) {
			$field = $this->fields[$i];
			if ($field['Extra'] != 'auto_increment' && isset($this->records[$field['Field']])) {
				if ($columns != '' && $values != '') {
					$columns .= ',';
					$values .= ',';
				}
				$columns .= $field['Field'];
				$values .= '?';
				array_push($args, $this->records[$field['Field']]);
			}
		}
		$sql = 'INSERT INTO' . ' ' . $this->tableName . ' ' . '(' . $columns . ')' . ' ' . 'VALUES(' . $values . ')';
		$isSucc = $this->execUpdate($sql,$args);
		if ($isSucc) {
			$this->records = array();
		}
		return $isSucc;
	}
	
	/**
	 * 更新当前实体记录
	 * 
	 * @return true表示更新成功
	 */
	public function update() {
		$this->getFields();
		$setStmt = '';
		$args = array();
		for($i = 0; $i < count($this->fields); $i++) {
			$field = $this->fields[$i];
			if($field['Extra'] != 'auto_increment' && isset($this->records[$field['Field']])) {
				if($setStmt != '') {
					$setStmt .= ',';
				}
				$setStmt .= $field['Field'] . '=' . '?';
				array_push($args, $this->records[$field['Field']]);
			}
		}
		$sql = "update $this->tableName set $setStmt where $this->pk=?";
		array_push($args, $this->records[$this->pk]);
		return $this->execUpdate($sql,$args);
	}
	
	
	/**
	 * 删除一条或多条记录
	 * 
	 * @param $pk 主键值
	 * @return true表示删除成功 
	 */
	public function delete($pk = '') {
		if (trim($pk) == '' && trim($this->whereClause) == '') {
			return false;
		}
		
		$sql = 'DELETE FROM' . ' ' . $this->tableName . ' ' . 'WHERE' . ' ';
		$args = array();
		if (trim($pk) != '') {
			$sql .= $this->pk . '=?';
			array_push($args, $pk);
		}
		if (trim($this->whereClause) != '') {
			$sql .= $this->whereClause;
			$args = array_merge($args,$this->args);
		}
		$isSucc = $this->execUpdate($sql,$args);
		$this->whereClause = '';
		$this->args = array();
		return $isSucc;
	}
	
	/**
	 * 指定SQL语句的where条件
	 * @param  $whereClause
	 * @param Array args 条件中的参数数组
	 * @return \Model\Model 返回当前对象引用
	 */
	public function where($whereClause, Array $args = null) {
		$this->whereClause = $whereClause;
		if ($args != null) {
			$this->args = $args;
		}
		return $this;
	}
	
	/**
	 * 指定排序列
	 * 
	 * @param string $orderBy
	 */
	public function orderBy($orderBy) {
		$this->orderBy = $orderBy;
		return $this;
	}
	
	/**
	 * limit
	 * 
	 * @param number $offset
	 * @param number $size
	 */
	public function limit($offset = 0, $size) {
		$this->limitOffset = $offset;
		$this->limitSize = $size;
		return $this;
	}
	
	/**
	 * 从对应的表当中获取所有的列（字段）信息
	 *
	 * @return 返回字段列表字符串,字段以逗号分割
	 */
	private function getFields() {
		// 从数据库中查询对应的表的字段信息
		if (empty ( $this->fields ) || count ( $this->fields ) == 0) {
			$sql = 'show columns from' . ' ' . $this->tableName;
			$this->fields = $this->query ( $sql );
		}
		$fieldsStr = '';
		for ($i = 0; $i < count($this->fields); $i++) {
			$field = $this->fields[$i];
			if ($fieldsStr != '') {
				$fieldsStr .= ',';
			}
			$fieldsStr .= $field['Field'];
		}
		return $fieldsStr;
	}
	
	/**
	 * 开启事务执行
	 */
	public function beginTransaction() {
		$this->driver->beginTransaction();
	}
	
	/**
	 * 执行增删改操作
	 *
	 * @param $sql string
	 *        	insert,update,delete语句 (预处理语句)
	 * @param array $args
	 *        	sql预处理语句中包含的参数，以数组形式按顺序排列,默认为null
	 * @return bool 执行成功返回true
	 */
	public function execUpdate($sql, $args = null) {
		return $this->driver->execUpdate($sql,$args);
	}
	
	/**
	 * 提交事务
	 */
	public function commit() {
		$this->driver->commit();
	}
	
	/**
	 * 回滚事务
	 */
	public function rollback() {
		$this->driver->rollback();
	}
	
	/**
	 * 执行查询语句，返回包含查询结果的二维数组
	 *
	 * @param string $sql
	 *        	select语句 (预处理语句)
	 * @param array $args
	 *        	预处理语句中的参数数组
	 * @return array 返回包含查询结果的二维数组
	 */
	public function query($sql, Array $args = null) {
		return $this->driver->query($sql,$args);
	}
}