<!DOCTYPE html>
<?php
    require("db.php");
    session_start();
    if($_SESSION['validTime']==FALSE){
        header("location: studentUI.php");
    }
?>

<html>
    
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="css/examStyle.css" media="screen" type="text/css" />
	<script type="text/javascript" src="js/exam.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script type="text/javascript">
            window.onload = maxWindow;
            function maxWindow() {
                window.moveTo(0, 0);
                //if (document.all) {
                    top.window.resizeTo(screen.width, screen.height);
                    window.fullScreen=true;
                /*}
                else if (document.layers || document.getElementById) {
                    if (top.window.outerHeight < screen.availHeight || top.window.outerWidth < screen.availWidth) {
                        top.window.outerHeight = screen.availHeight;
                        top.window.outerWidth = screen.availWidth;
                    }
                }*/
            }
        </script>
    </head>
    
    <body>
        <div id="bdy">
          <div id="timer">
            <h2><?=$_SESSION['xmTitle']?></h2>
            <p id="response"></p>
        </div>
        
        <script type="text/javascript">
            var xmlhttp1 = new XMLHttpRequest();
            xmlhttp1.open("GET","response.php",false);
            xmlhttp1.send(null);
            var rs1 = new String(xmlhttp1.responseText);
            var rs = rs1;
                //alert(rs1);
            setInterval(function()
            {
                document.getElementById('response').innerHTML = rs;
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("GET","response.php",false);
                xmlhttp.send(null);
                rs = new String(xmlhttp.responseText);
                if(rs1<rs || rs === "00:00:00")
                    document.getElementById('examForm').submit();
            },1000);
        </script>
        
        <?php
            $eid = $_SESSION['xmid'];
            $xml = simplexml_load_string($_SESSION['qsns']) or die("ERROR : Cannot Read Object");
            $n=0;
        ?>
        <div id="line">
         <div id="questions">
            <div id="qForm">
                <form id="examForm" name ="exam" autocomplete="off" method="post" action="validateExam.php">
                <?php
                    while($n < $_SESSION['qsnNo']){
                        $qn = $_SESSION['qnos'][$n];
                        $qno = $xml->Qsn[$qn]->no;
                ?>
                
                <div class="qsns" id="q<?=$n?>">
                    <div class="qval"><?= $n+1 ?>.<?=$xml->Qsn[$qn]->Qsnv?></div><br>
   
                    <input type="radio" name="q<?=$qno?>" value="<?=$xml->Qsn[$qn]->Opt1?>" class="optns" ><?=$xml->Qsn[$qn]->Opt1?><br>
                    <input type="radio" name="q<?=$qno?>" value="<?=$xml->Qsn[$qn]->Opt2?>" class="optns" ><?=$xml->Qsn[$qn]->Opt2?><br>
                    <input type="radio" name="q<?=$qno?>" value="<?=$xml->Qsn[$qn]->Opt3?>" class="optns" ><?=$xml->Qsn[$qn]->Opt3?><br>
                    <input type="radio" name="q<?=$qno?>" value="<?=$xml->Qsn[$qn]->Opt4?>" class="optns" ><?=$xml->Qsn[$qn]->Opt4?><br>
                    <input type="radio" name="q<?=$qno?>" value="nUll" checked hidden >
                    <div id="nav">
                        <a href="#" class="prev" onclick="previous('<?=htmlentities($n);?>')">Previous</a>
                        <a href="#" class="nxt" onclick="next('<?=htmlentities($n);?>','<?=htmlentities($_SESSION['qsnNo']-1);?>')">Save & Next</a>
                    </div>
                </div>
                
                <?php
                        $n++; 
                    }                        
                ?>
                <br>
                <center id="smts">
                    <a href="#"  id="sbmt" onclick="confrm()">Submit</a>
                </center>
             </form>
           </div>
          </div>
         </div>
        </div>
    </body>
</html>