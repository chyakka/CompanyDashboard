<?php
include 'includes/sql.php';
CheckLogin();

$imgfile = $_POST['deletefile'];

$img = "uploads/$imgfile";


if(isset($_POST['deletefile']))
{
	unlink($img);
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
}
?>