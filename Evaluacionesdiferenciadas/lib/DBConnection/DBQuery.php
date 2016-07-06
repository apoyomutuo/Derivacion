<?php
include_once 'DBConnectionFactory.php';
class DBQuery {
	
	const TYPETEXT = "TEXT";
	const TYPEDATE = "DATE";
	const TYPENUMBER = "NUMBER"; 
	
	private $connection;
	
	public function __construct() {
		$DBF = DBConnectionFactory::getInstance();
		$this->connection = $DBF->getConnection();
	}
	
	public function select($field=array(),$from=array(),$where,$orderby="asc") {
		if( ( count($field) > 0 ) && ( count($from) > 0 ) ) {
			$sql = "select ".join(", ", $field)." from ".join(", ", $from)." where ".$where;
			//var_dump($sql);
			$response =  $this->connection->query($sql);
			if($response!=false) return $response;
		}
		else return false;
	}
	
	public function insert($table="",$values=array(),$fields=array(),$datatypes=array()) {
		/* QUITAR EL ID DEL INSERT PARA UTILIZAR EL ID AUTOINCREMENTAL */
		array_shift($values);
		array_shift($fields);
		array_shift($datatypes);
		if( $table=="" ) throw new Exception('No se especifica tabla para ingresar datos');
		if( count( $values ) <= 0 ) throw new Exception('No se especifican valores a ingresar en la tabla');
		if( ( count( $fields ) > 0 ) && ( count( $fields ) != count( $values ) ) )
			throw new Exception('la cantidad de campos no corresponde con la cantidad de valores a ingresar');		
		$insertsql = "insert into " . $table . "(" . 
						join(",",$fields) 
					. ") values (" ;
		for( $i = 0 ; $i < count( $fields ) ; $i++ ) {
			if ( $i > 0 )
				$insertsql .= ',';
			switch ($datatypes[$i]) {
				/* esto hay que corregirlo (pasar las fechas a formatro de fecha y los textos 
				 * filtrarlos para evitar sqlinjection
				 */
				case DBQuery::TYPETEXT:		$insertsql .= "'$values[$i]'";
											break;
				
				case DBQuery::TYPENUMBER:	$insertsql .= $values[$i];
											break;

				case DBQuery::TYPEDATE:		$insertsql .= $values[$i];
											break;											
											
			}
		}
		$insertsql .= ")";
		//var_dump($insertsql);
		$response = $this->connection->query($insertsql);
		return $response;
	}
	
	/**
	 * 
	 * @param string $table Nombre de la tabla que contiene los datos en la BD
	 * @param array $values Valores en formato ej:array( campo => valor , campo =>valor, ... )
	 * @param string $condition condicion en formato de texto ej: "id=7" 
	 * @param array $datatypes array con tipo de datos de los campos ($values) a actualizar ej: array("TYPETEXT","TYPEDATE","TYPENUMBER")
	 * @throws Exception si no se especifica argumento tabla o valores validos (not null o vacios)
	 */
	public function update($table, $values = array(),$condition ="",$datatypes=array()) {
		if( ($table== null) || ($table=="") )
			throw new Exception('No se especifico la tabla a actualizar');
		if( count( $values ) <= 0 )
			throw new Exception('no se especifican valores a actualizar');
		$updatesql = "update ".$table." set ";
		$i=0;//para reccorrer los tipos de datos de los valores
		foreach ($values as $campo => $valor) {
			if ( $i > 0 )
				$updatesql .= ', ';
			$updatesql .= "$campo = ";
			switch ($datatypes[$i]) {
					/* TODO: corregir revisar actualizacion de fechas formato correcto
					 */
					case DBQuery::TYPETEXT:		$updatesql .= "'".$this->connection->escape_string ($valor)."'";
					break;
		
					case DBQuery::TYPENUMBER:	$updatesql .= $valor;
					break;
		
					case DBQuery::TYPEDATE:		$updatesql .= $valor;
					break;
			}	
			$i++;
		}
		$updatesql .= " where ".$condition;
		//var_dump($updatesql);exit(0);
		return $this->connection->query($updatesql);
	}
	public function delete($table,$where) {
		if( !( strlen($table) > 0 ) )
			throw new Exception("No se estableciò la tabla de cual borrar el registro");
		$deleteQuery = "delete from ".$table." where ".$where;
		return $this->connection->query($deleteQuery);
		
	}
	
	public function getConnection() {
		return $this->connection;
	}
}