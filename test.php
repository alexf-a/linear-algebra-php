<?php
include 'matrix.php';
include 'table_retrieval.php';
try {
	set_mysql_conn();
} catch (mysqli_sql_exception $e){
	print("\n MYSQLI CONNECTION FAILED WITH: \n");
	print($e);
	print("\n");
}

/*
$Adata = array(
	array(1, 2, 3),
	array(4, 5, 6),
	array(7, 8, 9),
	array(10, 11, 12)
);

$Bdata = array(
	array(51, 52, 34, 65, 67),
	array(89, 88, 54, 43, 25),
	array(34, 54, 65, 66, 76)
);
*/

$Adata = array(
	array(2, 2, 2,),
	array(2, 2, 2,)
);

$Bdata = array(
	array(2, 2),
	array(2, 2,),
	array(2, 2,)
);

$Cdata = array(
	array(2, 2),
	array(2, 2)
);

$A = table_to_matrix("test1");
$B = table_to_matrix("test1");
$C = table_to_matrix("test1", 1, 2);

echo 'PHP Version'.phpversion()."\n";
echo mmult($A, $B);
echo("\n \n");
echo($C);
echo mmult($B->getColumn(1), $C);

set_mysql_conn(False);

phpinfo();



?>