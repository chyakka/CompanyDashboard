<?php
include 'includes/sql.php';
CheckLogin();

$sesuID = $_SESSION['uID'];

$query = $con->prepare("SELECT * from users where UserID = '$sesuID'");
$query->execute();
$gData = $query->fetch();

$motdQuery = $con->prepare("SELECT `companyMOTD`, `companyNotice1`, `companyNotice2`, `companyNotice3` from misc");
$motdQuery->execute();
$gMiscData = $motdQuery->fetch();

$success_modal = false;
$fail_modal = false;

class Calendar
{
    public $dateTime = null;
    public function __construct($dateTime = null)
    {
        $this->dateTime = (isset($dateTime)? $dateTime : new DateTime());
        echo $this->dateTime->format('d-m-Y') . "\n";
    }
}
date_default_timezone_set('GMT');

if(isset($_POST['approve']))
{
    $userloa = $_POST['approve'];
    $approveQuery = $con->prepare("UPDATE loa SET approved = '1', approverid = '$sesuID' WHERE userid = '$userloa'");
    $approveQuery->execute();
    $userloaQuery = $con->prepare("UPDATE users SET OnLeave = '1' WHERE UserID = '$userloa'");
    $userloaQuery->execute();
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=includes/logout.php">';
}

if(isset($_POST['deny']))
{
    $userloa = $_POST['deny'];
    $approveQuery = $con->prepare("DELETE FROM loa WHERE userid = '$userloa'");
    $approveQuery->execute();
    $userloaQuery = $con->prepare("UPDATE users SET OnLeave = '0' WHERE UserID = '$userloa'");
    $userloaQuery->execute();
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=includes/logout.php">';
}

if(isset($_POST['loaname']))
{
    if($_POST['loaname'] != '')
    {
        if($_POST['loareason'] != '')
        {
            if($_POST['loaleave'] != '')
            {
                if($_POST['loadays'] != '')
                {
                    $success_modal = true;
                    $loaDate = $_POST['loaleave'];
                    $loaDays = $_POST['loadays'];
                    $loaReason = $_POST['loareason'];
                    $loaName = $_POST['loaname'];
                    $loaQuery = $con->prepare("INSERT INTO loa (userid, username, leavedate, returndate, reason) VALUES ('$sesuID', '$loaName', '$loaDate', '$loaDays', '$loaReason')");
                    $loaQuery->execute();
                    $_POST = array();
                    unset($_POST['loaleave'], $_POST['loadays'], $_POST['loareason'], $_POST['loaname']);
                    unset($loaDate, $loaDays, $loaQuery, $loaReason);
                    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=includes/logout.php">';  
                }
                else
                {
                    $err = "Invalid days of absence.";
                }
                
            }
            else
            {
                $err = "Invalid leave date.";
            }
        }
        else
        {
            $err = "Invalid reason.";
        }
    }
    else
    {
        $err = "Invalid name.";
    }
}

if(isset($_POST['newmotd']))
{
    if($_POST['newmotd'] != '')
    {
        $newMOTD = $_POST['newmotd'];
        $newMotdQuery = $con->prepare("UPDATE misc SET companyMOTD = '$newMOTD'");
        $newMotdQuery->execute();
        header("Refresh:0");
        unset($newMOTD);
        unset($_POST['newmotd']);
    }
}

if(isset($_POST['newnotice1']))
{
    if($_POST['newnotice1'] != '')
    {
        $newNotice1 = $_POST['newnotice1'];
        $newNoticeQuery = $con->prepare("UPDATE misc SET companyNotice1 = '$newNotice1'");
        $newNoticeQuery->execute();
        header("Refresh:0");
    }
}

if(isset($_POST['newnotice2']))
{
    if($_POST['newnotice2'] != '')
    {
        $newNotice2 = $_POST['newnotice2'];
        $newNoticeQuery = $con->prepare("UPDATE misc SET companyNotice2 = '$newNotice2'");
        $newNoticeQuery->execute();
        header("Refresh:0");
    }
}

if(isset($_POST['newnotice3']))
{
    if($_POST['newnotice3'] != '')
    {
        $newNotice3 = $_POST['newnotice3'];
        $newNoticeQuery = $con->prepare("UPDATE misc SET companyNotice3 = '$newNotice3'");
        $newNoticeQuery->execute();
        header("Refresh:0");
    }
}

