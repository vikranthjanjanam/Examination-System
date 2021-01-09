<!DOCTYPE html>
<?php
    session_start();
    $errmsg = "";
    $frmName = "";
    $err = FALSE;
    require("db.php");
    
?>

<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        if(isset($_POST['create'])){
                $regId = $mysqli->escape_string($_POST['sid']);
                $pres = $mysqli->query("SELECT * from staff WHERE UserID = '$regId'");
                        
                if($pres->num_rows == 0){
                
                    $email = $mysqli->escape_string($_POST['email']);
                    $name = $mysqli->escape_string($_POST['name']);
                    $dept = $mysqli->escape_string($_POST['dept']);
                    $desg = $mysqli->escape_string($_POST['desg']);
                    $phn = $mysqli->escape_string($_POST['phn']);
                    $pass = $mysqli->escape_string($_POST['pass']);
                    $pass1 = $mysqli->escape_string(password_hash($_POST['pass'], PASSWORD_BCRYPT));
                    $to = $email;
                    $subject = "RVR&JC Staff Service";
                    $message = "Hello " . $name
                            . "\n\tWe created an examination conducting account for you, with the following credentials.\n"
                            . "\t\tUserID : " . $regId
                            . "\n\t\tPassword : " . $pass
                            . "\n\tUse them to login to your account and schedule different exams.";
                    try {
                        $retval = mail ($to,$subject,$message);
                    } catch (Exception $exc) {
                        $errmsg = $exc->getTraceAsString();
                    } finally {
                        $errmsg = $errmsg . "<br>Mail can't be sent due to network error!!!";
                    }

                    if( $retval == true ) {
                        $errmsg="Mail Sent Succesfully";
                        $res=$mysqli->query("INSERT INTO `staff` (`Name`, `UserID`, `Designation`, `Dept`, `Phone`, `Email`, `Password`) VALUES"
                                . " ('$name', '$regId', '$desg', '$dept', '$phn', '$email', '$pass1')");
                        if($res == TRUE){
                            $errmsg = $errmsg . "<br>Created account for '$name'";
                        }
                        else{
                            $errmsg = $errmsg . "<br>But Error occured!! Please try again";
                        }
                    }
                    else {
                        $errmsg = "Message could not be sent to mail...<br>Please try again.";
                    }
                }
                else{
                    $errmsg="Duplicate UserID!!";
                }
                $err=TRUE;
                $frmName="AddFaculty";
        }
        
        elseif(isset($_POST['remov'])){
            
            if(!empty($_POST['sid'])){
                foreach ($_POST['sid'] as $sel){
                    $res = $mysqli->query("DELETE FROM `staff` WHERE `staff`.`UserID` = '$sel'");
                    if($res == FALSE){
                        $errmsg = "Remove operation intrupted!!<br>Please try again.";
                        break;
                    }
                }
            }
            $err = TRUE;
            $frmName = "RemoveFaculty";
        }
    }

