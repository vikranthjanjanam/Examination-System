<!DOCTYPE html>
<?php
    require("db.php");
    $err = FALSE;
    $errmsg = "";
    $frmName = "";
    session_start();
?>


	<head>
		<meta charset="UTF-8">
		<title>RVR&JC | Staff</title>
		<link rel="stylesheet" href="css/staffStyle.css" media="screen" type="text/css" />
		<script type="text/javascript" src="js/index.js"></script>
	</head>
        
<?php
    $_SESSION['sid'] = "S14IT835";  //trail
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        if(isset($_POST['crt'])){
            
            $title = $mysqli->escape_string($_POST['title']);
            $dt = date($_POST['dte']);
            $st = date('H:i:s', strtotime($_POST['starttime']));
            $et = date('H:i:s', strtotime($_POST['endtime']));
            $elt = $mysqli->escape_string($_POST['elapsetime']);
            $tm = $mysqli->escape_string($_POST['tm']);
            $des = $mysqli->escape_string($_POST['des']);
            $qlm = $mysqli->escape_string($_POST['qlm']);
            $qno = $mysqli->escape_string($_POST['qNo']);
            $pmark = $mysqli->escape_string($_POST['pMark']);
            $nmark = $mysqli->escape_string($_POST['nMark']);
            while (TRUE) {
                $eid = strval(mt_rand(99999,1000000));
                $check = $mysqli->query("SELECT * FROM exams WHERE EID = '$eid'");
                if ($check->num_rows == 0) {
                    break;
                }
            }
            $sid = $mysqli->escape_string($_SESSION['sid']);
            $insert = "INSERT INTO exams"
                    . " VALUES ('$title', '$sid', '$eid', '$dt', '$st', '$et', '$elt', '$qlm', '$qno', '$pmark', '$nmark', '$tm', '$des');";
            $resin = $mysqli->query($insert);
            if($resin == TRUE){
                $errmsg = "Exam is Scheduled.<br>Add Quesions.";
                $err = TRUE;
                $frmName = "Qsns";
               /* $_SESSION['eid'] = $eid;
                $_SESSION['Qsns'] = '<Questions>\n';
                $_SESSION['qno'] = 1;*/
                echo $errmsg;
            }
            else{
                $errmsg = "Exam Not Scheduled";
                $err = TRUE;
                $frmName = "ScheduleExam";
            }
            
        }
        /*
        else if(isset ($_POST['sbmt'])){
            $_SESSION['Qsns'] .= '<Qsn>\n';
            $_SESSION['Qsns'] .= '<no>'.$_SESSION['qno']++.'</no>\n';
            $_SESSION['Qsns'] .= '<Qsnv>'.htmlentities($_POST['qsn']).'</Qsnv>\n';
            $_SESSION['Qsns'] .= '<Opt1>'.htmlentities($_POST['opt1']).'</Opt1>\n';
            $_SESSION['Qsns'] .= '<Opt2>'.htmlentities($_POST['opt2']).'</Opt2>\n';
            $_SESSION['Qsns'] .= '<Opt3>'.htmlentities($_POST['opt3']).'</Opt3>\n';
            $_SESSION['Qsns'] .= '<Opt4>'.htmlentities($_POST['opt4']).'</Opt4>\n';
            $_SESSION['Qsns'] .= '<Crct>'.htmlentities($_POST['crct']).'</Crct>\n';
            $_SESSION['Qsns'] .= '</Qsn>\n';
            $_SESSION['Qsns'] .= '</Questions>';
            
            $eid = $_SESSION['eid'];
            $qsns = $_SESSION['Qsns'];
            $res = $mysqli->query("SELECT * FROM questions WHERE EID = '$eid'");
            if($res->num_rows == 0){
                $qins = $mysqli->query("INSERT INTO `questions` (`EID`, `Qsns`) VALUES ('$eid', '$qsns')");
            }
            else{
                $qins = $mysqli->query("UPDATE `questions` SET `Qsns` = '$qsns' WHERE `questions`.`EID` = '$eid'");
            }
            if($qins == TRUE){
                $_SESSION['Qsns']="";
                $_SESSION['eid']=0;
                $errmsg="Succesfuly Updated Qsns";
                $err=TRUE;
                $frmName="UpdateExam";
            }
        }*/
        
        else if(isset($_POST['save'])){
//            echo 'Uploading Qsns';
            require_once "PHPExcel/Classes/PHPExcel.php";
            $filename = $_FILES["fileToUpload"]["tmp_name"];
            echo $filename;
            print_r(stat($filename));
            $excelRd = PHPExcel_IOFactory::createReaderForFile($filename);
            $excelOb = $excelRd->load($filename);
            $worksheet = $excelOb->getSheet(0);
            $lastRow = $worksheet->getHighestRow();
            $qsns = "<Questions>\n";                                                
            for ($row = 2; $row < $lastRow; $row++)
            {
                $no = $row-1;
                $qsns .= "<Qsn>\n";
                $qsns .= "<no>". $no ."</no>\n";
                $qsns .= "<Qsnv>".htmlentities($worksheet->getCell('A'.$row)->getValue())."</Qsnv>\n";
                $qsns .= '<Opt1>'.htmlentities($worksheet->getCell('C'.$row)->getValue()).'</Opt1>\n';
                $qsns .= '<Opt2>'.htmlentities($worksheet->getCell('D'.$row)->getValue()).'</Opt2>\n';
                $qsns .= '<Opt3>'.htmlentities($worksheet->getCell('E'.$row)->getValue()).'</Opt3>\n';
                $qsns .= '<Opt4>'.htmlentities($worksheet->getCell('F'.$row)->getValue()).'</Opt4>\n';
                $qsns .= '<Crct>'.htmlentities($worksheet->getCell('B'.$row)->getValue()).'</Crct>\n';
                $qsns .= '</Qsn>\n';
            }
            $qsns .= '</Questions>';
            $eid = $_SESSION['eid'];
            $res = $mysqli->query("SELECT * FROM questions WHERE EID = '$eid'");
            if($res->num_rows == 0){
                $qins = $mysqli->query("INSERT INTO `questions` (`EID`, `Qsns`) VALUES ('$eid', '$qsns')");
            }
            else{
                $qins = $mysqli->query("UPDATE `questions` SET `Qsns` = '$qsns' WHERE `questions`.`EID` = '$eid'");
            }
            $errmsg="Succesfuly Updated Qsns";
                $err=TRUE;
                $frmName="UpdateExam";
        }
        
        else if(isset($_POST['update'])){
            $_SESSION['eid'] = $_POST['eid'];
            $err = TRUE;
            $frmName = "Qsns";
            $_SESSION['qno'] = 1;
        }
    }
