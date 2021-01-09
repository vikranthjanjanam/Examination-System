<!DOCTYPE HTML>

<?php
	require 'db.php';
        $err=FALSE;
        $errmsg="";
        $frmName="";
	session_start();
?>

<html>
	<head>
		<meta charset="UTF-8">
		<title>RVR&JC | Admin</title>
		<link rel="stylesheet" href="css/style.css" media="screen" type="text/css" />
		<script type="text/javascript" src="js/index.js"></script>
	</head>
	
        <?php
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                
                if(isset($_POST['Rst'])){
                $regId = $_SESSION['regId'] = $mysqli->escape_string($_POST['regId']);
                $pres = $mysqli->query("SELECT * FROM `admin` WHERE `UserID` = '$regId'");
                $frmName="ForgetPassword";
                if($pres->num_rows == 1){
                    $user = $pres->fetch_assoc();
                    $name = $user['Name'];
                    $email = $user['Email'];
                    $to = $email;
                    $subject = "RVR Account Verification";
                    $message = "Hello " . $name
                        . "\nTo Verify your account, please use the following code.\n\t";
                    $code = strval(mt_rand(99999,1000000));
                    $message .= $code . "\nThis code is valid only for 5min.";
                    try {
                        $retval = mail ($to,$subject,$message);
                    } catch (Exception $exc) {
                        $errmsg = $exc->getTraceAsString();
                    } finally {
                        $errmsg = $errmsg . "<br>Mail can't be sent due to network error!!!";
                    }

                    if( $retval == true ) {
                        $errmsg="Mail Sent Succesfully";
                        $nres = $mysqli->query("SELECT * FROM `tempcode` WHERE `RegID` = '$regId'");
                        if($nres->num_rows == 0){
                            $res=$mysqli->query("INSERT INTO `tempcode` (`RegID`, `Code`) VALUES ('$regId', '$code')");
                        }
                        else{
                            $res=$mysqli->query("UPDATE `tempcode` SET `Code` = '$code' WHERE `tempcode`.`RegID` = '$regId'");
                        }
                        if($res == TRUE){
                            $errmsg = $errmsg . "<br>Code Entered";
                            $err=TRUE;
                            $frmName="PassCodeVerify";
                        }
                        else{
                            $errmsg = $errmsg . "<br>Code not Entered";
                        }
                    }
                    else{
                        $errmsg = "Message could not be sent to your mail...<br>Please try again.";
                        $err=TRUE;
                        session_unset();
                    }
                }
                else{
                    $errmsg = "Invalid UserID";
                }
                
            }
                
                if(isset($_POST['Login'])){
                    
                    $err=TRUE;
                    $frmName="OtherLogin";
                    
                    $userID = $mysqli->escape_string($_POST['userId']);
                    $result = $mysqli->query("SELECT * from admin WHERE UserID = '$userID'");

                    if($result->num_rows == 0){
                	$errmsg = "Invalid UserID!!!";
                    }
                    else{
                        $user = $result->fetch_assoc();
                        if(password_verify($_POST['pass'], $user['Password'])){
                            
                            $pass=$password1=NULL;
                            $_SESSION['UserID'] = $user['UserID'];
                            $_SESSION['Email'] = $user['Email'];
                            $_SESSION['Name'] = $user['Name'];
                            $_SESSION['logged_in'] = true;
                            header("location: adminUI.php");
                        }
                        else{
                            $errmsg = "Invalid Password!!!";
                        }
                    }
                }
                
                elseif(isset($_POST['ResetPassword'])){
                
                $hash = $mysqli->escape_string($_POST['code']);
                $pass = $mysqli->escape_string(password_hash($_POST['pass'], PASSWORD_BCRYPT));
                $regId = $_SESSION['regId'];
                $cResult = $mysqli->query("SELECT * from TempCode WHERE RegID = '$regId'");
                $userCode=$cResult->fetch_assoc();
                if($hash==$userCode['Code']){
                    $res = $mysqli->query("UPDATE `admin` SET `Password` = '$pass' WHERE `UserID` = '$regId'");
                    if ($res==TRUE){
                        $res=$mysqli->query("DELETE FROM `tempcode` WHERE `tempcode`.`RegID` = '$regId'");
                        $errmsg="Succesfully created account.<br>Login to your Account.";
                        $err = TRUE;
                        header("location: adminUI.php");
                    }
                    else{
                        $errmsg="Falied To Reset\nPlease Try Again Later.";
                        $err = TRUE;
                        session_unset();
                    }
                }
                else{
                    $errmsg="Invalid code!!!";
                    $err=TRUE;
                } 
            }
                
            }
        ?>
        
	<body>
		<header>
			<div class="top-header">
				<div class="container">
                                    <a href="admin.php" id="headr" onclick="hideError();">
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
						<li><a href="#" class="actual" pid="OtherLogin" onclick="changeMenu(this);hideError();">Admin Login</a></li>
						<div class="clear"/>
					</ul>
				</nav>
			</div>
                </header>
            
                <div id="error">
                    <h4 id="errmsg"></h4>
                </div>
            
		<div class="pages" id="OtherLogin">
			<div id="scrolls">
					<h4 style="margin:20px">Welcome to RVR&JC Examination</h4>
			</div>
			<div id="login">
				<h1>Admin Login</h1>
                                <form name="admn" action="admin.php" method="post" autocomplete="off">
					<input name="userId" type="text" placeholder="User ID" required/>
					<input name="pass" type="password" placeholder="Password" onblur="validPassword('admn')" required/>
					<input type="submit" name="Login" value="Log in" onclick="validPassword('admn')"/>
					<pre id="note">Need Help?			  <a href="#" pid="ForgetPassword" onclick="changeMenuHelp(this);hideError();">Forget Password</a></pre>
				</form>
			</div>
		</div>
		
		<div class="pages" id="ForgetPassword">
			<div id="scrolls">
				<h4 style="margin:20px">Welcome to RVR&JC Examination</h4>
				<marquee>Welcome to rvr</marquee>
			</div>
			
			<div id="login">
				<h1>Account Help</h1>
                                <form name="adminHelp" action="#" method="post">
					<input name="regId" type="text" placeholder="User ID" required/>
					<input type="submit" name="Rst" value="Reset Password" />
				</form>
			</div>
                    
                </div>
            
                    <div class="pages" id="PassCodeVerify">
                        <div id="login">
                            <h1>Change Password</h1>
                            <form name="verify" action="#" method="post" autocomplete="off"/>
                                <input name="code" type="text" placeholder="Enter Code *" required/>
                                <input name="pass" type="password" placeholder="Password *" onblur='validPassword("verify")' required/>
                                <input name="conPass" type="password" placeholder="Confirm Password *" onblur='conPassword("verify")' required />
                                <input type="submit" name="ResetPassword" value="Reset Password" onclick='validPassword("verify");conPassword("verify")'/>
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
        </body>
</html>