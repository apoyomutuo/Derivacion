<?php
interface DAO {
	public function save($obj);//save and edit
	public function delete($obj);
	//public function edit($obj);
	public function fetch($obj); 
	public function fetchAll();
}