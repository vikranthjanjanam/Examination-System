<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    require("db.php");
    session_start();
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            body
            {
                background-color: #FFDCAC;
            }
        </style>
    </head>
    <body style="text-align: center">
        <?php
            $scr = $_SESSION['score'];
            $eid = $_SESSION['xmid'];
            $userID = $_SESSION['UserID'];
            $qry = "UPDATE `enrolled` SET `result` = '$scr' WHERE `examid` = '$eid' AND ( `std1` = '$userID' OR `std2` = '$userID' );";
            $res = $mysqli->query($qry);
            if($res == TRUE){
                echo "<h4>Your Score Is $scr.<h4><br>";
            }
        ?>
        <a href="#" onclick="window.close()">Home</a>
    </body>
</html>
