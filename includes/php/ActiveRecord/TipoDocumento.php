<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/ActiveRecord/ActiveRecord.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/ValueObject/TipoDocumentoVO.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/Singleton/BaseDeDatos.php';

class TipoDocumento extends ActiveRecord
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

		$query = "select * from tipodocumento where idtipodocumento = $id";

		$stmt = $pdo->prepare($query);
		$stmt->bindvalue(":id", $id, PDO::PARAM_INT);
		$stmt->execute();

		$vo = null;
		$resultado = $stmt->fetchObject();

		if ( $resultado != null )
		{
			$vo = new TipoDocumentoVO();
			$vo->idTipoDocumento = $resultado->idtipodocumento;
			$vo->nombre = $resultado->nombre;
			$vo->descripcion = $resultado->descripcion;
		}

		$this->_vo = $vo;
	}

	public function insert()
	{
		$pdo = BaseDeDatos::getInstancia()->getConexion();

		$query = "insert into tipodocumento (nombre,descripcion) values (:nombre, :descripcion)";

		$stmt = $pdo->prepare($query);
		$stmt->bindvalue(":nombre", $this->_vo->nombre, PDO::PARAM_STR);
		$stmt->bindvalue(":descripcion", $this->_vo->descripcion, PDO::PARAM_STR);
		$stmt->execute();

		$this->_vo->idTipoDocumento = $pdo->lastInsertId();
	}

	public function update()
	{
		$pdo = BaseDeDatos::getInstancia()->getConexion();

		$query = "update tipodocumento set
				nombre = '{$this->_vo->nombre}',
				descripcion = '{$this->_vo->descripcion}'
			where idtipodocumento = :idTipoDocuemento";

			$stmt = $pdo->prepare($query);
			$stmt->bindvalue(":idTipoDocuemento", $this->_vo->idTipoDocuemento, PDO::PARAM_INT);
			$stmt->execute();
	}

	public function delete($id)
	{
		$pdo = BaseDeDatos::getInstancia()->getConexion();

		$query = "delete from tipodocumento where idtipodocumento = :id";

		$stmt = $pdo->prepare($query);
		$stmt->bindvalue(":id", $id, PDO::PARAM_INT);
		$stmt->execute();
	}

	public function fetchAll()
	{
		$pdo = BaseDeDatos::getInstancia()->getConexion();

		$query = "select * from tipodocumento";

		$stmt = $pdo->query($query);

		$resultados = array();

		while ( ( $resultado = $stmt->fetchObject() ) != false )
		{
			$vo = new TipoDocumentoVO();
			$vo->idTipoDocumento = $resultado->idtipodocumento;
			$vo->nombre = $resultado->nombre;
			$vo->descripcion = $resultado->descripcion;

			$resultados[] = $vo;
		}

		return $resultados;
	}
}