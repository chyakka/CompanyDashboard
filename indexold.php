<?php
include 'includes/sql.php';
CheckLogin();

$sesuID = $_SESSION['uID'];

$query = $con->prepare("SELECT * from users where UserID = '$sesuID'");
$query->execute();
$gData = $query->fetch();
?>
<div class="row">
    <div class="col-md-12">
       	<h1 class="page-header">
            User Area
        </h1>
   	</div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
               	<?php echo $_SESSION['uUsername']; if($gData['Tech'] != 0) echo(" - Tech Administrator");
				else if($gData['HumanResources']) echo(" - Human Resources");
				else if($gData['Exec']) echo(" - Executives Team");
				else if($gData['Animation']) echo(" - Animation Team");
				else if($gData['Marketing']) echo(" - Marketing Team");
				else echo(" - Contact Tech to setup your department.");
				 ?>
			</div>
			<div class="panel-body">
				<p> Company MOTD:</p>
				<div class="col-lg-8">
                	<div class="panel-body">
                    	<div class="row">
                   			<div class="col-lg-6">
	                        <p>
	                            TEXTTEXTTEXTTEXTTEXTTEXTTEXTTEXTTEXTTEXTTEXTTEXT
	                           	TEXTTEXTTEXTTEXTTEXTTEXTTEXTTEXTTEXTTEXTTEXTTEXT
	                        </p>
	                        </div>
	                    </div>
	                </div>
	            </div>
			</div>
		</div>
	</div>
    </div>
</div>
