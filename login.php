<?php
include 'includes/sql.php';

if(isset($_SESSION['uID']))
{
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';   
    exit;
}

if(isset($_POST['uname']) && isset($_POST['pword']))
{
    if(!isset($_SESSION['uname']))
    {
        $query = $con->prepare("SELECT `UserID`, `Username`, `Tech`, `Marketing`, `Animation`, `HumanResources`, `Exec` from `users` where `Username` = ? and `Password` = ?");
        $query->execute(array($_POST['uname'], strtoupper(hash("whirlpool", $_POST['pword']))));
        if($query->rowCount() > 0)
        {
            $data = $query->fetch();

            $_SESSION['uID'] = $data['UserID'];
            $_SESSION['uUsername'] = $data['Username'];
            $_SESSION['uTech'] = $data['Tech'];
            $_SESSION['uMarketing'] = $data['Marketing'];
            $_SESSION['uAnimation'] = $data['Animation'];
            $_SESSION['uHumanResources'] = $data['HumanResources'];
            $_SESSION['uExec'] = $data['Exec'];

            echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';  
            exit;
        }
        else
        {
            $err = 'Wrong username or password';
        }
    }
}
?>
<!DOCTYPE html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="assets/css/custom-styles.css" <?php echo time(); ?>" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />  
</head>

<div class="container">
	<div class="row">
		<div class="Absolute-Center is-Responsive">
			<div class="col-sm-12 col-md-10 col-md-offset-1">
                <p class="main-title">Dashboard</p>
				<form action="login.php" name="form" id="form" enctype="multipart/form-data" method="POST">
				<div class="form-group input-group">
					<label>Username</label>
					<input class="form-control" type="text" id="uname" name='uname' placeholder="username"/>          
				</div>
				<div class="form-group input-group">
					<label>Password</label>
					<input class="form-control" type="password" id="pword" name='pword' placeholder="password"/>     
				</div>
                <?php if(isset($err)): ?>
                <b class="help-block" style="color: red;"><?=$err?></b>
                <?php endif; ?>      
				<div class="form-group">
					<button type="submit" class="btn btn-def btn-block login-btn">Login</button>
				</div>
				</form>        
			</div>  
		</div>    
	</div>
</div>