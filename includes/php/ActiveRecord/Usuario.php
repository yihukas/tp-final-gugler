<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/ActiveRecord/ActiveRecord.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/ValueObject/UsuarioVO.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/Singleton/BaseDeDatos.php';

class Usuario extends ActiveRecord
{
	private $_vo;

	public function get()
	{
		return $this->_vo;
	}

	public function set(ValueObject $value)
	{
		$this->_vo = $value;
	}

	public function fetch($id)
	{
		$pdo = BaseDeDatos::getInstancia()->getConexion();

		$query = "select * from usuario where idusuario = :id";

		$stmt = $pdo->prepare($query);
		$stmt->bindvalue(":id", $id, PDO::PARAM_INT);
		$stmt->execute();

		$vo = null;
		$resultado = $stmt->fetchObject();

		if ( $resultado != null )
		{
			$vo = new UsuarioVO();
			$vo->idUsuario = $resultado->idusuario;
			$vo->idPersona = $resultado->idpersona;
			$vo->idTipoUsuario = $resultado->idtipousuario;
			$vo->nombre = $resultado->nombre;
			$vo->contrasenia = $resultado->contrasenia;
		}

		$this->_vo = $vo;
	}

	public function insert()
	{
		$pdo = BaseDeDatos::getInstancia()->getConexion();

		$query = "insert into usuario (idpersona,idtipousuario,nombre,contrasenia)
				values (:idPersona, :idTipoUsuario, :nombre, :contrasenia)";

		$stmt = $pdo->prepare($query);
		$stmt->bindvalue(":idPersona", $this->_vo->idPersona, PDO::PARAM_INT);
		$stmt->bindvalue(":idTipoUsuario", $this->_vo->idTipoUsuario, PDO::PARAM_INT);
		$stmt->bindvalue(":nombre", $this->_vo->nombre, PDO::PARAM_STR);
		$stmt->bindvalue(":contrasenia", $this->_vo->contrasenia, PDO::PARAM_STR);
		$stmt->execute();

		$this->_vo->idUsuario = $pdo->lastInsertId();
	}

	public function update()
	{
		$pdo = BaseDeDatos::getInstancia()->getConexion();

		$query = "update usuario set
				idpersona = {$this->_vo->idPersona},
				idtipousuario = {$this->_vo->idTipoUsuario},
				nombre = '{$this->_vo->nombre}',
				contrasenia = '{$this->_vo->contrasenia}'
			where idusuario = :idUsuario";

		$stmt = $pdo->prepare($query);
		$stmt->bindvalue(":idUsuario", $this->_vo->idUsuario, PDO::PARAM_INT);
		$stmt->execute();
	}

	public function delete($id)
	{
		$pdo = BaseDeDatos::getInstancia()->getConexion();

		$query = "delete from usuario where idusuario = :id";

		$stmt = $pdo->prepare($query);
		$stmt->bindvalue(":id", $id, PDO::PARAM_INT);
		$stmt->execute();
	}

	public function validarContrasenia()
	{
		if ( strlen($this->_vo->contrasenia) < 6 )
		{
			return false;
		}

		$regExp = '/([a-z][0-9]|[0-9][a-z])+/i';

		if ( preg_match($regExp, $this->_vo->contrasenia) == false )
		{
			return false;
		}

		return true;
	}

	public function getContraseniaEnmascadara()
	{
		return preg_replace(array('/./'), '*', $this->_vo->contrasenia);
	}
}