if(isset($_POST['newsettingsemail']))
{
    if($_POST['newsettingsemail'] != '')
    {
        $tmpNewEmail = $_POST['newsettingsemail'];
        $settingsAccQuery = $con->prepare("UPDATE users SET Email = '$tmpNewEmail' WHERE UserID = '$sesuID'");
        $settingsAccQuery->execute();
    }
}

if(isset($_POST['newphonenr']))
{
    if($_POST['newphonenr'] != '')
    {
        $tmpNewPhone = $_POST['newphonenr'];
        $settingsAccQuery = $con->prepare("UPDATE users SET PhoneNumber = '$tmpNewPhone' WHERE UserID = '$sesuID'");
        $settingsAccQuery->execute();
    }
}

if(isset($_POST['mname']))
{
    $tmpName = $_POST['mname'];
    $getuserQuery = $con->prepare("SELECT * from users WHERE Username = '$tmpName'");
    $getuserQuery->execute();
    $getuserData = $getuserQuery->fetch();
    if(isset($_POST['modifyFormPerms']))
    {
        $success_modal = true;
        if(($_POST['modifyFormPerms'] == 'T') && $getuserData['Tech'] > 0)
        {
            //remove tech
            $modifyAccQuery = $con->prepare("UPDATE users SET Tech = '0' WHERE Username = '$tmpName'");
            $modifyAccQuery->execute();
        }
        else if($_POST['modifyFormPerms'] == 'T')
        {
            //give tech
            $modifyAccQuery = $con->prepare("UPDATE users SET Tech = '1' WHERE Username = '$tmpName'");
            $modifyAccQuery->execute();
        }
        if(($_POST['modifyFormPerms'] == 'H') && $getuserData['HumanResources'] > 0)
        {
            //remove hr
            $modifyAccQuery = $con->prepare("UPDATE users SET HumanResources = '0' WHERE Username = '$tmpName'");
            $modifyAccQuery->execute();
        }
        else if($_POST['modifyFormPerms'] == 'H')
        {
            //give hr
            $modifyAccQuery = $con->prepare("UPDATE users SET HumanResources = '1' WHERE Username = '$tmpName'");
            $modifyAccQuery->execute();
        }
        if(($_POST['modifyFormPerms'] == 'A') && $getuserData['Animation'] > 0)
        {
            //remove anim
            $modifyAccQuery = $con->prepare("UPDATE users SET Animation = '0' WHERE Username = '$tmpName'");
            $modifyAccQuery->execute();
        }
        else if($_POST['modifyFormPerms'] == 'A')
        {
            //give anim
            $modifyAccQuery = $con->prepare("UPDATE users SET Animation = '1' WHERE Username = '$tmpName'");
            $modifyAccQuery->execute();
        }
        if(($_POST['modifyFormPerms'] == 'M') && $getuserData['Marketing'] > 0)
        {
            //remove marketing
            $modifyAccQuery = $con->prepare("UPDATE users SET Marketing = '0' WHERE Username = '$tmpName'");
            $modifyAccQuery->execute();
        }
        else if($_POST['modifyFormPerms'] == 'M')
        {
            //give marketing
            $modifyAccQuery = $con->prepare("UPDATE users SET Marketing = '1' WHERE Username = '$tmpName'");
            $modifyAccQuery->execute();
        }
    }
    if($_POST['newemail'] != '')
    {
        $tmpNewEmail = $_POST['newemail'];
        $modifyAccQuery = $con->prepare("UPDATE users SET Email = '$tmpNewEmail' WHERE Username = '$tmpName'");
        $modifyAccQuery->execute();
    }
    if($_POST['newname'] != '')
    {
        $tmpNewName = $_POST['newname'];
        $modifyAccQuery = $con->prepare("UPDATE users SET Username = '$tmpNewName' WHERE Username = '$tmpName'");
        $modifyAccQuery->execute();
    }
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=includes/logout.php">';
}

