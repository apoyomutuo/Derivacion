<?php
class DBConnectionFactory {
	private static $instance;
	private $connection;
	
	private function __construct() {
		$this->connection = new mysqli('127.0.0.1', 'root', '', 'diferencial');
	}
	
	public static function getInstance() {
		if( !self::$instance instanceof self) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function getConnection() {
		return $this->connection;
	}
}