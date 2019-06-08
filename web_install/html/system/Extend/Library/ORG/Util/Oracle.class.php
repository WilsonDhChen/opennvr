<?php

/**
 * Simple Oracle  Connect Driver
 *	
 */

class Oracle {
	
	private $conn;
	
	private $stmt;
	
	
	public function connect($username,$password,$db=NULL){
	
		$this->conn = oci_connect($username,$password,$db,'UTF8');
		if($this->conn) {
			return true;
		}else{
			return false;	
		}
	}
	
	//执行一条SQL语句 delete,insert,update 返回影响记录条数，select 返回0，因此此方法不适合select查询
	public function execute($sql,$mode=OCI_COMMIT_ON_SUCCESS){
		
		//sql oci_parse
		$this->parse($sql);
		//execute
		$result = oci_execute($this->stmt,$mode);
		if($result){
			return oci_num_rows($this->stmt);
		}else{
			return false;	
		}
	}
	
	//查询多条记录
	public function select($sql){
		
		$result = $this->execute($sql);	
		if($result===false){
			return false;	
		}
		
		$rows = array();
		while ($row = oci_fetch_array($this->stmt, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$rows[] = $row;
		}
		
		return $rows;		
		
	}
	
	//查询单条记录
	public function find($sql){
		$result = $this->execute($sql);		
		if($result===false){
			return false;	
		}
		return oci_fetch_array($this->stmt, OCI_ASSOC+OCI_RETURN_NULLS);			
	}
	
	
	public function error(){
		if(empty($this->conn)){
			return oci_error();	
		}else{
			return oci_error($this->conn);		
		}
	}
	
    public function __destruct(){
		oci_free_statement($this->stmt);
		$this->stmt = NULL;
		oci_close($this->conn);
    }
	
	protected function parse($sql){
		
		$this->stmt = oci_parse($this->conn,$sql);	
	}		

}