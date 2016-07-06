<?php
include_once dirname(dirname(__FILE__)).'/Curso.php';
include_once dirname(__FILE__).'/DAO.php';
include_once dirname(dirname(__FILE__)).'/DBConnection/DBQuery.php';

class cursoDAO implements DAO {
	
	private $tabla = "Curso";
	private $campos= array("id","nivel","nombre");
	private $dataType= array(DBQuery::TYPENUMBER,DBQuery::TYPETEXT,DBQuery::TYPETEXT);
	private $query;
	
	public function __construct() {
		$this->query = new DBQuery();
	}
	
	public function save($curso) {
		if(!$curso instanceof Curso) 
			throw new Exception("El argumento debe ser una instancia de Curso");
		if( strlen( str_replace(" ", "",$curso->getNivel() ) ) == 0 )
			throw new Exception("El nivel del Curso no puede estar en blanco");
		if( strlen( str_replace(" ", "",$curso->getNombre() ) ) == 0 )
			throw new Exception("El nombre del Curso no puede estar en blanco");
		if($curso->getId()!=null) { 
			$dataTypeAux = $this->dataType;
			array_shift($dataTypeAux);
			//var_dump($this->tabla);
			return $this->query->update($this->tabla,
					array(
							$this->campos[1] => $this->query->getConnection()->real_escape_string($curso->getNivel()),
							$this->campos[2] => $this->query->getConnection()->real_escape_string($curso->getNombre())
					),
					"id = ".$this->query->getConnection()->real_escape_string($curso->getId()),
					$dataTypeAux
					);
		}
		else {
			$response = $this->query->select(array("*"),array($this->tabla),"nivel = '".$curso->getNivel()."' and nombre = '".$curso->getNombre()."'");
			//var_dump($response);
			if($response->num_rows > 0)
				throw new Exception("Ya existe un Curso con esos Datos");
			else 
				return $this->query->insert( $this->tabla,
						array(	
							$this->query->getConnection()->real_escape_string($curso->getId()),
							$this->query->getConnection()->real_escape_string($curso->getNivel()),
							$this->query->getConnection()->real_escape_string($curso->getNombre())
						),
						$this->campos,
						$this->dataType
				);
		}
	}
	public function delete($curso) {
		if(!$curso instanceof Curso)
			throw new Exception("El argumento debe ser una instancia de Curso");
		if( !( $curso->getId() > 0 ) )
			throw new Exception("No se especifica Id para eliminar");
		$where = "id = ".$this->query->getConnection()->real_escape_string($curso->getId());
		return $this->query->delete($this->tabla,$where);
				
	}
	//public function edit($curso) {return 0;}
	/*** Busca primero por ID, en caso de no existir el ID busca por nivel y curso 
	 * 
	 * @return 
	 * {@inheritDoc}
	 * @see DAO::fetch()***/
	public function fetch($curso) {
		//var_dump($curso);
		$cursos = array();
		$response = false;
		if(!$curso instanceof Curso)
			throw new Exception("El argumento debe ser una instancia de Curso");
		if(null != $curso->getId()) {
			if( $curso->getId() > 0 )
				$response = $this->query->select(array("*"),array($this->tabla),"id = ".$curso->getId());
		}	
		else if(null != $curso->getNombre()) {
			$response = $this->query->select(array("*"),array($this->tabla),"nombre like '%".$curso->getNombre()."%'");
		}
		else if(null != $curso->getNivel()) {
			$response = $this->query->select(array("*"),array($this->tabla),"nivel like '%".$curso->getNivel()."%'");
		}
		foreach($response as $r) {
			$curso = new Curso();
			$curso->setId($r[$this->campos[0]]);
			$curso->setNivel(htmlspecialchars($r[$this->campos[1]]));
			$curso->setNombre(htmlspecialchars($r[$this->campos[2]]));
			array_push($cursos,$curso);
		}
		return $cursos;
	}
	/*** devuelve un array con todos los cursos ***/
	public function fetchAll() {
		$cursos = array();
		$response = $this->query->select(array("*"),array($this->tabla),"true");
		foreach($response as $r) {
			$curso = new Curso();
			$curso->setId($r[$this->campos[0]]);
			$curso->setNivel(htmlspecialchars($r[$this->campos[1]]));
			$curso->setNombre(htmlspecialchars($r[$this->campos[2]]));
			array_push($cursos,$curso);
		}
		return $cursos;
	}
}