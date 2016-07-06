<?php
$alumnoDAO = new alumnoDAO();
$cursoDAO = new cursoDAO();
$alumno = new Alumno();
$error="";
if(sizeof($_POST)>0)  {
	if( isset( $_POST["agregar"] ) ) {
		$alumno->setRut( htmlspecialchars($_POST["rut"]) );
		$alumno->setNombres(ucwords( htmlspecialchars($_POST["nombre"]) ) );
		$alumno->setPaterno( ucwords( htmlspecialchars($_POST["paterno"] ) ) );
		$alumno->setMaterno( ucwords( htmlspecialchars($_POST["materno"] ) ) );
		$alumno->setCurso( htmlspecialchars($_POST["curso"]));
		try {
			$alumnoDAO->save($alumno);
		} catch (Exception $e) {
			$error = $e->getMessage();
		}
	}
	if(isset( $_POST["delete"] ) )  {
		$alumno->setId(htmlspecialchars($_POST["id"]));
		try {
			$alumnoDAO->delete($alumno);
		} catch (Exception $e) {
			$error = $e->getMessage();
		}
	}
	if(isset( $_POST["buscanombre"] ) ) {
		$buscax = htmlspecialchars($_POST["buscax"]);
		switch ($buscax) {
			case "id" : $alumno->setId(htmlspecialchars($_POST["buscarnombre"]));break;
			case "rut" : $alumno->setRut(htmlspecialchars($_POST["buscarnombre"]));break;
			case "nombres" : $alumno->setNombres(htmlspecialchars($_POST["buscarnombre"]));break;
			case "paterno" : $alumno->setPaterno(htmlspecialchars($_POST["buscarnombre"]));break;
			case "materno" : $alumno->setMaterno(htmlspecialchars($_POST["buscarnombre"]));break;
			case "curso_id": $alumno->setCurso(htmlspecialchars($_POST["buscarnombre"])); break;
		}
		try {
			$buscado = $alumnoDAO->fetch($alumno);
			//var_dump($buscado);
		}
		catch (Exception $e) {
			$error = $e->getMessage();
		}
	}
	if(isset( $_POST["update"])) {
		$alumno->setId(htmlspecialchars($_POST["id"]));
		$alumno->setRut(htmlspecialchars($_POST["rut"]));
		$alumno->setNombres(htmlspecialchars($_POST["nombres"]));
		$alumno->setPaterno(htmlspecialchars($_POST["paterno"]));
		$alumno->setMaterno(htmlspecialchars($_POST["materno"]));
		$alumno->setCurso(htmlspecialchars($_POST["curso_id"])); 
		try {
			$alumnoDAO->save($alumno);
		} catch (Exception $e) {
			$error = $e->getMessage();
		}
	}
	
}
if(strlen($error) > 0 )
	echo "<b style='color:red'>$error</b><br/>";

$cursos = $cursoDAO->fetchAll();
?>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	<label for="rut">RUT</label><input type="text" name="rut" value="<?php echo (strlen($error) > 0 )?$alumno->getRut():"";?>"/>
	<label for="nombre">Nombre</label><input type="text" name="nombre" value="<?php echo (strlen($error) > 0 )?$alumno->getNombres():"";?>"/>
	<label for="paterno">Paterno</label><input type="text" name="paterno" value="<?php echo (strlen($error) > 0 )?$alumno->getPaterno():"";?>"/>
	<label for="materno">Materno</label><input type="text" name="materno" value="<?php echo (strlen($error) > 0 )?$alumno->getMaterno():"";?>"/>	
	<select name="curso">
	<?php foreach($cursos as $c) :?>
		<option value="<?php echo $c->getId()?>" <?php echo ($alumno->getCurso() ==  $c->getId() )? "selected":"";?>><?php echo $c->getNivel()." ".$c->getNombre();?></option>
	<?php endforeach;?>
	</select>
	<input type="submit" name="agregar" value="Agregar">
</form>
<?php
$allAlumnos = $alumnoDAO->fetchAll();
//var_dump($allAlumnos);
if($allAlumnos!=false)
	foreach($allAlumnos as $al) :
		$cursoAlumno = new Curso();
		$cursoAlumno->setId($al->getCurso());
		$lustacursos = $cursoDAO->fetch( $cursoAlumno );
		$curso = array_shift( $lustacursos );
		//var_dump($curso);
		echo '<br/>';
		echo " ID: ".$al->getId()." RUT: ".$al->getRut()." Nombre: ".ucwords($al->getNombres())." ".ucwords($al->getPaterno())." ".ucwords($al->getMaterno())." ".$curso->getNivel()." ".$curso->getNombre();
	endforeach;
?>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	<label for="id">ID</label><input type="text" name="id"/>
	<input type="submit" name="delete" value="Eliminar">
</form>
<br/>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	<label for="buscarnombre">ID</label><input type="text" name="buscarnombre"/>
	<select name="buscax">
	<?php $campos = $alumnoDAO->getCampos(); 
	foreach ($campos as $c) :?>
		<option value="<?php echo $c;?>"><?php echo $c;?></option>
	<?php endforeach;?>
	</select>
	<input type="submit" name="buscanombre" value="Buscar x nombre">
</form>
<?php 
if( (isset($buscado)) && (false!=$buscado))
	foreach($buscado as $c) :
		echo '<br/>';
		echo "Alumno ID: ".$c->getId()." nombre ".ucwords($c->getNombres())." ".ucwords($c->getPaterno())." ".ucwords($c->getMaterno());
endforeach;
?>
<br/>

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	<label for="id">ID</label><input type="text" name="id"/>
	<label for="rut">rut</label><input type="text" name="rut"/>
	<label for="nombres">nombres</label><input type="text" name="nombres"/>
	<label for="paterno">paterno</label><input type="text" name="paterno"/>
	<label for="materno">materno</label><input type="text" name="materno"/>
	<label for="curso_id">curso_id</label><input type="text" name="curso_id"/>
	<input type="submit" name="update" value="actualizar">
</form>

