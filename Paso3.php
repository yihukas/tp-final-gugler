<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/Factory/ActiveRecordFactory.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/Singleton/Sesion.php';

$oRegistry = Sesion::getInstancia()->getRegistry();
if (!isset($_SESSION['initiated']))
{
	session_regenerate_id();
	$_SESSION['initiated'] = true;
}
if ($_SESSION['HUA'] != md5($_SERVER['HTTP_USER_AGENT'])) {
	header('Location: index.php');
}
if(isset($_SESSION['token']) and ($_SESSION['token'] == $_POST['token']))
{
	ini_set ('display_errors', 0);
	$token = md5(rand(15000));
	ini_set ('display_errors', 1);
	$oPersonaVO = ( $oRegistry->exists('persona') == false ) ? new PersonaVO() : $oRegistry->get('persona');
	$oUsuarioVO = ( $oRegistry->exists('usuario') == false ) ? new UsuarioVO() : $oRegistry->get('usuario');

	$aProvincias = array('Entre Rios', 'Sante Fe', 'Cordoba', 'Buenos Aires');

	//1. Filtrado de datos de entrada y salida
	// aplico:
	//htmlentities
	//ctype
	//Falsificación de Formularios

	if (isset($_POST['bt_paso2']) == true)
	{
		//EMAIL
		$auxEmail = htmlentities($_POST['email']);
		if(ctype_graph($auxEmail)==false ){
			$FiltroEmail = false;
		}else{
			$FiltroEmail = true;
		}

		//TELEFONO
		$auxTelefono = htmlentities($_POST['telefono']);
		if(ctype_graph($auxTelefono)==false ){
			$FiltroTelefono = false;
		}else{
			$FiltroTelefono = true;
		}

		//CELULAR
		$auxCelular = htmlentities($_POST['celular']);
		if(ctype_graph($auxCelular)==false ){
			$FiltroCelular = false;
		}else{
			$FiltroCelular = true;
		}

		//PROVINCIAS
		if(in_array($_POST['provincia'], $aProvincias)){
			$auxProv = htmlentities($_POST['provincia']);
			$aux = implode('',array_filter(explode(' ',$auxProv)));
			if(ctype_alpha($aux)==false ){
				$FiltroProvincia = false;
			}else{
				$FiltroProvincia = true;
			}
		}else{
			$FiltroProvincia = false;
		}

		//LOCALIDAD
		$aux = htmlentities($_POST['localidad']);
		$auxlocalidad = implode('',array_filter(explode(' ',$aux)));
		if(ctype_alpha($auxlocalidad)==false ){
			$FiltroLocalidad = false;
		}else{
			$FiltroLocalidad = true;
		}
	}else{
		$FiltroEmail = true;
		$FiltroTelefono = true;
		$FiltroCelular = true;
		$FiltroProvincia = true;
		$FiltroLocalidad = true;
	}// --- FIN DE FILTRADO


	$validarProvincia = false;
	$validarContacto = false;

	if ( isset($_POST['bt_paso2']) == true )
	{
		$oPersonaVO->email = ( isset($_POST['email']) == true ) ? $_POST['email'] : '';
		$oPersonaVO->telefono = ( isset($_POST['telefono']) == true ) ? $_POST['telefono'] : '';
		$oPersonaVO->celular = ( isset($_POST['celular']) == true ) ? $_POST['celular'] : '';
		$oPersonaVO->provincia = ( isset($_POST['provincia']) == true ) ? $_POST['provincia'] : '';
		$oPersonaVO->localidad = ( isset($_POST['localidad']) == true ) ? $_POST['localidad'] : '';

		foreach ($aProvincias as $provincia )
		{
			if ( $oPersonaVO->provincia == $provincia )
			{
				$validarProvincia = true;
			}
		}

		$oPersona = ActiveRecordFactory::getPersona();
		$oPersona->set($oPersonaVO);

		if ( $oPersona->validarContactos() == true )
		{
			$validarContacto = true;
		}

		$oRegistry->add('persona', $oPersonaVO);
	}
	else
	{
		$validarProvincia = true;
		$validarContacto = true;
	}

	$oPersona = ActiveRecordFactory::getPersona();
	$oPersona->set($oPersonaVO);
	$oUsuario = ActiveRecordFactory::getUsuario();
	$oUsuario->set($oUsuarioVO);
	$oTipoDocumento = ActiveRecordFactory::getTipoDocumento();
	$oTipoDocumento->fetch($oPersonaVO->idTipoDocumento);
}else{
	header('location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="ISO-8859-1">
	<title>SGU | Formulario de Inscripc&oacute;n</title>
	<link type="text/css" rel="stylesheet" href="/tp6/includes/css/estilos.css">
</head>
<body>

<div class="wraper">

	<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/header.php'; ?>
	<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/menu.php'; ?>
	<?php if ( $FiltroEmail == false || $FiltroTelefono == false || $FiltroCelular == false || $FiltroProvincia == false || $FiltroLocalidad == false){?>
		
		<div class="mensaje">
			<h3>Existen algunos errores al procesar la información ingresada</h3>
			<ul>
				<?php if ( $FiltroEmail == false ) { ?>
				<li>Email no valido. Caracteres prohibidos o incorrectos</li>
				<?php } if ( $FiltroTelefono == false ) { ?>
				<li>Telefono no valido. Caracteres prohibidos o incorrectos</li>
				<?php } if ( $FiltroCelular == false ) { ?>
				<li>Celular no valido. Caracteres prohibidos o incorrectos</li>
				<?php } if ( $FiltroProvincia == false ) { ?>
				<li>Provincia no valida. Caracteres prohibidos o incorrectos</li>
				<?php } if ( $FiltroLocalidad == false ) { ?>
				<li>Localidad no valida. Caracteres prohibidos o incorrectos</li>
				<?php } ?>
			</ul>
			<div class="buttons">
				<input type="button" value="Anterior" onclick="document.location='Paso2.php'">
			</div>
		</div>

	<?php } else if ( $validarProvincia == false || $validarContacto == false ) { ?>

		<div class="mensaje">
			<h3>Existen algunos errores al procesar la información ingresada</h3>
			<ul>
				<?php if ( $validarProvincia == false ) { ?>
					<li>La provincia ingresada no se encuentra registrada</li>
				<?php } if ( $validarContacto == false ) { ?>
					<li>Alguna de las forma de contacto no se ha ingresado correctamento, recuerde que el correo electrónico debe contener un símbolo "@" y para teléfono y celular debe contener al menos 10 dígitos y estar separado por un "-"</li>
				<?php } ?>
			</ul>
			<div class="buttons">
				<input type="button" value="Anterior" onclick="document.location='Paso2.php'">
			</div>
		</div>

	<?php } else { ?>

		<h2>Informaci&oacute;n de alta de usuario</h2>

		<div class="ultimo_paso">

			<fieldset>
				<legend>Informaci&oacute;n Personal:</legend>

				<ul>
					<li><label>Nombre de Usuario:</label></li>
					<li><?= $oUsuarioVO->nombre ?><br></li>

					<li><label>Contrase&ntilde;a:</label></li>
					<li><?= $oUsuario->getContraseniaEnmascadara() ?><br></li>

					<li><label>Apellido:</label></li>
					<li><?= $oPersonaVO->apellido ?></li>

					<li><label>Nombre:</label></li>
					<li><?= $oPersonaVO->nombre ?></li>

					<li><label>Tipo de Documento:</label></li>
					<li><?= $oTipoDocumento->get()->nombre ?></li>

					<li><label>N&uacute;mero de Documento:</label></li>
					<li><?= $oPersonaVO->numeroDocumento ?></li>

					<li><label>Sexo:</label></li>
					<li><?= ( $oPersonaVO->sexo == 'M' ) ? 'Masculino' : 'Femenino' ?></li>

					<li><label>Nacionalidad:</label></li>
					<li><?= $oPersonaVO->nacionalidad ?></li>
				</ul>

			</fieldset>

			<fieldset>
				<legend>Informaci&oacute;n de Contacto:</legend>

				<ul>
					<li><label>Correo electr&oacute;nico:</label></li>
					<li><?= $oPersonaVO->email ?></li>

					<li><label>Tel&eacute;fono:</label></li>
					<li><?= $oPersonaVO->telefono ?></li>

					<li><label>Celular:</label></li>
					<li><?= $oPersonaVO->celular ?></li>

					<li><label>Provincia:</label></li>
					<li><?= $oPersonaVO->provincia ?></li>

					<li><label>Localidad:</label></li>
					<li><?= $oPersonaVO->localidad ?></li>
				</ul>
			</fieldset>

			<fieldset>

				<div class="buttons">
					<input type="button" value="Guardar" onclick="document.location='Finalizar.php'">
					<input type="button" value="Anterior" onclick="document.location='Paso2.php'">
				</div>

			</fieldset>

		</div>

	<?php } ?>
	
	<div class="push"></div>
	
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/footer.php'; ?>
</body>
</html>