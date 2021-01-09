<!DOCTYPE html>

<?php
    require("db.php");
    $err=FALSE;
    $errmsg="";
    $frmName="";
    session_start();
?>

<html>
    <head>
        <meta charset="UTF-8">
	<title>RVR&JC | Exam</title>
	<link rel="stylesheet" href="css/style.css" media="screen" type="text/css" />
	<script type="text/javascript" src="js/index.js"></script>
    </head>
	
    <?php
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            if(isset($_POST['Rst'])){
                $regId = $_SESSION['regId'] = $mysqli->escape_string($_POST['regId']);
                $pres = $mysqli->query("SELECT * FROM `student` WHERE `UserID` = '$regId'");
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
                $err = TRUE;
            }
            
            elseif(isset($_POST['Login'])){
                
                $userID = $mysqli->escape_string($_POST['userId']);
                $result1 = $mysqli->query("SELECT * from Student WHERE UserID REGEXP '$userID'");

                $frmName="StudentLogin";
                
                if($result1->num_rows == 0){
                    $errmsg = "Invalid UserID!!!";
                    $err=TRUE;
                }
                else{
                    $user = $result1->fetch_assoc();
                    if(password_verify($_POST['pass'], $user['Password'])){
                    
                        $_SESSION['UserID'] = $user['UserID'];
                        $_SESSION['Email'] = $user['Email'];
                        $_SESSION['Name']=$user['Name'];
                        $_SESSION['Branch']=$user['Branch'];
                        $_SESSION['Phone']=$user['Phone'];
                        $_SESSION['gender']=$user['Gender'];
                        $_SESSION['qual']=$user['Qualification'];
                        $_SESSION['clg']=$user['College'];
                        $_SESSION['year']=$user['Year'];
                        $_SESSION['logged_in'] = true;
                        
                        header("location: studentUI.php");
                    }
                    else{
                        $errmsg = "Invalid Password!!!";
                        $err=TRUE;
                        session_unset();
                    }
                }
            }
            //Registration
            elseif(isset($_POST['Registr'])){
                $regId = $_SESSION['regId'] = $mysqli->escape_string($_POST['regId']);
                $pres = $mysqli->query("SELECT * from Student WHERE UserID = '$regId'");
                        
                $frmName="Register";
                if($pres->num_rows == 0){
                
                    $email = $_SESSION['email'] = $mysqli->escape_string($_POST['umail']);
                    $name = $_SESSION['name'] = $mysqli->escape_string($_POST['name']);
                    $_SESSION['gender'] = $mysqli->escape_string($_POST['gender']);
                    $_SESSION['qual'] = $mysqli->escape_string($_POST['qual']);
                    $_SESSION['branch'] = $mysqli->escape_string($_POST['branch']);
                    $_SESSION['year'] = $mysqli->escape_string($_POST['year']);
                    $_SESSION['clg'] = $mysqli->escape_string($_POST['clg']);
                    $_SESSION['phone'] = $mysqli->escape_string($_POST['phone']);
                    $_SESSION['pass1'] = $mysqli->escape_string(password_hash($_POST['pass'], PASSWORD_BCRYPT));
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
                        }
                        else{
                            $errmsg = $errmsg . "<br>But Error occured! Please try again";
                            session_unset();
                        }
                        
                        $err=TRUE;
                        $frmName="CodeVerify";
                    }
                    else {
                        $errmsg = "Message could not be sent to your mail...<br>Please try again.";
                        $err=TRUE;
                        session_unset();
                    }
                }
                else{
                    $errmsg="Duplicate UserID!!";
                    $err=TRUE;
                    session_unset();
                }
            }

            elseif(isset($_POST['ResetPassword'])){
                
                $hash = $mysqli->escape_string($_POST['code']);
                $pass = $mysqli->escape_string(password_hash($_POST['pass'], PASSWORD_BCRYPT));
                $regId = $_SESSION['regId'];
                $cResult = $mysqli->query("SELECT * from TempCode WHERE RegID = '$regId'");
                $userCode=$cResult->fetch_assoc();
                if($hash==$userCode['Code']){
                    $res = $mysqli->query("UPDATE `student` SET `Password` = '$pass' WHERE `student`.`UserID` = '$regId'");
                    if ($res==TRUE){
                        $res=$mysqli->query("DELETE FROM `tempcode` WHERE `tempcode`.`RegID` = '$regId'");
                        $errmsg="Succesfully created account.<br>Login to your Account.";
                        $err = TRUE;
                        header("location:admin.php");
                    }
                    else{
                        $errmsg="Falied To Register\nPlease Try Again Later.";
                        $err = TRUE;
                        session_unset();
                    }
                }
                else{
                    $errmsg="Invalid code!!!";
                    $err=TRUE;
                } 
            }
            elseif(isset($_POST['Create'])){
                
                $hash = $mysqli->escape_string($_POST['code']);
                $regId = $_SESSION['regId'];
                $cResult = $mysqli->query("SELECT * from TempCode WHERE RegID = '$regId'");
                $userCode=$cResult->fetch_assoc();
                if($hash==$userCode['Code']){
                    $name = $_SESSION['name'];
                    $gender = $_SESSION['gender'];
                    $email = $_SESSION['email'];
                    $qual = $_SESSION['qual'];
                    $branch = $_SESSION['branch'];
                    $year = $_SESSION['year'];
                    $clg = $_SESSION['clg'];
                    $phone = $_SESSION['phone'];
                    $pass1 = $_SESSION['pass1'];
                    $insert = "INSERT INTO `student` (`Name`, `UserID`, `Gender`, `Qualification`, `Branch`, `Year`, `College`, `Email`, `Phone`, `Password`) VALUES "
                            . "('$name', '$regId', '$gender', '$qual', '$branch', '$year', '$clg', '$email', '$phone', '$pass1')";
                    $res=$mysqli->query($insert);
                    if ($res==TRUE){
                        $res=$mysqli->query("DELETE FROM `tempcode` WHERE `tempcode`.`RegID` = '$regId'");
                        $errmsg="Succesfully created account.<br>Login to your Account.";
                        $err = TRUE;
                        header("location:studentUI.php");
                    }
                    else{
                        $errmsg="Falied To Register\nPlease Try Again Later.";
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
            <div>
		<div class="top-header">
                    <div class="container">
			<a href="index.php" id="headr" onclick="hideError();">
                            <img class="logo" src="Images/RVRLogo.jpg">
                                <div class="linkText">
                                    <h1 class="siteTitle">RVR&JC Examination System</h1>
				</div>
			</a>
                    </div>
		</div>
			
		<div id="bottom-header">
                    <nav id="headMenu">
			<ul class="menu" onclick="hideError();">
                            <li><a href="#" class="actual" pid="Home" onclick="changeMenu(this)">Home</a></li>
                            <li><a href="#" class="actual" pid="StudentLogin" onclick="changeMenu(this)">Login</a></li>
                            <li><a href="#" class="actual" pid="Register" onclick="changeMenu(this)">Register</a></li>
                            <li><a href="https://www.rvrjcce.ac.in">About College</a></li>
                            <div class="clear"></div>
			</ul>
                    </nav>
		</div>
            </div>
	</header>
        
        <div id="error">
            <h4 id="errmsg"></h4>
        </div>
      
        
        <div class="pages" id="CodeVerify">
            <div id="login">
                <h1>Verification</h1>
                <form name="verifyc" action="index.php" method="post" autocomplete="off"/>
                    <input name="code" type="text" placeholder="Enter Code *" required/>
                    <input type="submit" name="Create" value="Create Account"/>
                </form>
            </div>           
        </div>
        
        <div class="pages" id="PassCodeVerify">
            <div id="login">
                <h1>Change Password</h1>
                <form name="verify" action="index.php" method="post" autocomplete="off"/>
                    <input name="code" type="text" placeholder="Enter Code *" required/>
                    <input name="pass" type="password" placeholder="Password *" onblur='validPassword("verify")' required/>
                    <input name="conPass" type="password" placeholder="Confirm Password *" onblur='conPassword("verify")' required />
                    <input type="submit" name="ResetPassword" value="Reset Password" onclick='validPassword("verify");conPassword("verify")'/>
                </form>
            </div>           
        </div>
	
        <div class="pages" id="StudentLogin">
            <div id="scrolls">
		<h4 style="margin:20px">Welcome to RVR&JC Examination</h4>
            </div>
            
            <div id="login">
                <h1>Login</h1>
		<form name="student" action="index.php" method="post" autocomplete="off">
                    <input name="userId" type="text" placeholder="User ID" required/>
                    <input name="pass" type="password" placeholder="Password" onblur='validPassword("student")' required/>
                    <input type="submit" name="Login" value="Log in" onclick='validPassword("student")'/>
                    <pre id="note"><a href="#" pid="ForgetPassword" onclick="changeMenuHelp(this);hideError();">Forget Password</a>			  <a href="#" pid="Register" onclick="changeMenuHelp(this)">Create Account</a></pre>
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
	
        <div class="pages" id="Home">
			
	</div>
	
        <div class="pages" id="ForgetPassword">
            <div id="scrolls">
		<h4 style="margin:20px">Welcome to RVR&JC Examination</h4>
                <marquee>Welcome to rvr</marquee>
            </div>
            
            <div id="login">
		<h1>Account Help</h1>
		<form name="accountHelp" action="index.php" method="post" autocomplete="off">
                    <input name="regId" type="text" placeholder="User ID *" required/>
                    <input type="submit" name="Rst" value="Reset Password"/>
		</form>
            </div>
	</div>
	
        <div class="pages" id="Register">
            <div id="scrolls">
		<h4 style="margin:20px">Welcome to RVR&JC Examination</h4>
		<marquee>Welcome to rvr</marquee>
            </div>
            <div id="login">
		<h1>Register</h1>
                <form name="register" action="index.php" method="post" autocomplete="off">
                    <input name="name" type="text" placeholder="Name *" required/>
                    <input name="regId" type="text" placeholder="Register ID *" required/>
                    <select name="gender" onblur='validGender("register")'>
			<option selected disabled hidden>Gender *</option>
                        <option value="Male" name="gender">Male</option>
			<option value="Female" name="gender">Female</option>
			<option value="Other" name="gender">Other</option>
                    </select>
                    <input name="qual" type="text" placeholder="Qualification *" required/>
                    <input name="branch" type="text" placeholder="Branch *" required/>
                    <input name="year" type="text" placeholder="Year Of Study *" required/>
                    <input name="clg" type="text" placeholder="College *" required/>
                    <input name="umail" type="email" placeholder="Email ID *" required/>
                    <input name="phone" type="text" placeholder="Contact number"/>
                    <input name="pass" type="password" placeholder="Password *" onblur='validPassword("register")' required/>
                    <input name="conPass" type="password" placeholder=" Confirm Password *" onblur='conPassword("register")' required />
                    <input type="submit" name="Registr" value="Register" onclick='validGender("register");validPassword("register");conPassword("register")'/>
                    <pre id="note">Already Registered?			  <a href="#" pid="StudentLogin" onclick='changeMenuHelp(this);hideError();'>Sign in</a></pre>
                </form>
            </div>
	</div>
    </body>
</html>