<?php
$cursoDAO = new cursoDAO();
$error="";
if(sizeof($_POST)>0)  {
	if( isset( $_POST["agregar"] ) ) {
		$curso = new Curso();
		$curso->setNivel(htmlspecialchars($_POST["nivel"]));
		$curso->setNombre(htmlspecialchars($_POST["curso"]));
		try {
			$cursoDAO->save($curso);
		} catch (Exception $e) {
			$error = $e->getMessage();
		}
	}
	if(isset( $_POST["delete"] ) )  {
		$curso = new Curso();
		$curso->setId(htmlspecialchars($_POST["id"]));
		try {
			$cursoDAO->delete($curso);
		} catch (Exception $e) {
			$error = $e->getMessage();
		}
	}
	if(isset( $_POST["buscanombre"] ) ) {
		$curso = new Curso();
		$curso->setNombre(htmlspecialchars($_POST["buscarnombre"]));
		try {
			$buscado = $cursoDAO->fetch($curso);
		}
		catch (Exception $e) {
			$error = $e->getMessage();
		}
	}
	if(isset( $_POST["buscanivel"] ) ) {
		$curso = new Curso();
		$curso->setNivel(htmlspecialchars($_POST["buscarnivel"]));
		try {
			$buscadonivel = $cursoDAO->fetch($curso);
		}
		catch (Exception $e) {
			$error = $e->getMessage();
		}
	}
	if(isset( $_POST["update"])) {
		$curso = new Curso();
		$curso->setID(htmlspecialchars($_POST["id"]));
		$curso->setNivel(htmlspecialchars($_POST["nivel"]));
		$curso->setNombre(htmlspecialchars($_POST["curso"]));
		try {
			$cursoDAO->save($curso);
		} catch (Exception $e) {
			$error = $e->getMessage();
		}
	}
	
}
if(strlen($error) > 0 )
	echo "<b style='color:red'>$error</b><br/>";
?>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	<label for="nivel">Nivel</label><input type="text" name="nivel"/>
	<label for="curso">Curso</label><input type="text" name="curso"/>
	<input type="submit" name="agregar" value="Agregar">
</form>
<?php
$allCurso = $cursoDAO->fetchAll();
foreach($allCurso as $c) :
	echo '<br/>';
	echo "curso ID: ".$c->getId()." nivel ".$c->getNivel()." nombre ".$c->getNombre();
endforeach;
?>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	<label for="id">ID</label><input type="text" name="id"/>
	<input type="submit" name="delete" value="Eliminar">
</form>
<br/>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	<label for="buscarnombre">ID</label><input type="text" name="buscarnombre"/>
	<input type="submit" name="buscanombre" value="Buscar x nombre">
</form>
<?php 
if(isset($buscado))
	foreach($buscado as $c) :
		echo '<br/>';
		echo "curso ID: ".$c->getId()." nivel ".$c->getNivel()." nombre ".$c->getNombre();
endforeach;
?>
<br/>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	<label for="buscarnivel">ID</label><input type="text" name="buscarnivel"/>
	<input type="submit" name="buscanivel" value="Buscar x nivel">
</form>
<?php 
if(isset($buscadonivel))
	foreach($buscadonivel as $c) :
		echo '<br/>';
		echo "curso ID: ".$c->getId()." nivel ".$c->getNivel()." nombre ".$c->getNombre();
endforeach;
?>

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	<label for="id">ID</label><input type="text" name="id"/>
	<label for="nivel">Nivel</label><input type="text" name="nivel"/>
	<label for="curso">Curso</label><input type="text" name="curso"/>
	<input type="submit" name="update" value="actualizar">
</form>