?>
        
	<body>
		<header>
			<div class="top-header">
				<div class="container">
                                        <a href="staffUI.php" id="headr">
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
						<li><a href="#" class="actual" pid="ScheduleExam" onclick="changeMenu(this)">Schedule Exam</a></li>
						<li><a href="#" class="actual" pid="UpdateExam" onclick="changeMenu(this)">Update Exam</a></li>
                                                <li><a href="#" class="actual" pid="Results" onclick="changeMenu(this)">Results</a></li>
					   <ul class="menu1">
						<li><a href="staffLogout.php" class="actual" pid="Logout" onclick="changemenu(this)">Logout</a></li>
						<li><a href="#" class="actual" pid="Profile" onclick="changeMenu(this)">Profile</a></li>
                                                <div class="clear"/>
					   </ul>
					</ul>
				</nav>
			</div>
		</header>
            
                <div id="error">
                    <h4 id="errmsg"></h4>
                </div>
		
                <div class="pages" id="Results">
			<?php
                            $usid = $_SESSION['sid'];
                            $res = $mysqli->query("SELECT * FROM exams WHERE StaffID = '$usid'");
                            if($res->num_rows > 0){
                                    echo '<form id="StaffResult" name="StaffResult" action="#" method="post" autocomplete="off">'
                                        . '<table class="responstable">'
                                        . '<tr>'
                                            . '<th></th>'
                                            . '<th>Title</th>'
                                            . '<th>Date</th>'
                                            . '<th>Start Time</th>'
                                            . '<th>End Time</th>'
                                            . '<th>Description</th>'
                                        . '</tr>';
                                    $curd = date("Y-m-d");
                                    $curt = date("H:i:s");
                                    while($row = $res->fetch_assoc()){
                                        echo '<tr>';
                                            if($curd >= $row['DateOfConduct']){
                                            echo'<td padding="10%"><input value="Get Result" type="submit" name="'.$row['EID'].'" onclick="chVal(this)"></td>';
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
                                    $frmName = "Results";
                                    $errmsg = 'No Exam is Assigned';
                                    $err = TRUE;
                                }
                        
                        ?>
                </div>
            
		<div class="pages" id="ScheduleExam">
			<div id="staff1">
				<h1>SCHEDULE EXAM</h1>
                                <form name="ScheduleExam" method="post" autocomplete="off">
					<input name="title" type="text" placeholder="Exam Title *" required/>
                                        <p>Date Of Exam *<input name="dte" type="date" placeholder="Date Of Exam *" required/></p>
					<p>Start Time *<input name="starttime" type="time" placeholder="Starting Time * (hh:mm:ss)" required/></p>
					<p>End Time *<input name="endtime" type="time" placeholder="Closing Time * (hh:mm:ss)" required/></p>
					<input name="elapsetime" type="text" placeholder="Time Period in Min (20)" required/>
                                        <input name="qNo" type="text" placeholder="No of Questions for Student *" required/>
                                        <input name="pMark" type="text" placeholder="Positive Mark *" required/>
                                        <input name="nMark" type="text" placeholder="Negative Mark *" required/>
                                        <select name="tm" onblur='validGrp("ScheduleExam")'>
                                            <option selected disabled hidden>Members per Team *</option>
                                            <option value="2" name="tm">2</option>
                                            <option value="1" name="tm">1</option>
                                        </select>
                                        <input name="qlm" type="text" placeholder="Qualifying Marks *" required/>
                                        <textarea name="des"  placeholder="Description Of Exam" required></textarea>
					<input name="crt" type="submit" value="create"/>
				</form>
			</div>
		</div>
		
		<div class="pages" id="UpdateExam">
                    <div id="scrolls">
			<?php
                            $sid = $_SESSION['sid'];
                            if($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['search'])){
                                $frmName = "UpdateExam";
                                $title = $mysqli->escape_string($_POST['title']);
                                $res = $mysqli->query("SELECT * FROM exams WHERE Title = '$title' AND StaffID = '$sid'");
                                if($res->num_rows == 0){
                                    $errmsg = "Invalid Exam";
                                    $err = TRUE;
                                    $res = $mysqli->query("SELECT * FROM exams WHERE StaffID = '$sid'");
                                }
                            }
                            else{
                                $res = $mysqli->query("SELECT * FROM exams WHERE StaffID = '$sid'");
                            }
                            
                                if($res->num_rows > 0){
                                    echo '<form id="Staffremov" action="#" method="post" autocomplete="off">'
                                        . '<table class="responstable">'
                                        . '<tr>'
                                            . '<th> </th>'
                                            . '<th>Title</th>'
                                            . '<th>Date</th>'
                                            . '<th>Description</th>'
                                        . '</tr>';
                                    while($row = $res->fetch_assoc()){
                                        echo '<tr>'
                                            . '<td><input name="eid" type="radio" value=' . $row['EID'] . '></td>'
                                            . '<td>' . $row['Title'] . '</td>'    
                                            . '<td>' . $row['DateOfConduct'] . '</td>'
                                            . '<td>' . $row['Description'] . '</td>'
                                            . '</tr>';
                                    }
                                    echo '</table>'
                                        . '<input name="update" type="submit" value="Update"/>'
                                        . '</form>';
                                }
                                else{
                                    $errmsg = 'No Exam is Assigned';
                                    $err = TRUE;
                                    $frmName = "UpdateExam";
                                }
                        
                        ?>
                    </div>
                    <div class="login" id="sSearch">
				<h1>Update Exam</h1>
				<form id="rmvStaff" action="#" method="post" autocomplete="off">
                                    <input name="title" type="text" placeholder="Exam Title *" required/>
                                    <input name="search" type="Submit" value="Search" />
				</form>
                    </div>
		</div>
		
                <div class="pages" id="Qsns">
                    <div id="staff1">
				<h1>Upload Questions</h1>
                                <form name="Qsns" action="#" method="post" autocomplete="off" enctype="multipart/form-data">
					<p>Upload<input type="file" name="fileToUpload" id="fileToUpload"></p>
                                        <input name="save" type="Submit" value="Insert"/>
                                        <pre id="note">Need Template?			  <a href="sample.xls" download>Sample</a></pre>
                                <!--    <textarea name="qsn"  placeholder="Question" required></textarea>
                                    <textarea name="opt1"  placeholder="Option 1" required></textarea>
                                    <textarea name="opt2"  placeholder="Option 2" required></textarea>
                                    <textarea name="opt3"  placeholder="Option 3" required></textarea>
                                    <textarea name="opt4"  placeholder="Option 4" required></textarea>
                                    <input name="crct" type="text" placeholder="Correct Option No" required/>
                                    <input name="save" type="submit" value="Next"/>
                                    <input name="sbmt" type="submit" value="Submit"/>   -->
                                </form>
                    </div>
                </div>
                
		<div class="pages" id="Profile">
			<div id="staff1">
				<h1>profile</h1>
				<form name="profile" autocomplete="off">
					
					<p>NAME<input name="Name" type="text"/></p>
					<p>DEPARTMENT<input name="Department" type="text"/></p>
					<p>DESIGNATION<input name="Designation" type="text"/></p>
					<p>CONTACT NUMBER<input name="ContactNo" type="text"/></p>
					<p>EMAIL<input name="Email" type="email"/><p>
					<input type="Submit" value="EDIT"/>
					
				</form>
			</div>
		</div>
            
            <?php
                
                if(isset($_POST['GetResult']) or isset($_POST['sort'])){
                    if(isset($_POST['GetResult'])){
                        $_SESSION['reid'] = $_POST['GetResult'];
                        $srt = 30;
                    }
                    else{
                        $srt = (int)$_POST['nStud'];
                    }
                    $reid = $_SESSION['reid'];
                    $qry = "SELECT * FROM `enrolled` e WHERE e.examid = '$reid' ORDER BY e.`result` DESC LIMIT 0,$srt";
                    $res = $mysqli->query($qry);
                    if($res == TRUE){
                        echo "<div class='pages' id='sorted'>"
                        . "<div id='marksTable' class='scrolls'>";
                        echo '<table id="mTable" class="responstable">'
                            . '<tr>'
                                . '<th>Student1 Name</th>'
                                , '<th>Student2 Name</th>'
                                . '<th>Score</th>'
                            . '</tr>';
                    
                        while($row = $res->fetch_assoc()){
                            echo '<tr>'
                                . '<td>'.$row["std1"].'</td>'
                                . '<td>'.$row["std1"].'</td>'
                                . '<td>'.$row["result"].'</td>'
                                . '</tr>';
                        }
                        echo '</table>'
                        . "</div>";
                        echo "<div id='login'>"
                                ."<h1>Filter Result</h1>"
                                .'<form name="verify" action="#" method="post" autocomplete="off"/>'
                                .'<input name="nStud" type="number" placeholder="No of Students to Qualify" required/>'
                                . '<input name="sort" type="submit" value="Sort">'
                                . '</form>'
                            . "</div>";
                        $errmsg = "Result";
                        $frmName = "sorted";
                    }
                    else{
                        $errmsg = "No Results Found";
                        $frmName = "Results";
                    }
                    $err = TRUE;
                }
                
                echo '<script type="text/javascript">';
                
                if($err == TRUE){
                    echo 'var arg1 = "' , $errmsg . '";'
                        . 'var arg2 = "' . htmlentities($frmName) . '";'
                        . "unhideError(arg1);"
                        . "hideDiv(arg2);";
                    $err = FALSE;
                }
                echo '</script>';
            ?>	
            
	</body>
</html>