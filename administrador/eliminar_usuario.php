<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/Factory/ActiveRecordFactory.php';

session_start();
$oPersona = ActiveRecordFactory::getPersona();
$oUsuario = ActiveRecordFactory::getUsuario();

if (!isset($_SESSION['initiated']))
{
	session_regenerate_id();
	$_SESSION['initiated'] = true;
}
if ($_SESSION['HUA'] != md5($_SERVER['HTTP_USER_AGENT'])) {
	header('Location: index.php');
}
if ( isset($_POST['bt_eliminar']) == true )
{

	$pdo = BaseDeDatos::getInstancia()->getConexion();

	$pdo->beginTransaction();

	try
	{
		$oUsuario->fetch($_POST['idUsuario']);
		$idPersona = $oUsuario->get()->idPersona;


		$oUsuario->delete($_POST['idUsuario']);
		$oPersona->delete($idPersona);

		$pdo->commit();

		header('location: /tp6/administrador/');
	}
	catch(Exception $e)
	{
		$pdo->rollBack();
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="ISO-8859-1">
	<title>SGU | Eliminar Usuario</title>
	<link type="text/css" rel="stylesheet" href="/tp6/includes/css/estilos.css">
</head>
<body>

<div class="wraper">

	<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/header.php'; ?>
	<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/menu-admin.php'; ?>

	<div class="mensaje">

		<h3>Error al registrar el usuario</h3>
		<p>
			Ha ocurrido un error al intentar registrar el usuario. Por favor intentelo nuevamente.
		</p>

		<div class="buttons">
			<input type="button" value="Anterior" onclick="document.location='/tp6/administrador'">
		</div>
	</div>

	<div class="push"></div>

</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/footer.php'; ?>
</body>
</html>