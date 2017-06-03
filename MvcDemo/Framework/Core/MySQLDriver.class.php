<?php
namespace Framework\Core;

class MySQLDriver {
	private static $instance = null;
	
	private $config;
	
	private $connection = null;
	
	private function __construct(){
		$this->config = require 'config/db.php';
	}
	
	public static function getInstance() {
		if(self::$instance == null){
			self::$instance = new MySQLDriver();
		}
		return self::$instance;
	}
	
/**
	 * 创建数据库链接
	 */
	private function connect() {
		if($this->connection == null){
			$config = $this->config;
			$host = $config['host'];
			$db = $config['db'];
			$user = $config['user'];
			$password = $config['password'];
			$port = $config['port'];
			$dsn = "mysql:host=$host:$port;dbname=$db;";
			try{
				$this->connection = new \PDO($dsn,$user,$password);
			}catch (\PDOException $e){
				echo $e->getMessage();exit();
				
			}
		}
	}
	
	/**
	 * 开启事务执行
	 */
	public function beginTransaction() {
		$this->connect ();
		$this->connection->beginTransaction ();
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
		$this->connect ();
		$stmt = $this->connection->prepare ( $sql );
		if (isset ( $args ) && ! empty ( $args )) {
			for($i = 0; $i < count ( $args ); $i ++) {
				$stmt->bindParam ( $i + 1, $args [$i] );
			}
		}
	
		return $stmt->execute();
	}
	
	/**
	 * 提交事务
	 */
	public function commit() {
		if ($this->connection && $this->connection->inTransaction ()) {
			$this->connection->commit ();
		}
	}
	
	/**
	 * 回滚事务
	 */
	public function rollback() {
		if ($this->connection && $this->connection->inTransaction ()) {
			$this->connection->rollBack ();
		}
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
		$this->connect ();
	
		$stmt = $this->connection->prepare ( $sql );
		if (isset ( $args ) && ! empty ( $args )) {
			for($i = 0; $i < count ( $args ); $i ++) {
				$stmt->bindParam ( $i + 1, $args [$i] );
			}
		}
		$isSucc = $stmt->execute ();
		$data = array ();
		while ( $row = $stmt->fetch ( \PDO::FETCH_ASSOC ) ) {
			$data [] = $row;
		}
		return $data;
	}
}