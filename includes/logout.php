<?php
include 'sql.php';
if(isset($_SESSION['uID']))
{
	unset($_SESSION['uID']);
	unset($_SESSION['uUsername']);
	unset($_SESSION['uTech']);
	unset($_SESSION['uMarketing']);
	unset($_SESSION['uAnimation']);
	unset($_SESSION['uHumanResources']);
	unset($_SESSION['uExec']);
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../login.php">';    
	exit;
}
else
{
	header("location:../index.php");
}