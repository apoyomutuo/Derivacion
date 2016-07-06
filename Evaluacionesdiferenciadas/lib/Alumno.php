<?php
class Alumno {
	private $id;
	private $rut;
	private $nombres;
	private $paterno;
	private $materno;
	private $curso;
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setRut($rut) {
		$this->rut = $rut;
	}
	
	public function getRut() {
		return $this->rut;
	}
	public function setNombres($nombres) {
		$this->nombres = $nombres;
	}
	
	public function getNombres() {
		return $this->nombres;
	}
	public function setPaterno($paterno) {
		$this->paterno = $paterno;
	}
	
	public function getPaterno() {
		return $this->paterno;
	}
	public function setMaterno($materno) {
		$this->materno = $materno;
	}
	
	public function getMaterno() {
		return $this->materno;
	}
	
	public function setCurso($curso) {
		$this->curso = $curso;
	}
	
	public function getCurso() {
		return $this->curso;
	}
}