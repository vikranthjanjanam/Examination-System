<!DOCTYPE html>

<?php
    require("db.php");
    $err = FALSE;
    $errmsg = "";
    $frmName = "";
    session_start();
?>

<html>
	<head>
		<meta charset="UTF-8">
		<title>RVR&JC | Student</title>
		<link rel="stylesheet" href="css/adminStyle.css" media="screen" type="text/css" />
		<script type="text/javascript" src="js/index.js"></script>
	</head>
        
        <?php
            
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
                if(isset($_POST['enroll'])){
                    
                    if(!empty($_POST['eid'])){
                        $_SESSION['eid'] = array();
                        foreach ($_POST['eid'] as $seleid){
                            array_push($_SESSION['eid'], $seleid);
                        }
                        $err = TRUE;
                        $frmName = "Team";
                        $errmsg = "Login with the Team Member Credentials to form a team."
                                . "<br>If team is unavailable, Login with your Credentials.";
                    }
                    else{
                        $err = TRUE;
                        $frmName = "Exams";
                        $errmsg = "No Exam is Selected";
                    }
                    
                }
                
                else if(isset($_POST['selectTeam'])){
                    $sid2 = $mysqli->escape_string($_POST['userID']);
                    $result1 = $mysqli->query("SELECT * from Student WHERE UserID REGEXP '$sid2'");
                    if($result1->num_rows == 0){
                        $errmsg = "Invalid UserID!!!";
                        $frmName = "Team";
                        $err=TRUE;
                    }
                    else{
                        $sid1 = $_SESSION['UserID'];
                        $user = $result1->fetch_assoc();
                        if(password_verify($_POST['pass'], $user['Password'])){
                            foreach ($_SESSION['eid'] as $seleid){
                                $result1 = $mysqli->query("SELECT * from Enrolled WHERE examid = '$seleid' and ( ( std1 = '$sid1' or std2 = '$sid1' ) or ( std1 = '$sid2' or std2 = '$sid2' ) )");
                                if($result1->num_rows == 0){
                                    $insert = $mysqli->query("INSERT INTO `enrolled` (`examid`, `std1`, `std2`, `result`) VALUES ('$seleid', '$sid1', '$sid2', '0');");
                                    $errmsg = "Enrolled Succesfully";
                                    $frmName = "Enrolled";
                                    $err=TRUE;
                                }
                                else{
                                    $errmsg = "Already enrolled";
                                    $frmName = "Exams";
                                    $err=TRUE;
                                }
                            }
                            unset($_SESSION['eid']);
                        }
                        else{
                            $errmsg = "Invalid Password!!!";
                            $frmName = "Team";
                            $err=TRUE;
                        }
                    }
                }
                
            }
            
        ?>
        
	<body>
		<header>
			<div class="top-header">
				<div class="container">
                                        <a href="studentUI.php" id="headr">
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
						<li><a href="#" class="actual" pid="Exams" onclick="changeMenu(this)">Exams</a></li>
                                                <li><a href="#" class="actual" pid="Enrolled" onclick="changeMenu(this)">Enrolled</a></li>
					<ul class="menu1">
                                                <li><a href="studentLogout.php" class="actual" pid="Logout">Logout</a></li>
                                                <li><a href="#" class="actual" pid="EditProfile" onclick="changeMenu(this)">Profile</a></li>
						<div class="clear"/>
					</ul>
					</ul>
				</nav>
			</div>
		</header>
		
		<div id="error">
                    <h4 id="errmsg"></h4>
                </div>
		
		
		<div class="pages" id="Exams">
                    <center>
                    <div class="login" id="Search" style="float:none">
				<h1>Exams Schedule</h1>
				<form name="examenroll" action="#" method="post" autocomplete="off">
                                    <input name="eid" type="text" placeholder="Exam Title *" required/>
                                    <input name="search" type="Submit" value="Search" />
				</form>
                    </div>
                    <div>
		    <?php
                        
                            if(($_SERVER['REQUEST_METHOD'] == 'POST') and isset($_POST['search'])){
                                
                                $sid = $mysqli->escape_string($_POST['eid']);
                                $res = $mysqli->query("SELECT * FROM `exams` WHERE Title = '$sid'");
                                if($res->num_rows > 0){
                                    echo '<form id="Staffremov" action="studentUI.php" method="post" autocomplete="off">'
                                        . '<table class="responstable">'
                                        . '<tr>'
                                            . '<th> </th>'
                                            . '<th>ExamTitle</th>'
                                            . '<th>Date</th>'
                                            . '<th>Start Time</th>'
                                            . '<th>End Time</th>'
					. '<th>Description</th>'
                                        . '</tr>';
                                    while($row = $res->fetch_assoc()){
                                        echo '<tr>'
                                            . '<td><input name="eid[]" type="checkbox" value=' . $row['EID'] . '></td>'
                                            . '<td>' . $row['Title'] . '</td>'    
                                            . '<td>' . $row['DateOfConduct'] . '</td>'
                                            . '<td>' . $row['StartTime'] . '</td>'
                                            . '<td>' . $row['EndTime'] . '</td>'
                                            . '<td>' . $row['Description'] . '</td>'
                                            . '</tr>';
                                    }
                                    echo '</table>'
                                        . '<input name="enroll" type="submit" value="Enroll"/>'
                                        . '</form>';
                                        
                                }
                                else{
                                    $errmsg = 'No such type of Exam';
                                }
                                $err = TRUE;
                                $frmName = "Exams";
                            }
                            else{
                                $res = $mysqli->query("SELECT * FROM `Exams`");
                                if($res->num_rows > 0){
                                    echo '<form id="Staffremov" action="studentUI.php" method="post" autocomplete="off">'
                                        . '<table class="responstable">'
                                        . '<tr>'
                                            . '<th> </th>'
                                            . '<th>Exam Title</th>'
                                            . '<th>Date</th>'
                                            . '<th>Start Time</th>'
                                            . '<th>End Time</th>'
											 . '<th>Description</th>'
                                        . '</tr>';
                                    while($row = $res->fetch_assoc()){
                                        echo '<tr>'
                                            . '<td><input name="eid[]" type="checkbox" value=' . $row['EID'] . '></td>'
                                            . '<td>' . $row['Title'] . '</td>'    
                                            . '<td>' . $row['DateOfConduct'] . '</td>'
                                            . '<td>' . $row['StartTime'] . '</td>'
                                            . '<td>' . $row['EndTime'] . '</td>'
											. '<td>' . $row['Description'] . '</td>'
                                            . '</tr>';
                                    }
                                    echo '</table>'
                                        . '<input name="enroll" type="submit" value="Enroll"/>'
                                        . '</form>';
                                        
                                }
                                else{
                                    echo 'No Such Exam';
                                }
                            }
                        
                        ?>
                    </div>
                    </center>
		</div>
            
            <div class="pages" id="Enrolled">
                    <center>
                    
                    <div>
		    <?php
                        
                                $userID = $_SESSION['UserID'];
                                $qry = "SELECT `exams`.* FROM `exams`,`enrolled` WHERE `enrolled`.`examid` = `exams`.`EID` AND (enrolled.std1 = '$userID' or enrolled.std2 = '$userID')";
                                $res = $mysqli->query($qry);
                                if($res->num_rows > 0){
                                    echo '<form id="Staffremov" action="takeTest.php" method="post" autocomplete="off">'
                                        . '<table class="responstable">'
                                        . '<tr>'
                                            . '<th> </th>'
                                            . '<th>Exam Title</th>'
                                            . '<th>Date</th>'
                                            . '<th>Start Time</th>'
                                            . '<th>End Time</th>'
                                            . '<th>Description</th>'
                                        . '</tr>';
                                    $curd = date("Y-m-d");
                                    $curt = date("H:i:s");
                                    while($row = $res->fetch_assoc()){
                                        echo '<tr>';
                                        if(($curd == $row['DateOfConduct']) and (($curt <= $row['EndTime']) and ($curt >= $row['StartTime']))){
                                            echo'<td padding="10%"><input padding="20%" value="TakeTest" type="submit" name="'.$row['EID'].'"></td>';
                                        }
                                        else{
                                            echo '<td>Test is on specified date and time</td>';
                                        }
                                        echo '<td>' . $row['Title'] . '</td>'    
                                            . '<td>' . $row['DateOfConduct'] . '</td>'
                                            . '<td>' . $row['StartTime'] . '</td>'
                                            . '<td>' . $row['EndTime'] . '</td>'
                                            . '<td>' . $row['Description'] . '</td>'
                                        . '</tr>';
                                    }
                                    echo '</table>'
                                        . '</form>';
                                        
                                }
                                else{
                                    echo 'No Enrolled Exam';
                                }
                        ?>
                    </div>
                    </center>
		</div>
            
            
		<div class="pages" id="Profile">
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
            
                <div class="pages" id="Team">
			<div class="login" position="center">
				<h1>Select Team</h1>
				<form name="tem" action="#" method="post" autocomplete="off">
                                    <input name="userID" type="text" placeholder="Student ID *"></p>
                                    <input name="pass" type="password" placeholder="Password *" onblur='validPassword("tem")' required/>
                                    <input name="selectTeam" type="Submit" value="Form Team" />
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