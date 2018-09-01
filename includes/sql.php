<?php
$con = new PDO("mysql:host=localhost;dbname=dashboard", "root", "");
session_start();

function CheckLogin()
{
	if(!isset($_SESSION['uID']))
	{
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=login.php">';    
		exit;
	}
}
?>