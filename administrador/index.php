<?php
session_start();
//CROSS-SITE
$token = md5(rand(15000));
$_SESSION['token']=$token;

//SESSION HIJACKING
$_SESSION['HUA'] = md5($_SERVER['HTTP_USER_AGENT']);
header('location: listar.php');