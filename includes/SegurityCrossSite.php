<?php

class SegurityCrossSite
{
    private $_token;
	private static $_singleton;

	private function __construct()
	{
		if ( isset($_SESSION['token']) == true || $_SESSION['token'] == true )
		{
			$this->_token = md5(uniqid(rand(), true));
			$_SESSION['token'] = $this->_token;
		}
	}

	public static function getInstancia()
	{
		if( self::$_singleton == null )
		{
			self::$_singleton = new SegurityCrossSite;
		}
		return self::$_singleton;
	}

	public function getToken()
	{
		return $this->_token;
	}

	public function existeToken()
	{
		if ( ($_SESSION['token'] == $this->_token))
		{
			return true;
		}else{
			return false;
		}
	}

    public function validarToken()
	{
		if ( ($_SESSION['token'] == $this->_token) and ($_SESSION['token'] == $_POST['token']))
		{
			return true;
		}else{
			return false;
		}
	}

	public function enviarToken(SegurityCrossSite $oSSCS)
	{
		return "<input type='hidden' name='token' value='".$oSSCS->getToken()."'>";
	}

}
?>