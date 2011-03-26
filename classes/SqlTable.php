<?php
/*
 * SqlTable.php
 * 
 * Class that allows for easy table creation in MySql.
 * 
 * Litter is coded by Scott VonSchilling. He needs a job. If you like
 * what you see, please email scottvonschilling [at] gmail [dot] com.
 * 
 */
class SqlTable {
	protected $name;
	protected $subName;
	
	protected $cols = array();
	
	function __construct($name, $subName = NULL){
		$this->name = $name;
		$this->subName = $subName;
	}
	
	public function addCol ($name, $type, $null = FALSE, $primary = FALSE){
		$col = array("name"=>$name, 
		             "type"=>$type, 
					 "null"=>$null,
					 "primary"=>$primary);
		array_push($this->cols, $col);
	}
	
	public function createTable($sql){
		$s = "CREATE TABLE ".$this->name;
		$this->subName ? $s .= "_".$this->subName : $s .= "";
		$s .= " (";
		$isFirst = TRUE;
		foreach ($this->cols as $c){
			$isFirst ? $isFirst = FALSE : $s.=", ";
			$s .= $c["name"];
			$s .= " ".$c["type"];
			$c["null"] ? $s .= "" : $s .= " NOT NULL";
			$c["primary"] ? $s .= " PRIMARY KEY" : $s .= "";
		}
		$s.=")";
		mysql_query($s, $sql);
	}
	
}

