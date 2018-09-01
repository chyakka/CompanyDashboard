<?php
include 'includes/sql.php';
CheckLogin();

$file = $_FILES["file"];

move_uploaded_file($file["tmp_name"], "uploads/" . $file["name"]);

echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';

?>