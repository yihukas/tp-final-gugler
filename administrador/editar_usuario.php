<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/Factory/ActiveRecordFactory.php';

$oPersona = ActiveRecordFactory::getPersona();
$oUsuario = ActiveRecordFactory::getUsuario();
session_start();
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
	$aProvincias = array('Entre Rios', 'Sante Fe', 'Cordoba', 'Buenos Aires');

	if ( isset($_POST['bt_guardar']) == true )
	{
		//TIPO USUARIO
		$tipoUsuario = array(1,2);
		if(in_array($_POST['tipo_documento'], $tipoUsuario)){
			$tipo_usuario = htmlentities($_POST['tipo_usuario']);
			if(ctype_alnum($tipo_usuario)==false ){
				$FiltroTipo_usuario = false;
			}else{
				$FiltroTipo_usuario = true;
			}
		}

		//NOMBRE USUARIO
		$nombre_usuario = htmlentities($_POST['nombre_usuario']);
		if(ctype_alpha($nombre_usuario)==false ){
			$FiltroNombre_usuario = false;
		}else{
			$FiltroNombre_usuario = true;
		}

		//CONTRASENIA
		$contrasenia = htmlentities($_POST['contrasenia']);
		if(ctype_graph($contrasenia)==false ){
			$FiltroContrasenia = false;
		}else{
			$FiltroContrasenia = true;
		}

		//APELLIDO
		$apellido = htmlentities($_POST['apellido']);
		if(ctype_alpha($apellido)==false ){
			$FiltroApellido = false;
		}else{
			$FiltroApellido = true;
		}

		//NOMBRE
		$aux = htmlentities($_POST['nombre']);
		$nombre = implode('',array_filter(explode(' ',$aux)));
		if(ctype_alpha($nombre)==false ){
			$FiltroNombre = false;
		}else{
			$FiltroNombre = true;
		}

		//TIPO DOCUMENTO
		$tipoDNI = array(1,2,3);
		if(in_array($_POST['tipo_documento'], $tipoDNI)){
			$tipo_documento = htmlentities($_POST['tipo_documento']);
			if(ctype_alnum($tipo_documento)==false ){
				$FiltroTipo_documento = false;
			}else{
				$FiltroTipo_documento = true;
			}
		}

		//NUMERO DOCUMENTO
		$numero_documento = htmlentities($_POST['numero_documento']);
		if(ctype_alnum($numero_documento)==false ){
			$FiltroNumero_documento = false;
		}else{
			$FiltroNumero_documento = true;
		}

		//SEXO
		$tipoSexo = array('F','M');
		if(in_array($_POST['sexo'], $tipoSexo)){
			$sexo = htmlentities($_POST['sexo']);
			if(ctype_alnum($sexo)==false ){
				$FiltroSexo = false;
			}else{
				$FiltroSexo = true;
			}
		}else{
			$FiltroSexo = false;
		}

		//nacionalidad
		$nacionalidad = htmlentities($_POST['nacionalidad']);
		if(ctype_alpha($nacionalidad)==false ){
			$FiltroNacionalidad = false;
		}else{
			$FiltroNacionalidad = true;
		}

		//email
		$email = htmlentities($_POST['email']);
		if(ctype_graph($email)==false ){
			$FiltroEmail = false;
		}else{
			$FiltroEmail = true;
		}

		//telefono
		$telefono = htmlentities($_POST['telefono']);
		if(ctype_graph($telefono)==false ){
			$FiltroTelefono = false;
		}else{
			$FiltroTelefono = true;
		}

		//celular
		$celular = htmlentities($_POST['celular']);
		if(ctype_graph($celular)==false ){
			$FiltroCelular = false;
		}else{
			$FiltroCelular = true;
		}

		//provincia
		if(in_array($_POST['provincia'], $aProvincias)){
			$aux = htmlentities($_POST['provincia']);
			$provincia = implode('',array_filter(explode(' ',$aux)));
			if(ctype_alpha($provincia)==false ){
				$FiltroProvincia = false;
			}else{
				$FiltroProvincia = true;
			}
		}else{
			$FiltroProvincia = false;
		}

		//localidad
		$aux = htmlentities($_POST['localidad']);
		$localidad = implode('',array_filter(explode(' ',$aux)));
		if(ctype_alpha($localidad)==false ){
			$FiltroLocalidad = false;
		}else{
			$FiltroLocalidad = true;
		}
	}else{
		$FiltroTipo_usuario = false;
		$FiltroNombre_usuario = false;
		$FiltroContrasenia = false;
		$FiltroApellido = false;
		$FiltroNombre = false;
		$FiltroTipo_documento = false;
		$FiltroNumero_documento = false;
		$FiltroSexo = false;
		$FiltroNacionalidad = false;
		$FiltroEmail = false;
		$FiltroTelefono = false;
		$FiltroCelular = false;
		$FiltroProvincia = false;
		$FiltroLocalidad = false;
	}

	$validarTipoDocumento = false;
	$validarSexo = false;
	$validarContrasenia = false;
	$validarProvincia = false;
	$validarContacto = false;

	if ( isset($_POST['bt_guardar']) == true )
	{
		$oUsuario->fetch($_POST['idUsuario']);
		$oPersona->fetch($oUsuario->get()->idPersona);

		$oUsuarioVO = $oUsuario->get();
		$oPersonaVO = $oPersona->get();

		$oUsuarioVO->idTipoUsuario = ( isset($_POST['tipo_usuario']) == true ) ? $_POST['tipo_usuario'] : 2;
		$oUsuarioVO->nombre = ( isset($_POST['nombre_usuario']) == true ) ? $_POST['nombre_usuario'] : '';
		$oUsuarioVO->contrasenia = ( isset($_POST['contrasenia']) == true ) ? $_POST['contrasenia'] : '';

		$oPersonaVO->apellido = ( isset($_POST['apellido']) == true ) ? $_POST['apellido'] : '';
		$oPersonaVO->nombre = ( isset($_POST['nombre']) == true ) ? $_POST['nombre'] : '';
		$oPersonaVO->idTipoDocumento = ( isset($_POST['tipo_documento']) == true ) ? $_POST['tipo_documento'] : '';
		$oPersonaVO->numeroDocumento = ( isset($_POST['numero_documento']) == true ) ? $_POST['numero_documento'] : '';
		$oPersonaVO->sexo = ( isset($_POST['sexo']) == true ) ? $_POST['sexo'] : '';
		$oPersonaVO->nacionalidad = ( isset($_POST['nacionalidad']) == true ) ? $_POST['nacionalidad'] : '';
		$oPersonaVO->email = ( isset($_POST['email']) == true ) ? $_POST['email'] : '';
		$oPersonaVO->telefono = ( isset($_POST['telefono']) == true ) ? $_POST['telefono'] : '';
		$oPersonaVO->celular = ( isset($_POST['celular']) == true ) ? $_POST['celular'] : '';
		$oPersonaVO->provincia = ( isset($_POST['provincia']) == true ) ? $_POST['provincia'] : '';
		$oPersonaVO->localidad = ( isset($_POST['localidad']) == true ) ? $_POST['localidad'] : '';

		$oUsuario->set($oUsuarioVO);
		$oPersona->set($oPersonaVO);

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

		foreach ($aProvincias as $provincia )
		{
			if ( $oPersonaVO->provincia == $provincia )
			{
				$validarProvincia = true;
			}
		}

		if ( $oPersona->validarContactos() == true )
		{
			$validarContacto = true;
		}

		$validaciones = $validarContrasenia && $validarContacto && $validarProvincia && $validarSexo && $validarTipoDocumento;

		if ( $validaciones )
		{
			$pdo = BaseDeDatos::getInstancia()->getConexion();

			$pdo->beginTransaction();

			try
			{
				$oUsuario->update();
				$oPersona->update();

				$pdo->commit();

				header('location: /tp6/administrador/');
			}
			catch(Exception $e)
			{
				$pdo->rollBack();
			}
		}
	}

	?>
	<!DOCTYPE html>
	<html>
	<head>
		<meta charset="ISO-8859-1">
		<title>SGU | Editar Usuario</title>
		<link type="text/css" rel="stylesheet" href="/tp6/includes/css/estilos.css">
	</head>
	<body>

	<div class="wraper">

		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/header.php'; ?>
		<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/menu-admin.php'; ?>

		<?php if ( $FiltroTipo_usuario == false || $FiltroNombre_usuario == false || $FiltroContrasenia == false || $FiltroApellido == false || $FiltroNombre == false || $FiltroNumero_documento == false || $FiltroTipo_documento == false || $FiltroSexo == false || $FiltroNacionalidad == false || $FiltroEmail == false || $FiltroTelefono == false || $FiltroCelular == false || $FiltroProvincia == false || $FiltroLocalidad == false){?>
		
		<div class="mensaje">
			<h3>Existen algunos errores al procesar la información ingresada</h3>
			<ul>
				<?php if ( $FiltroTipo_usuario == false ) { ?>
				<li>TIPO Usuario no valido. Caracteres prohibidos o incorrectos</li>
				<?php } if ( $FiltroNombre_usuario == false ) { ?>
				<li>NOMBRE USUARIO no valido. Caracteres prohibidos o incorrectos</li>
				<?php } if ( $FiltroContrasenia == false ) { ?>
				<li>CONTRASEÑA no valida. Caracteres prohibidos o incorrectos</li>
				<?php } if ( $FiltroApellido == false ) { ?>
				<li>APELLIDO no valido. Caracteres prohibidos o incorrectos</li>
				<?php } if ( $FiltroNombre == false ) { ?>
				<li>NOMBRE no valido. Caracteres prohibidos o incorrectos</li>
				<?php } if ( $FiltroNumero_documento == false ) { ?>
				<li>Numero de documento no valido. Caracteres prohibidos o incorrectos</li>
				<?php } if ( $FiltroTipo_documento == false ) { ?>
				<li>TIPO DOCUMENTO no valido. Caracteres prohibidos o incorrectos</li>
				<?php } if ( $FiltroSexo == false ) { ?>
				<li>SEXO no valido. Caracteres prohibidos o incorrectos</li>
				<?php } if ( $FiltroEmail == false ) { ?>
				<li>EMAIL no valido. Caracteres prohibidos o incorrectos</li>
				<?php } if ( $FiltroTelefono == false ) { ?>
				<li>TELEFONO no valido. Caracteres prohibidos o incorrectos</li>
				<?php } if ( $FiltroCelular == false ) { ?>
				<li>CELULAR no valido. Caracteres prohibidos o incorrectos</li>
				<?php } if ( $FiltroProvincia == false ) { ?>
				<li>PROVINCIA no valida. Caracteres prohibidos o incorrectos</li>
				<?php } if ( $FiltroLocalidad == false ) { ?>
				<li>LOCALIDAD no valida. Caracteres prohibidos o incorrectos</li>
				<?php } ?>
			</ul>
			<div class="buttons">
				<input type="button" value="Anterior" onclick="document.location='/tp6/administrador'">
			</div>
		</div>

		<?php } else if ( $validaciones == true ) { ?>
		<div class="mensaje">
				<h3>Error al registrar el usuario</h3>
				<p>
					Ha ocurrido un error al intentar registrar el usuario. Por favor intentelo nuevamente.
				</p>
			<?php } else { ?>
				<h3>Existen algunos errores al procesar la información ingresada</h3>
				<ul>
					<?php if ( $validarContrasenia == false ) { ?>
						<li>La contraseña no es válida. Debe contener al menos 6 caracteres y al menos 1 letra y 1 número</li>
					<?php } if ( $validarTipoDocumento == false ) { ?>
						<li>El tipo de documento ingresado no se encuentra registrado</li>
					<?php } if ( $validarSexo == false ) { ?>
						<li>El sexo ingresado no se encuentra registrado</li>
					<?php } if ( $validarProvincia == false ) { ?>
						<li>La provincia ingresada no se encuentra registrada</li>
					<?php } if ( $validarContacto == false ) { ?>
						<li>Alguna de las forma de contacto no se ha ingresado correctamento, recuerde que el correo electrónico debe contener un símbolo "@" y para teléfono y celular debe contener al menos 10 dígitos y estar separado por un "-"</li>
					<?php } ?>
				</ul>
			<?php } ?>

			<div class="buttons">
				<input type="button" value="Anterior" onclick="document.location='/tp6/administrador'">
			</div>
		</div>

		<div class="push"></div>

	</div>

	<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/footer.php'; ?>
	</body>
	</html>
<?php }else{
	header('location: index.php');
}	
?>