<?php
//
include_once dirname(dirname(__FILE__)).'/Alumno.php';
include_once dirname(__FILE__).'/DAO.php';
include_once dirname(dirname(__FILE__)).'/DBConnection/DBQuery.php';

class AlumnoDAO implements DAO {
	private $tabla ="usuario";
	private $campos = array("id", "rut", "nombres", "paterno", "materno", "curso_id");
	private $dataType= array(DBQuery::TYPENUMBER,DBQuery::TYPETEXT,DBQuery::TYPETEXT,DBQuery::TYPETEXT,DBQuery::TYPETEXT,DBQuery::TYPENUMBER);
	private $query;
	
	public function __construct() {
		$this->query = new DBQuery();
	}
	public function getCampos() {
		return $this->campos;
	}
	public function save($alumno) {
		if(!$alumno instanceof Alumno)
			throw new Exception("El argumento debe ser una instancia de Alumno");
		//TODO: falta validar rut, pero debe ser en el formulario, no aqui
		if( strlen( str_replace(" ", "",$alumno->getRut() ) ) == 0 )
			throw new Exception("El Rut del Alumno no puede estar en blanco");
		else if ( !$this->validaRut( $alumno->getRut() ) )
				throw new Exception("Rut no válido");
		if( strlen( str_replace(" ", "",$alumno->getNombres() ) ) == 0 )
			throw new Exception("El nombre del alumno no puede estar en blanco");
		if( strlen( str_replace(" ", "",$alumno->getPaterno() ) ) == 0 )
			throw new Exception("El apellido paterno del alumno no puede estar en blanco");	
		if( $alumno->getId() != null ) {
			$dataTypeAux = $this->dataType;
			array_shift($dataTypeAux);
			return $this->query->update($this->tabla,
					array(
							$this->campos[1] => $this->query->getConnection()->real_escape_string($alumno->getRut()),
							$this->campos[2] => $this->query->getConnection()->real_escape_string($alumno->getNombres()),
							$this->campos[3] => $this->query->getConnection()->real_escape_string($alumno->getPaterno()),
							$this->campos[4] => $this->query->getConnection()->real_escape_string($alumno->getMaterno()),
							$this->campos[5] => $this->query->getConnection()->real_escape_string($alumno->getCurso())
					),
					"id = ".$this->query->getConnection()->real_escape_string($alumno->getId()),
					$dataTypeAux
					);
		} else {
			$response = $this->query->select(array("*"),array($this->tabla),"rut = '".$alumno->getRut()."'");
			//var_dump($response);
			if( ( $response!=false ) && ($response->num_rows > 0 ) )
				throw new Exception("Ya existe un Alumno con esos Datos");
			else
				return $this->query->insert( $this->tabla,
						array(
								$this->query->getConnection()->real_escape_string($alumno->getId()),
								$this->query->getConnection()->real_escape_string($alumno->getRut()),
								$this->query->getConnection()->real_escape_string($alumno->getNombres()),
								$this->query->getConnection()->real_escape_string($alumno->getPaterno()),
								$this->query->getConnection()->real_escape_string($alumno->getMaterno()),
								$this->query->getConnection()->real_escape_string($alumno->getCurso())
						),
						$this->campos,
						$this->dataType
					);
		}
	}
	public function delete($alumno) {
		if(!$alumno instanceof Alumno)
			throw new Exception("El argumento debe ser una instancia de Alumno");
		if( !( $alumno->getId() > 0 ) )
			throw new Exception("No se especifica Id para eliminar");
		$where = "id = ".$this->query->getConnection()->real_escape_string($alumno->getId());
		return $this->query->delete($this->tabla,$where);
	}
	public function fetch($alumno) {
		//var_dump($alumno);
		$alumnos = array();
		$response = false;
		if(!$alumno instanceof Alumno)
			throw new Exception("El argumento debe ser una instancia de Alumno");
		if(null != $alumno->getId()) {
			if( $alumno->getId() > 0 )
				$response = $this->query->select(array("*"),array($this->tabla),"id = ".$alumno->getId());
		}	
		else if(null != $alumno->getRut()) {
			$response = $this->query->select(array("*"),array($this->tabla),"rut like '%".$alumno->getRut()."%'");
		}
		else if(null != $alumno->getNombres()) {
			$response = $this->query->select(array("*"),array($this->tabla),"nombres like '%".$alumno->getNombres()."%'");
		}
		else if(null != $alumno->getPaterno()) {
			$response = $this->query->select(array("*"),array($this->tabla),"paterno like '%".$alumno->getPaterno()."%'");
		}
		else if(null != $alumno->getMaterno()) {
			$response = $this->query->select(array("*"),array($this->tabla),"materno like '%".$alumno->getMaterno()."%'");
		}
		else if(null != $alumno->getCurso()) {
			$response = $this->query->select(array("*"),array($this->tabla),"curso_id = ".$alumno->getCurso());
		}
		else return false;
		foreach($response as $r) {
			$alumno = new alumno();
			$alumno->setId($r[$this->campos[0]]);
			$alumno->setRut(htmlspecialchars($r[$this->campos[1]]));
			$alumno->setNombres(htmlspecialchars($r[$this->campos[2]]));
			$alumno->setPaterno(htmlspecialchars($r[$this->campos[3]]));
			$alumno->setMaterno(htmlspecialchars($r[$this->campos[4]]));
			$alumno->setCurso(htmlspecialchars($r[$this->campos[5]]));
			array_push($alumnos,$alumno);
		}
		return $alumnos;
	}
	public function fetchAll() {
		$alumnos = array();
		$response = $this->query->select(array("*"),array($this->tabla),"true");
		if(!$response) return false;
		foreach($response as $r) {
			$alumno = new Alumno();
			$alumno->setId($r[$this->campos[0]]);
			$alumno->setRut(htmlspecialchars($r[$this->campos[1]]));
			$alumno->setNombres(htmlspecialchars($r[$this->campos[2]]));
			$alumno->setPaterno(htmlspecialchars($r[$this->campos[3]]));
			$alumno->setMaterno(htmlspecialchars($r[$this->campos[4]]));
			$alumno->setCurso(htmlspecialchars($r[$this->campos[5]]));
			array_push($alumnos,$alumno);
		}
		return $alumnos;		
	}
	
	private function validaRut ( $rutCompleto ) {
		if ( !preg_match("/^[0-9]+-[0-9kK]{1}/",$rutCompleto)) return false;
		$rut = explode('-', $rutCompleto);
		return strtolower($rut[1]) == $this->dv($rut[0]);
	}
	private function dv ( $T ) {
		$M=0;$S=1;
		for(;$T;$T=floor($T/10))
			$S=($S+$T%10*(9-$M++%6))%11;
			return $S?$S-1:'k';
	}
}