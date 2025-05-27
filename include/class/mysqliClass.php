<?php
include_once('mysql.php');
class mysqliClass extends mysql{
	
	
	protected function connect()
	{
		$this->errormsg	= '';
		if(!class_exists('mysqli'))exit('操作数据库的php的扩展mysqli不存在');
		$this->conn = @new mysqli($this->db_host,$this->db_user, $this->db_pass, $this->db_base);
		if (mysqli_connect_errno()) {
			$this->conn 	= null;
			$this->errormsg	= mysqli_connect_error();
		}else{
			$this->selectdb($this->db_base);
			$this->conn->query("SET NAMES 'utf8'");
		}
	}
	
	protected function querysql($sql)
	{
		$roboll = $this->conn->query($sql);
		if(!$roboll)$this->setError($this->conn->error, $sql);
		return $roboll;
	}
	
	public function fetch_array($result, $type = 0)
	{
		$result_type = ($type==0)?MYSQLI_ASSOC:MYSQLI_NUM;
		return $result->fetch_array($result_type);
	}
	
	public function insert_id()
	{
		return $this->conn->insert_id;
	}
	
	protected function starttran()
	{
		$this->conn->autocommit(FALSE);
	}
	
	protected function endtran($bo)
	{
		if(!$bo){
			$this->conn->rollback();
		}else{
			$this->conn->commit();
		}
	}
	
	public function getallfields($table)
	{
		$sql	= 'select * from '.$table.' limit 0,0';
		$result	= $this->query($sql);
		if(!$result)return array();
		$finfo 	= $result->fetch_fields();
		foreach ($finfo as $val) {
			$arr[] = $val->name;
		}
		return $arr;
	}
	
	
	public function close()
	{
		if($this->conn==null)return;
		return $this->conn->close();
	}
}