if(isset($_POST['name']) && isset($_POST['word']) && isset($_POST['email']))
{
    $tmpPW = strtoupper(hash("whirlpool", $_POST['word']));
    $tmpUser = $_POST['name'];
    $tmpEmail = $_POST['email'];
    if($_POST['formPerms'] == 'T') //set tech
    {
        $accQuery = $con->prepare("INSERT INTO users (Username, Password, Email, Tech) VALUES ('$tmpUser', '$tmpPW', '$tmpEmail', '1')");
        $accQuery->execute();
    }
    else if($_POST['formPerms'] == 'A') //set animation
    {
        $accQuery = $con->prepare("INSERT INTO users (Username, Password, Email, Animation) VALUES ('$tmpUser', '$tmpPW', '$tmpEmail', '1')");
        $accQuery->execute();
    }
    else if($_POST['formPerms'] == 'M') //set marketing
    {
        $accQuery = $con->prepare("INSERT INTO users (Username, Password, Email, Marketing) VALUES ('$tmpUser', '$tmpPW', '$tmpEmail', '1')");
        $accQuery->execute();
    }
    else if($_POST['formPerms'] == 'H') //set hr
    {
        $accQuery = $con->prepare("INSERT INTO users (Username, Password, Email, HumanResources) VALUES ('$tmpUser', '$tmpPW', '$tmpEmail', '1')");
        $accQuery->execute();
    }
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=includes/logout.php">';  
}

    unset($tmpPW);
    unset($tmpUser);
    unset($tmpEmail);
    unset($accQuery);
    unset($_POST['name']);
    unset($_POST['word']);
    unset($_POST['email']);
    unset($_POST['formPerms']);
    unset($_POST['newname']);
    unset($_POST['modifyFormPerms']);
    unset($_POST['mname']);
    unset($_POST['newemail']);
    unset($tmpName);
    unset($modifyAccQuery);
    unset($tmpNewName);
    unset($getuserData);
    unset($settingsAccQuery);
    unset($tmpNewEmail);
    unset($_POST['newsettingsemail']);
    unset($_POST['newphonenr']);
    unset($newNotice1);
    unset($newNotice2);
    unset($newNotice3);
    unset($newNoticeQuery);
    unset($_POST['newnotice1']);
    unset($_POST['newnotice2']);
    unset($_POST['newnotice3']);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard</title>

    <link href="includes/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/custom-styles.css" rel="stylesheet" />

</head>

