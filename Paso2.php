<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/Factory/ActiveRecordFactory.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/Singleton/Sesion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/SegurityCrossSite.php';

$oRegistry = Sesion::getInstancia()->getRegistry();

//----------------------------------SEGURIDAD-----------------------------------------------
//CROSS-SITE
$oSSCS = SegurityCrossSite::getInstancia();
if($oSSCS->existeToken() == false or $oSSCS->validarToken() == false)
{
	header('location: index.php');
}


if (!isset($_SESSION['initiated']))
{
	session_regenerate_id();
	$_SESSION['initiated'] = true;
}
if ($_SESSION['HUA'] != md5($_SERVER['HTTP_USER_AGENT'])) {
	header('Location: index.php');
}
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

if (isset($_POST['bt_paso1']) == true)
{
	//USUARIO
	$auxUsuario = htmlentities($_POST['nombre_usuario']);
	if(ctype_alpha($auxUsuario)==false ){
		$FiltroUsuario = false;
	}else{
		$FiltroUsuario = true;
	}

	//CONTRASENIA
	$auxContrasenia = htmlentities($_POST['contrasenia']);
	if(ctype_alnum($auxContrasenia)==false ){
		$FiltroContrasenia = false;
	}else{
		$FiltroContrasenia = true;
	}

	//APELLIDO
	$auxApellido = htmlentities($_POST['apellido']);
	if(ctype_alpha($auxApellido)==false ){
		$FiltroApellido = false;
	}else{
		$FiltroApellido = true;
	}

	//NOMBRE
	$auxUsuario = htmlentities($_POST['nombre']);
	$aux = implode('',array_filter(explode(' ',$auxUsuario)));
	if(ctype_alpha($aux) == false ){
		$FiltroNombre = false;
	}else{
		$FiltroNombre = true;
	}

	//DNI
	$tipoDNI = array(1,2,3);
	if(in_array($_POST['tipo_documento'], $tipoDNI)){
		$auxTipoDoc = htmlentities($_POST['tipo_documento']);
		if(ctype_alnum($auxTipoDoc)==false ){
			$FiltroIdTipoDocumento = false;
		}else{
			$FiltroIdTipoDocumento = true;
		}
	}else{
		$FiltroIdTipoDocumento = false;
	}

	//NUMERO DOCUMENTO
	$auxNumeroDoc = htmlentities($_POST['numero_documento']);
		if(ctype_alnum($auxNumeroDoc)==false ){
			$FiltroNumeroDocumento = false;
		}else{
			$FiltroNumeroDocumento = true;
		}

		//SEXO
		$tipoSexo = array('F','M');
		if(in_array($_POST['sexo'], $tipoSexo)){
				$auxSexo = htmlentities($_POST['sexo']);
			if(ctype_alpha($auxSexo)==false ){
				$FiltroSexo = false;
			}else{
				$FiltroSexo = true;
			}
		}else{
			$FiltroSexo = false;
		}

		//NACIONALIDAD
		$auxNacionalidad = htmlentities($_POST['nacionalidad']);
		if(ctype_alpha($auxNacionalidad)==false ){
			$FiltroNacionalidad = false;
		}else{
			$FiltroNacionalidad = true;
		}
	}else{
		$FiltroUsuario = true;
		$FiltroContrasenia = true;
		$FiltroApellido = true;
		$FiltroNombre = true;
		$FiltroIdTipoDocumento = true;
		$FiltroNumeroDocumento = true;
		$FiltroSexo = true;
		$FiltroNacionalidad = true;
	}// --- FIN DE FILTRADO ---

	$validarTipoDocumento = false;
	$validarSexo = false;
	$validarContrasenia = false;

	if ( isset($_POST['bt_paso1']) == true )
	{
		$oUsuarioVO->idTipoUsuario = 2;
		$oUsuarioVO->nombre = ( isset($_POST['nombre_usuario']) == true ) ? $_POST['nombre_usuario'] : '';
		$oUsuarioVO->contrasenia = ( isset($_POST['contrasenia']) == true ) ? $_POST['contrasenia'] : '';
		$oPersonaVO->apellido = ( isset($_POST['apellido']) == true ) ? $_POST['apellido'] : '';
		$oPersonaVO->nombre = ( isset($_POST['nombre']) == true ) ? $_POST['nombre'] : '';
		$oPersonaVO->idTipoDocumento = ( isset($_POST['tipo_documento']) == true ) ? $_POST['tipo_documento'] : '';
		$oPersonaVO->numeroDocumento = ( isset($_POST['numero_documento']) == true ) ? $_POST['numero_documento'] : '';
		$oPersonaVO->sexo = ( isset($_POST['sexo']) == true ) ? $_POST['sexo'] : '';
		$oPersonaVO->nacionalidad = ( isset($_POST['nacionalidad']) == true ) ? $_POST['nacionalidad'] : '';


		$oUsuario = ActiveRecordFactory::getUsuario();
		$oUsuario->set($oUsuarioVO);

		if ( $oUsuario->validarContrasenia() == true )
		{
			$validarContrasenia = true;
		}

		foreach ( ActiveRecordFactory::getTipoDocumento()->fetchAll() as $oTipoDocumento )
		{
			if ( $oPersonaVO->idTipoDocumento == $oTipoDocumento->idTipoDocumento )
			{
				$validarTipoDocumento = true;
			}
		}

		$sexos = array('M','F');
		if ( in_array($oPersonaVO->sexo, $sexos) == true )
		{
			$validarSexo = true;
		}

		$oRegistry->add('persona', $oPersonaVO);
		$oRegistry->add('usuario', $oUsuarioVO);
	}
	else
	{
		$validarContrasenia = true;
		$validarSexo = true;
		$validarTipoDocumento = true;
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

	<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/header.php' ?>
	<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/menu.php'; ?>

	<?php if ( $FiltroUsuario == false || $FiltroContrasenia == false || $FiltroApellido == false || $FiltroNombre == false || $FiltroIdTipoDocumento == false || $FiltroNumeroDocumento == false || $FiltroSexo == false || $FiltroNacionalidad == false){?>
		
		<div class="mensaje">
			<h3>Existen algunos errores al procesar la información ingresada</h3>
			<ul>
				<?php if ( $FiltroUsuario == false ) { ?>
				<li>Usuario no valido. Caracteres prohibidos o incorrectos</li>
				<?php } if ( $FiltroContrasenia == false ) { ?>
				<li>Contrasenia no valido. Caracteres prohibidos o incorrectos</li>
				<?php } if ( $FiltroApellido == false ) { ?>
				<li>Apellido no valido. Caracteres prohibidos o incorrectos</li>
				<?php } if ( $FiltroNombre == false ) { ?>
				<li>Nombre no valido. Caracteres prohibidos o incorrectos</li>
				<?php } if ( $FiltroIdTipoDocumento == false ) { ?>
				<li>Tipo documento no valido. Caracteres prohibidos o incorrectos</li>
				<?php } if ( $FiltroNumeroDocumento == false ) { ?>
				<li>Numero de documento no valido. Caracteres prohibidos o incorrectos</li>
				<?php } if ( $FiltroSexo == false ) { ?>
				<li>Sexo no valido. Caracteres prohibidos o incorrectos</li>
				<?php } if ( $FiltroNacionalidad == false ) { ?>
				<li>nacionalidad no valida. Caracteres prohibidos o incorrectos</li>
				<?php } ?>
			</ul>
			<div class="buttons">
				<input type="button" value="Anterior" onclick="document.location='Paso1.php'">
			</div>
		</div>

	<?php } else if ( $validarContrasenia == false || $validarSexo == false || $validarTipoDocumento == false ) { ?>

		<div class="mensaje">
			<h3>Existen algunos errores al procesar la información ingresada</h3>
			<ul>
				<?php if ( $validarContrasenia == false ) { ?>
				<li>La contraseña no es válida. Debe contener al menos 6 caracteres y al menos 1 letra y 1 número</li>
				<?php } if ( $validarTipoDocumento == false ) { ?>
				<li>El tipo de documento ingresado no se encuentra registrado</li>
				<?php } if ( $validarSexo == false ) { ?>
				<li>El sexo ingresado no se encuentra registrado</li>
				<?php } ?>
			</ul>
			<div class="buttons">
				<input type="button" value="Anterior" onclick="document.location='Paso1.php'">
			</div>
		</div>

	<?php } else { ?>

		<form action="Paso3.php" method="post">
			<fieldset>
				<legend>Informaci&oacute;n de Contacto:</legend>

				<ul>
					<li><label>Correo electr&oacute;nico:</label></li>
					<li><input type="text" name="email" value="<?= $oPersonaVO->email ?>"></li>

					<li><label>Tel&eacute;fono:</label></li>
					<li><input type="text" name="telefono" value="<?= $oPersonaVO->telefono ?>"></li>

					<li><label>Celular:</label></li>
					<li><input type="text" name="celular" value="<?= $oPersonaVO->celular ?>"></li>

					<li><label>Provincia:</label></li>
					<li>
						<select name="provincia">
							<?php foreach ($aProvincias as $provincia ) { ?>
							<option value="<?= $provincia ?>" <?= ( $oPersonaVO->provincia == $provincia ) ? 'selected="selected"' : ''  ?>><?= $provincia ?></option>
							<?php } ?>
						</select>
					</li>

					<li><label>Localidad:</label></li>
					<li><input type="text" name="localidad" value="<?= $oPersonaVO->localidad ?>"></li>
				</ul>
				<input type="hidden" name="token" value="<?= $token ?>">
				<div class="buttons">
					<input type="submit" name="bt_paso2" value="Siguiente">
					<input type="button" value="Anterior" onclick="document.location='Paso1.php'">
				</div>
			</fieldset>
		</form>

	<?php } ?>

	<div class="push"></div>
	
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/footer.php'; ?>
</body>
</html>