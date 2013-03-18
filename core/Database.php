<?php
Class Database{

	/**
	* Boolean representing link status
	* @var string
	*/ 
	public $link = false;

	/**
	* Host string
	* @var string
	*/ 
	private $host = 'localhost';

	/**
	* The database username string
	* @var string
	*/ 
	private $user = 'user';

	/**
	* The password string
	* @var string
	*/ 
	private $password = 'pass';

	/**
	* String representing the databasename
	* @var string
	*/ 
	private $dbname = 'dbname';

	/**
	* Databse class constructor
	*
	* Connects to the database
	*/
	public function __construct(){
		$this->link = @new mysqli($this->host, $this->user, $this->password, $this->dbname);

		if($this->link->connect_errno){
		    trigger_error("Error connecting: ". $this->link->connect_error ." (#". $this->link->connect_errno .")");
		}
	}

	/**
	* Read query
	*
	* Function that reads the query and returns the results
	* @param string (sql query)
	*/			
	public function read($strSql){
		//	Results from the query
		$arrResult = array();

		//	Execute query
		$objQuery = $this->link->query($strSql);
		
		//	Check for invalid query
		if ($objQuery === false || $objQuery->num_rows == 0){
			return false; //Early abort
		}

		
		while($objRow = $objQuery->fetch_array()){
			$arrResult[] = $objRow;
		}

		//	Free memory
		$objQuery->free();

		return $arrResult;
	}

	/**
	* Insert data to database
	*
	* Function that inserts data to the database
	* @param array list of data to insert, keys equal database field
	* @param string tablename to insert into
	* @param string return id or not
	*/
	public function insert($arrData, $strTable, $boolId = false){
		$arrValues = array(); // db values
		$arrFields = array(); // db fields to fill

		foreach($arrData as $key => $val){
			$arrFields[] = "$key";
			$val = addslashes($val);
			$arrValues[] = "'" . $this->link->real_escape_string($val) . "'";
		}
		
		$strFields = implode(",",$arrFields);
		$strValues = implode(",",$arrValues);
		
		$strQuery = "INSERT INTO `" . $strTable . "` ($strFields) VALUES ($strValues)";
		
		if($this->execute($strQuery)){
			if($boolId == true){
				return $this->link->insert_id;
			}else{
				return true;	
			}
		}else{
			return false;
		}

	}

	/**
	* Insert data to database
	*
	* Function that inserts data to the database
	*
	* @param array list of data to insert, keys equal database field
	* @param string tablename to insert into
	* @param string key column to insert 
	* @param key column value
	*/
	public function update($arrData,$strTable,$strKeyColumn, $keyValue){
		//	array holding the values
		$arrValues = array();

		foreach($arrData as $key => $val){
			$arrValues[] = "`$key` = '" . $this->link->real_escape_string($val) . "'";
		}
		
		//	values
		$strValues = implode(",",$arrValues);	

		//	string to execute
		$strQuery = "UPDATE `" . $strTable 
				. "` SET " . $strValues 
				. " WHERE `$strKeyColumn` = '" 
				. $this->link->real_escape_string($keyValue) 
				. "'";
		
		return $this->execute($strQuery);
	}

	/**
	* Delete data from database
	*
	* Function that removes data to the database
	*
	* @param string tablename to insert into
	* @param string key column to insert 
	* @param key column value
	* @param int limit
	*/
	public function delete($strTable,$strKeyColumn,$keyValue, $limit = 1){
		$q = "DELETE FROM `$strTable` WHERE `$strTable`.`$strKeyColumn` = "
			. $this->link->real_escape_string($keyValue); 
		
		return $this->execute($q);
	}

	/**
	* Execute a query on the database
	*
	* Function that removes data to the database
	*
	* @param string query to execute
	*/
	public function execute($strSql){	
		return $this->link->query($strSql);
	}

	/**
	* Escape values and return
	*
	* @param string value to escape
	*/
	public function escape($strVal){
		return $this->link->real_escape_string($strVal);
	}
}