<body>

    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="#">
                        Navigation
                    </a>
                </li>
                <?php
                    if(isset($_SESSION['uID'])) :
                ?>
                <li>
                    <a href="index.php" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dashboard</a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#loaModal">Book LOA</a>
                    </div>
                </li>
                <?php
                endif;
                ?> 
                <?php if(isset($_SESSION['uID'])) :
                ?>
                <li>
                    <a href="#" data-toggle="modal" data-target="#settingsModal">Settings</a>
                </li>
                <?php 
                endif; 
                ?>
                <?php 
                    if(isset($_SESSION['uID']) and $_SESSION['uTech'] > 0) :
                ?>
                <li>
                    <a href="departments/tech.php" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tech Area</a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#myModal">Make New User</a>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modifyModal">Modify User</a>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#noticeModal">Modify Notices</a>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#employeeModal">View Employees</a>
                    </div>
                </li>
                <?php
                endif;
                ?> 
                <?php 
                    if(isset($_SESSION['uID']) and $_SESSION['uHumanResources'] > 0) :
                ?>
                <li>
                    <a href="departments/hr.php" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Human Resources Area</a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <!-- <a class="dropdown-item" href="#">Issue Disciplinary</a> -->
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#motdModal">Manage MOTD</a>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#employeeModal">View Employees</a>
                    </div>
                </li>
                <?php
                endif;
                ?> 
            <!--
                <?php 
                    if(isset($_SESSION['uID']) and $_SESSION['uMarketing'] > 0) :
                ?>
                <li>
                    <a href="departments/marketing.php">Marketing Area</a>
                </li>
                <?php
                endif;
                ?> 
            -->
                <?php 
                    if(isset($_SESSION['uID']) and $_SESSION['uAnimation'] > 0) :
                ?>
                <li>
                    <a href="departments/animation.php" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">3D Area</a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#uploadModal">Manage Files</a>
                    </div>
                </li>
                <?php
                endif;
                ?> 
                <?php 
                    if(isset($_SESSION['uID']) and $_SESSION['uExec'] > 0) :
                ?>
                <li>
                    <a href="#">Executive Area</a>
                </li>
                <?php
                endif;
                ?> 
                <?php if(isset($_SESSION['uID'])) :
                ?>
                <li>
                    <a href="includes/logout.php" data-toggle="modal" data-target="#logoutModal"">Logout</a>
                </li>
                <?php 
                endif; 
                ?>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->
        <!-- Page Content -->
        <div id="logoutModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
            <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Are you sure you want to logout?</h4>
                    </div>
                    <div class="modal-body">
                        <form method="POST" enctype="multipart/form-data" action="includes/logout.php">
                            <input type="submit" name="logoutconfirm" value="Logout">
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <div id="uploadModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
            <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Download & Upload Files</h4>
                    </div>
                    <div class="modal-body">
                        <form method="POST" enctype="multipart/form-data" action="upload.php">
                            <input type="file" name="file">
                            <input type="submit" value="Upload">
                        </form>

                        <?php
                        $files = scandir("uploads");
                        for($a = 2; $a < count($files); $a++)
                        {
                            ?>
                            <form action="delete.php" name="form" id="form" enctype="multipart/form-data" method="POST">
                                <div class="form-group form-upload">
                                    <a download="<?php echo $files[$a] ?>" href="uploads/<?php echo $files[$a] ?>"><?php echo $files[$a] ?></a>
                                    <button type="submit" name="deletefile" value="<?php echo $files[$a] ?>" class="btn btn-red">Delete File</button>
                                </div>
                            </form>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div id="loaModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
            <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Post a Leave of Absence.</h4>
                    </div>
                    <div class="modal-body">
                        <p>HR will need to review it on completion. You will also be logged out after you fill out the form.</p>
                        <p>Date Today: <?php $date = new Calendar(); ?>
                        <form action="index.php" name="form" id="form" enctype="multipart/form-data" method="POST">
                            <label>Name</label>
                            <div class="form-group input-group">
                                <input class="form-control" type="text" id="loaname" name='loaname' maxlength="1024" placeholder="Your name"/>          
                            </div>
                            <label>Reason for Leave</label>
                            <div class="form-group input-group">
                                <input class="form-control" type="text" id="loareason" name='loareason' maxlength="512" placeholder="Reason for LOA"/>          
                            </div>
                            <label>Leave Date (YYYY-DD-MM) </label>
                            <div class="form-group input-group">
                                <input class="form-control" type="text" id="loaleave" name='loaleave' placeholder="Leave date"/>          
                            </div>
                            <label>How many days would you like to take off</label>
                            <div class="form-group input-group">
                                <input class="form-control" type="number" id="loadays" name='loadays' placeholder="Days"/>          
                            </div>
                            <?php if(isset($err)): ?>
                            <b class="help-block" style="color: red;"><?=$err?></b>
                            <?php endif; ?>    
                            <div class="form-group">
                                <p></p>
                                <button type="submit" class="btn btn-def btn-block login-btn">Post LOA Request</button>
                            </div>
                        </form>     
                    </div>
                </div>
            </div>
        </div>
        <div id="noticeModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
            <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Update Company Notices</h4>
                    </div>
                    <div class="modal-body">
                        <p>Use the notices only for company-related matters. Leave blank if you don't want to modify a certain notice.</p>
                        <form action="index.php" name="form" id="form" enctype="multipart/form-data" method="POST">
                            <div class="form-group input-group">
                                <input class="form-control" type="text" id="newnotice1" name='newnotice1' maxlength="1024" placeholder="Notice 1"/>          
                            </div>
                            <div class="form-group input-group">
                                <input class="form-control" type="text" id="newnotice2" name='newnotice2' maxlength="1024" placeholder="Notice 2"/>          
                            </div>
                            <div class="form-group input-group">
                                <input class="form-control" type="text" id="newnotice3" name='newnotice3' maxlength="1024" placeholder="Notice 3"/>          
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-def btn-block login-btn">Save Notices</button>
                            </div>
                        </form>     
                    </div>
                </div>
            </div>
        </div>
        <div id="motdModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
            <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Update Company MOTD</h4>
                    </div>
                    <div class="modal-body">
                        <form action="index.php" name="form" id="form" enctype="multipart/form-data" method="POST">
                            <div class="form-group input-group">
                                <input class="form-control" type="text" id="newmotd" name='newmotd' maxlength="1024" placeholder="Message of the Day"/>          
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-def btn-block login-btn">Save MOTD</button>
                            </div>
                        </form>     
                    </div>
                </div>
            </div>
        </div>
        <div id="failModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
            <!-- Modal content-->
                <div class="modal-content modal-fail">
                    <div class="modal-header">
                        <h4 class="modal-title">Operation unsuccessful!</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <button type="button" class="btn btn-default btn-red" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
        <div id="successModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
            <!-- Modal content-->
                <div class="modal-content modal-success">
                    <div class="modal-header">
                        <h4 class="modal-title">Operation successful!</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <button type="button" class="btn btn-default btn-green" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
         <div id="employeeModal" class="modal fade modal-employee" role="dialog">
            <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Employees</h4>
                </div>
            <div class="modal-body">
                <table>
                <tr class="spaceUnder">
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                </tr>
                <?php
                $connection = mysqli_connect("localhost", "root", "", "dashboard");
                $result =mysqli_query($connection,"SELECT Username, Email, PhoneNumber FROM users ORDER BY Username");
                if(mysqli_num_rows($result) > 0)
                {
                    while($row = mysqli_fetch_array($result))
                    {
                        echo "<tr>";
                            echo "<td>" . $row['Username'] . "</td>";
                            echo "<td>" . $row['Email'] . "</td>";
                            echo "<td>" . $row['PhoneNumber'] . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    // Free result set
                    mysqli_free_result($result);
                    } 
                    else
                    {
                    echo "No records matching your query were found.";
                }
                ?>
            </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
          </div>
        </div>
        <div id="settingsModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Settings</h4>
                </div>
            <div class="modal-body">
                <form action="index.php" name="form" id="form" enctype="multipart/form-data" method="POST">
                    <div class="form-group input-group">
                        <input class="form-control" type="text" id="newsettingsemail" name='newsettingsemail' placeholder="New Email Address"/>          
                    </div>
                    <div class="form-group input-group">
                        <input class="form-control" type="text" id="newphonenr" name='newphonenr' placeholder="New Phone Number"/>          
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-def btn-block login-btn">Save Settings</button>
                    </div>
                </form>        
            </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

          </div>
        </div>
        <div id="modifyModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modify existing user</h4>
                </div>
            <div class="modal-body">
                <form action="index.php" name="form" id="form" enctype="multipart/form-data" method="POST">
                    <p>If you tick a permission a user already has it will remove it.</p>
                    <div class="form-group input-group">
                        <input class="form-control" type="text" id="mname" name='mname' placeholder="username"/>          
                    </div>
                    <div class="form-group input-group">
                        <input class="form-control" type="text" id="newname" name='newname' placeholder="new username (optional)"/>          
                    </div>
                    <div class="form-group input-group">
                        <input class="form-control" type="text" id="newemail" name='newemail' placeholder="new email (optional)"/>          
                    </div>
                    <p>Permissions</p>
                    <div class="checkbox">
                        <label><input type="checkbox" name="modifyFormPerms" value="T">Tech</label>
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" name="modifyFormPerms" value="A">Animation</label>
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" name="modifyFormPerms" value="H">Human Resources</label>
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" name="modifyFormPerms" value="M">Marketing</label>
                    </div>
                    <?php if(isset($err)): ?>
                    <b class="help-block" style="color: red;"><?=$err?></b>
                    <?php endif; ?>      
                    <div class="form-group">
                        <button type="submit" class="btn btn-def btn-block login-btn">Modify Account</button>
                    </div>
                </form>        
            </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

          </div>
        </div>
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add new user</h4>
                </div>
            <div class="modal-body">
                <p>Make sure to email the details to the correct address on completion. You will also be logged out after you fill out the form.</p>
                <form action="index.php" name="form" id="form" enctype="multipart/form-data" method="POST">
                    <div class="form-group input-group">
                        <input class="form-control" type="text" id="name" name='name' placeholder="username"/>          
                    </div>
                    <div class="form-group input-group">
                        <input class="form-control" type="password" id="word" name='word' placeholder="password"/>     
                    </div>
                    <div class="form-group input-group">
                        <input class="form-control" type="text" id="email" name='email' placeholder="email"/>     
                    </div>
                    <p>Permissions</p>
                    <div class="checkbox">
                        <label><input type="checkbox" name="formPerms" value="T">Tech</label>
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" name="formPerms" value="A">Animation</label>
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" name="formPerms" value="H">Human Resources</label>
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" name="formPerms" value="M">Marketing</label>
                    </div>
                    <div class="checkbox disabled">
                        <label><input type="checkbox" name="formPerms" value="E" disabled>Executive</label>
                    </div>
                    <?php if(isset($err)): ?>
                    <b class="help-block" style="color: red;"><?=$err?></b>
                    <?php endif; ?>      
                    <div class="form-group">
                        <button type="submit" class="btn btn-def btn-block login-btn">Create Account</button>
                    </div>
                </form>        
            </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

          </div>
        </div>
        <img src="assets/example-company.png" alt="company logo" class="pull-right"/>
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <br>
                <?php echo "Welcome, ";?>
                <?php echo $_SESSION['uUsername']; if($gData['Tech'] != 0) echo(" - Tech Administrator");
                    else if($gData['HumanResources']) echo(" - Human Resources");
                    else if($gData['Exec']) echo(" - Executives Team");
                    else if($gData['Animation']) echo(" - Animation Team");
                    else if($gData['Marketing']) echo(" - Marketing Team");
                    else echo(" - Contact Tech to setup your department.");
                    echo "<p></p>";
                    if($gData['OnLeave']) echo ("You're currently on Leave of Absence, if this is an error or you've returned from your absence contact Human Resources.");
                ?>
                <p></p>
                <a class="btn btn-primary" data-toggle="collapse" href="#motdCollapse" role="button" aria-expanded="false" aria-controls="motdCollapse">MOTD</a>
                <p></p>
                <div class="collapse multi-collapse" id="motdCollapse">
                    <div class="card card-body">
                        <h1>Message of the Day:</h1>
                        <?php echo $gMiscData['companyMOTD']; ?>
                    </div>
                </div>
                <br>
                <div class="card card-body">
                    <h2>Notices:</h2>
                    <?php echo $gMiscData['companyNotice1']; ?>
                    <p></p>
                    <?php echo $gMiscData['companyNotice2']; ?>
                    <p></p>
                    <?php echo $gMiscData['companyNotice3']; ?>
                </div>

                <!-- HR AREA -->
                <?php if($_SESSION['uHumanResources'] > 0) : ?>
                <br>
                <h4><strong>Review LOA Requests (Human Resources)</strong></h4>
                <p>Only use the delete feature if the LOA has expired.</p>
                <table>
                <tr>
                    <th>Employee Name</th>
                    <th>Reason</th>
                    <th>Requested Leave Date</th>
                    <th>Date LOA Requested</th>
                    <th>Days Requested</th>
                    <th>Approved (1-yes, 0-no)</th>
                </tr>
                <?php
                $connection = mysqli_connect("localhost", "root", "", "dashboard");
                $result =mysqli_query($connection,"SELECT username, reason, approved, requestdate, leavedate, returndate, approved, userid FROM loa ORDER BY Username");
                if(mysqli_num_rows($result) > 0)
                {
                    while($row = mysqli_fetch_array($result))
                    {
                        $id = $row['userid'];
                ?>
                        <tr class="spaceUnder">
                <?php
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['reason'] . "</td>";
                        echo "<td>" . $row['leavedate'] . "</td>";
                        echo "<td>" . $row['requestdate'] . "</td>";
                        echo "<td>" . $row['returndate'] . "</td>";
                        echo "<td>" . $row['approved'] . "</td>";
                ?>
                <td>
                    <?php 
                        if($row['approved'] = 0) :
                    ?>
                        <form action="index.php" name="form" id="form" enctype="multipart/form-data" method="POST">
                            <div class="form-group">
                                <button type="submit" class="btn btn-def btn-green" name="approve" value="<?php echo $id ?>">Approve</button>
                            </div>
                        </form>   
                        <form action="index.php" name="form" id="form" enctype="multipart/form-data" method="POST">
                            <div class="form-group">
                                <button type="submit" class="btn btn-def btn-red" name="deny" value="<?php echo $id ?>">Reject</button>
                            </div>
                        </form>  
                    <?php
                        endif;
                    ?> 
                        <form action="index.php" name="form" id="form" enctype="multipart/form-data" method="POST">
                            <div class="form-group">
                                <button type="submit" class="btn btn-def btn-red" name="deny" value="<?php echo $id ?>">Delete</button>
                            </div>
                        </form>  
                </td>     
                <?php
                        echo "</tr>";
                    }
                    echo "</table>";
                    // Free result set
                    mysqli_free_result($result);
                    } 
                    else
                    {
                    echo "No LOAs have been posted.";
                }
                ?>
                <?php
                endif;
                ?> 
                <br/>
                    </div>
                </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Bootstrap core JavaScript -->
    <script src="includes/vendor/jquery/jquery.min.js"></script>
    <script src="includes/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" charset="utf-8" language="javascript" src="assets/js/jquery.dataTables.js"></script>

    <?php if($success_modal):?>
    <script> $('#successModal').modal('show');</script>
    <?php endif;?>

    <?php if($fail_modal):?>
    <script> $('#failModal').modal('show');</script>
    <?php endif;?>

    <!-- Menu Toggle Script -->

    <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    </script>

    <script>
    function IsChecked($chkname,$value)
    {
        if(!empty($_POST[$chkname]))
        {
            foreach($_POST[$chkname] as $chkval)
            {
                if($chkval == $value)
                {
                    return true;
                }
            }
        }
        return false;
    }
    </script>
    
<div id="page-wrapper">
<div id="page-inner">