<?php
//Fill in appropriate values for HOST, USER and PASSWORD
define('HOST', 'localhost:8889');
define('USER', 'root');
define('PASSWORD', 'root');
define('DBNAME', 'somerville_test');
$conn = NULL;
//EXAMPLE retrieval of table name from HTML form
	//NOTE: "table_name" refers to the "name" attribute of the HTML input element


//Create new MySQL connection
/**
 * sets the global conn variable to:
 * - new MySQL connection with HOST, USER, PASSWORD, DBNAME if open = True.
 * - closed connection if open = False.
 * throws any mysqli_sql_exception thrown by mysqli constructor.
 * @param boolean $open Whether to open or close MySQL connection.
 * @throws any mysqli_sql_exception thrown by mysqli constructor.
 */
function set_mysql_conn($open=True){
	if ($open){
		try {
		$GLOBALS['conn'] = new mysqli(HOST, USER, PASSWORD, DBNAME);
		} catch (mysqli_sql_exception $e){
			throw $e;
		}
	} else {
		$GLOBALS['conn']->close();
	}
}


/**
 * return a Matrix constructed from an SQL table
 * with table_name name, if table is retrieved, and optionally composed of only cols columns.
 * @return Matrix the new Matrix constructed from SQL data.
 * @return NULL if query fails.
 * @param string $table_name Name of SQL table.
 * @param mixed $cols, ... unlimited OPTIONAL string column names from SQL table.
 * 
 */
function table_to_matrix($table_name){
	$cols = func_get_args()[1:];
	//Construct query string from input
	$c = "";
	if (count($cols) == 0){
		$c = "*";
	} else {
		foreach ($cols as $col){
			$c = $c.$col.", ";
		}
		$c = substr($c, 0, -2);
	}
	$query = sprintf("SELECT %s FROM %s", $c, $table_name);
	//Cache data from SQL database.
	$data = [];
	$result = $GLOBALS['conn']->query($query);
	if($result){
		while($row = $result->fetch_array(MYSQLI_NUM)){
			$data[] = $row;
		}
		return new Matrix($data);
	}
} 

//Save mmult(m1, ..., mn) to db.
?> 