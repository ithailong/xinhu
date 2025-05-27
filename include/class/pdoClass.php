<?php
include_once('mysql.php');
class pdoClass extends mysql{
	
	
	protected function connect()
	{
		$this->errormsg	= '';
		if(!class_exists('PDO'))exit('操作数据库的php的扩展PDO不存在');
		try {
			$this->conn = @new PDO('mysql:host='.$this->db_host.';dbname='.$this->db_base.'', $this->db_user, $this->db_pass);
			$this->conn->query("SET NAMES 'utf8'");
			$this->selectdb($this->db_base);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			$this->conn 	= null;
			$this->errormsg = $e->getMessage();
		}
	}
	
	protected function querysql($sql)
	{
		try {
			$bo = $this->conn->query($sql);
		} catch (PDOException $e) {
			$bo = false;
			$this->setError($e->getMessage(), $sql);
		}
		return $bo;
	}
	
	public function fetch_array($result, $type = 0)
	{
		$result_type = ($type==0)? PDO::FETCH_ASSOC : PDO::FETCH_NUM;
		return $result->fetch($result_type);
	}
	
	public function insert_id()
	{
		return $this->conn->lastInsertId();
	}
	
	protected function starttran()
	{
		$this->conn->beginTransaction();
	}
	
	protected function endtran($bo)
	{
		if(!$bo){
			$this->conn->rollBack();
		}else{
			$this->conn->commit();
		}
	}
		
	
	
	public function close()
	{
		if($this->conn==null)return;
		return $this->conn=null;
	}
}