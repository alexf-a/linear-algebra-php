<?php

/**
 * A class to represent a vector math structure.
 * NOTE: Indices begin at 1 (as is conventional in math). 
 */
class Vector implements ArrayAccess {
	protected $values;
	
	/**
	 * Construct a new Vector with $values as its point. 
	 * @param array of int|array of float $values The values for this Vector.
	 */
	function __construct($values){
		$this->values = $values;
	}
	
	function __toString(){
		return implode(", ", $this->values);
	}
	
	/** Array Access methods https://secure.php.net/manual/en/class.arrayaccess.php **/
	public function offsetSet($offset, $value){
		if (is_null($offset)){
	        $this->values[] = $value;
	        return;
	    }
	    if (!is_int($offset)){
			throw new InvalidArgumentException("Vector indices must be integers");
		}
		if (($offset > count($this->values) + 1) ){
			throw new OutOfBoundsException("You can only append to, or alter existing values of, a Vector.");
		}
		if ($offset < 1) {
			throw new OutOfBoundsException("All indices must be greater than 0");
		}
		if (is_null($offset)){
			throw new DimensionException("Cannot append to a vector. Vectors are of fixed dimensions");
		}
		
		
		$this->values[$offset-1] = $value;
	}
	
	public function offsetExists($offset){
		return isset($this->values[$offset-1]);
	}
	
	public function offsetUnset($offset){
		unset($this->values[$offset-1]);
	}
	
	public function offsetGet($offset){
		return isset($this->values[$offset-1]) ? $this->values[$offset-1]:null;
	}
	
	/**End Array Access methods **/
	
	/**
	 * Return the dimensions of this Vector.
	 * @return int The number of dimensions of this vector.
	 */
	public function numDimensions(){
		return count($this->values);
	}
	
	
	/**
	 * Return this vector's point as an array of values.
	 * @return array of int|array of float This vector's point.
	 */
	public function getPoint(){
		return $this->values;
	}
	
	/**
	 * Return this Vector's length (magnitude, in other words).
	 * @return int|float This Vector's length.
	 */
	public function getLength(){
		$result = 0;
		for ($i = 0; $i < count($this->values); $i++){
			$result = $result + pow($this->values[$i],2);
		}
		return sqrt($result);
	}
	
	/**
	 * Multiply this Vector by scalar $a.
	 * @param int|float $a Scalar to multiply this Vector by.
	 */
	public function scalarMultiply($a){
		for ($i = 0; $i < count($this->values); $i++){
			$this->values[$i] = $this->values[$i]*$a;
		}
	}
}
/*
 * A class to represent a matrix math structure.
 * NOTE: Index numbering follows math convention (all indices begin at 1).
 * Please use custom setters and getters for setting/getting cells, rows and columns.
 */
class Matrix implements ArrayAccess {
	protected $data;
	/*
	 * Construct a new matrix with $data as its data. Assumes consistency of row/column dimensions.
	 * @param 2D array of int| 2D array of float $data
	 * @throws DimensionException if rows/columns of unequal length.
	 */
	function __construct($data){
		$l = count($data[0]);
		for ($i = 0; $i < count($data); $i++){
			if (count($data[$i]) != $l){
				throw new DimensionException("Cannot construct a Matrix with inconsistent dimensions");
			}
		}
		$this->data = $data;
	}
	
	public function __toString(){
		if ($this->numRows() == 1 && $this->numColumns() == 1){
			return strval($this->data[0][0]);
		}
		$result = "";
		for ($i = 0; $i < count($this->data); $i++){
			$result = $result.implode(", ", $this->data[$i])."\n"; 
		}
		return $result;
	}
	
	/*
	 * Multiply this matrix by scalar $a.
	 * @param int|float $a Scalar to multiply by.
	 */
	public function scalarMultiply($a){
		if (!(is_int($a) || is_float($a))){
			throw new InvalidArgumentException("Scalar must be a number");
		}

		for ($i = 0; $i < count($this->data); $i++){
			for ($j = 0; $j < $this->numColumns(); $j++){
				$this->data[$i][$j] = $this->data[$i][$j]*$a;
			}
		}	
	
	}
	
	/**
	 * Return the number of rows in this Matrix.
	 * @return int The number of rows in this Matrix.
	 */
	public function numRows(){
		return count($this->data);
	}
	
	/**
	 * Return the number of columns in this Matrix.
	 * @return int The number of columns in this Matrix.
	 */
	public function numColumns(){
		return count($this->data[0]);
	}
	
	/**
	 * return row $i of this Matrix as a Vector. 
	 * Assumign $i > 0.
	 * @return Vector row $i of this Matrix.
	 * @param int $i the index of the row.
	 * @throws OutOfBoundsException if $i < 1. 
	 */
	public function getRow($i){
		if ($i <=0){
	 		throw new OutOfBoundsException("Matrix indices must be greater than 0");
	 	}	 
		return new Vector($this->data[$i-1]);
	}
	
