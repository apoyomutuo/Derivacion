<?php
class Curso {
	private $id;
	private $nivel;
	private $nombre;
	
	function setId($id) {
		$this->id=$id;
	}
	function getId() {
		return $this->id;
	}
	function setNivel($nivel) {
		$this->nivel = $nivel;
	}
	function getNivel() {
		return $this->nivel;
	}
	function setNombre($nombre) {
		$this->nombre = $nombre;
	}
	function getNombre() {
		return $this->nombre;
	}
}