?>
<html>
	<head>
		<meta charset="UTF-8">
		<title>RVR&JC | Admin</title>
		<link rel="stylesheet" href="css/adminStyle.css" media="screen" type="text/css" />
		<script type="text/javascript" src="js/index.js"></script>
	</head>

	<body>
		<header>
			<div class="top-header">
				<div class="container">
                                        <a href="adminUI.php" id="headr">
						<img class="logo" src="Images/RVRLogo.jpg"/>
						<div class="linkText">
							<h1 class="siteTitle">RVR&JC Examination System</h1>
						</div>
					</a>
				</div>
			</div>
			
			<div id="bottom-header">
				<nav id="headMenu">
					<ul class="menu">
						<li><a href="#" class="actual" pid="AddFaculty" onclick="changeMenu(this)">Add Faculty</a></li>
						<li><a href="#" class="actual" pid="RemoveFaculty" onclick="changeMenu(this)">Remove Faculty</a></li>
                                        <ul class="menu1">
                                                <li><a href="adminLogout.php" class="actual" pid="Logout">Logout</a></li>
                                                <li><a href="#" class="actual" pid="EditProfile" onclick="changeMenu(this)"><?php echo $_SESSION['Name'];?></a></li>
						<div class="clear"/>
					</ul>
					</ul>
				</nav>
			</div>
		</header>
		
		<div id="error">
                    <h4 id="errmsg"></h4>
                </div>
		
            <div class="pages" id="Home"></div>            
            
		<div class="pages" id="AddFaculty">
			<div class="login">
				<h1>Add Faculty</h1>
                                <form name="addFaculty" action="#" method="post" autocomplete="off">
					<input name="name" type="text" placeholder="Name *" required/>
					<input name="sid" type="text" placeholder="Staff ID *" required/>
					<input name="dept" type="text" placeholder="Department *" required/>
					<input name="desg" type="text" placeholder="Designation *" required/>
                                        <input name="phn" type="text" placeholder="Contact No *" onblur='verifyPhone("addFaculty")' required/>
					<input name="email" type="email" placeholder="Email *" required/>
					<input name="pass" type="password" placeholder="Password *" onblur='validPassword("addFaculty")' required/>
                                        <input name="conPass" type="password" placeholder=" Confirm Password *" onblur='conPassword("addFaculty")' required />
                                        <input name="create" type="Submit" value="Create Account" onclick='verifyPhone("addFaculty");validPassword("addFaculty");conPassword("addFaculty");'/>
					
				</form>	
			</div>
		</div>
            
		<div class="pages" id="RemoveFaculty">
                    <div id="scrolls">
			<?php
                        
                            if($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['search'])){
                                
                                $sid = $mysqli->escape_string($_POST['sid']);
                                $res = $mysqli->query("SELECT * FROM `staff` WHERE `UserID` = '$sid'");
                                $err = TRUE;
                            }
                            else{
                                $res = $mysqli->query("SELECT * FROM `staff`");
                            }
                            
                                if($res->num_rows > 0){
                                    echo '<form id="Staffremov" action="adminUI.php" method="post" autocomplete="off">'
                                        . '<table class="responstable">'
                                        . '<tr>'
                                            . '<th> </th>'
                                            . '<th>Name</th>'
                                            . '<th>Staff ID</th>'
                                            . '<th>Designation</th>'
                                            . '<th>Depatment</th>'
                                        . '</tr>';
                                    while($row = $res->fetch_assoc()){
                                        echo '<tr>'
                                            . '<td><input name="sid[]" type="checkbox" value=' . $row['UserID'] . '></td>'
                                            . '<td>' . $row['Name'] . '</td>'    
                                            . '<td>' . $row['UserID'] . '</td>'
                                            . '<td>' . $row['Designation'] . '</td>'
                                            . '<td>' . $row['Dept'] . '</td>'
                                            . '</tr>';
                                    }
                                    echo '</table>'
                                        . '<input name="remov" type="submit" value="Remove"/>'
                                        . '</form>';
                                        
                                }
                                else{
                                    $errmsg = 'No Staff is Assigned';
                                    $err = TRUE;
                                }
                                
                                $frmName = "RemoveFaculty";
                        
                        ?>
                    </div>
                    <div class="login" id="sSearch">
				<h1>Remove Staff</h1>
				<form name="rmvStaff" action="#" method="post" autocomplete="off">
                                    <input name="sid" type="text" placeholder="Staff ID *" required/>
                                    <input name="search" type="Submit" value="Search" />
				</form>
                    </div>
		</div>
            
		<div class="pages" id="EditProfile">
			<div class="login">
				<h1>Edit Profile</h1>
				<form name="editProfile" action="#" method="post" autocomplete="off">
                                    <p>NAME<input name="Name" type="text" ></p>
                                    <p>EMAIL<input name="Email" type="email"></p>
                                    <input name="pass" type="password" placeholder="Password *" onblur='validPassword("editProfile")' required/>
                                    <input name="EditProfile" type="Submit" value="DONE" />
				</form>
			</div>
		</div>
		
            <?php
                echo '<script type="text/javascript">';
                
                if($err == TRUE){
                    echo 'var arg1 = "' . $errmsg . '";'
                        . 'var arg2 = "' . htmlentities($frmName) . '";'
                        . "unhideError(arg1);"
                        . "hideDiv(arg2);";
                    $err = FALSE;
                }
                echo '</script>';
            ?>
            <?php print_r($_SESSION);?>
        
    </body>
</html>