	/**
	 * return column of this Matrix as a Vector. 
	 * Assumign $j > 0.
	 * @return Vector column $j of this Matrix.
	 * @param int $j the index of the column.
	 * @throws OutOfBoundsException if $j < 1. 
	 */
	public function getColumn($j){
	 if ($j <=0){
	 	throw new OutOfBoundsException("Matrix indices must be greater than 0");
	 } 
	 $result = array();
	 for ($i = 0; $i < count($this->data); $i++){
	 	$result[] = $this->data[$i][$j-1];
	 }
	 return new Vector($result);
	}
	
	/**
	 * return element at row $i, column $j of this Matrix.
	 * Assuming $i, $j > 0. In other words, using Linear Algebra indexing.
	 * @return int|float element at row $i, column $j.
	 * @param int $i
	 * @param int $j
	 */
	public function getCell($i, $j){
		if ($i < 1 || $j < 1){
		 throw new OutOfBoundsException("Make sure all Matrix indexes are greater than 0.");
		}
		return $this->data[$i-1][$j-1];
	}
	
	/**
	 * set the cell at row i, column j to val of this Matrix.
	 * Assuming $i, $j > 0. In other words, using Linear Algebra indexing.
	 * @param int $i
	 * @param int $j
	 * @param int val new value at row $i, col $j.
	 */
	public function setCell($i, $j, $val){
		if ($i < 1 || $j < 1){
		 throw new OutOfBoundsException("Make sure all Matrix indexes are greater than 0.");
		}
		$this->data[$i-1][$j-1] = $val;
	}
	
	/**
	 * Set row $i to $data.
	 * @param int $i The row to change.
	 * @param int array|float array $data Array of values to change row to.
	 */
	public function setRow($i, $data){
		$this->data[$i-1] = $data;
	}
	
	/**
	 * Set column $j to $data.
	 * @param int $j The column to change.
	 * @param int array|float array $data Array of values to change column to.
	 */
	public function setColumn($j, $data){
		$j = $j-1;
		for ($i = 0; $i < count($this->data); $i++){
			$this->data[$i][$j] = $data[$i];
		}
	}
	
	/**
	 * Array Access methods
	 */
	public function offsetSet($offset, $value){
		throw new Exception("Please use setCell() or setRow() for changing Matrix values");
	}
	
	public function offsetGet($offset){
		return isset($this->data[$offset-1]) ? new Vector($this->data[$offset-1]):null;
	}
	
	public function offsetUnset($offset){
		unset($this->data[$offset-1]);
	}
	
	public function offsetExists($offset){
		return isset($this->data[$offset-1]);
	}
	/** End Array Access methods **/
	
	
}
class DimensionException extends Exception { 

}
/*
 * Return the matrix product of M1, M2, ... , Mn in matrices.
 * @return A Matrix that is the product of M1, ... , Mn. 
 * @param Matrix $M1 Matrix to left multiply $M2 by.
 * @param Matrix $M2 Matrix to right multiply $M1 by. 
 * @param mixed $M3, ... unlimited OPTIONAL number of additional matrices.
 * @throw a DimensionException if (M1 * ... * Mi) and Mi+1 have incompatible dimensions.
 */
function mmult($M1, $M2){
	
	$matrices = func_get_args();
	$i = 1;
	foreach ($matrices as $M){
		if ($i == 1){
			$A = $M;
		} else {
			$A = _mmult($A, $M);
		}
		$i+=1;
	}
	return $A;
}

function _mmult($A, $B){
	//$A is mXn
	if ($A instanceof Vector){
		$m = 1;
		$n = $A->numDimensions();
	} else {
		$m = $A->numRows();
		$n = $A->numColumns();
	}
	//$B is pXq
	if ($B instanceof Vector){
		$p = $B->numDimensions();
		$q = 1;
	} else {
		$p = $B->numRows();
		$q = $B->numColumns();
	}
	
	$data = [];
	if ($n != $p){
		throw new DimensionException("Matrix multiplication failed because of mismatched dimensions");
	}
	
	for($i = 1; $i <= $m; $i++){
		$row = [];
		for($j = 1; $j <= $q; $j++){
			$row[] = dotProduct(
						$A instanceof Vector ? $A:$A->getRow($i), 
						$B instanceof Vector ? $B:$B->getColumn($j)
						);
		}
		$data[] = $row;
	}
	
	return new Matrix($data);
}

/**
 * Return the dot product of Vectors $p and $q.
 * @return int the vector product of $p and $q.
 * @param Vector $p A vector of equal length to $q.
 * @param Vector $q A vector of equal length to $p.
 * @throws DimensionException of $p and $q unequal lengths.
 */
function dotProduct($p, $q){
	$m = $p->numDimensions();
	$n = $q->numDimensions();
	if ($m != $n){
		throw new DimensionException("Vector multiplication failed because of mismatched dimensions");
	}
	$result = 0;
	for ($i = 1; $i <= $m; $i++) {
		$result = $result + $p[$i]*$q[$i];
	}
	return $result;
}


?>