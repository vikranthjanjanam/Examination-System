<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
            require("db.php");
            session_start();
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                foreach ($_POST as $seleeid){
                    $eid = $_SESSION['xmid'] = key($_POST);
                    $userID = $_SESSION['UserID'];
                    $qry = "SELECT * FROM `enrolled` WHERE `examid` = '$eid' AND (`std1` LIKE '$userID' OR `std2` LIKE '$userID')";
                    $res = $mysqli->query($qry);
                    if($res->num_rows == 1){
                        $exm = $res->fetch_assoc();
                        if($userID == $exm['std1']){
                            $_SESSION['std2ID'] = $exm['std2'];
                        }
                        else{
                            $_SESSION['std2ID'] = $exm['std1'];
                        }
                    }
                    $qry = "SELECT * FROM `exams` WHERE `EID` = '$eid'";
                    $res = $mysqli->query($qry);
                    if($res->num_rows > 0){
                        $exm = $res->fetch_assoc();
                        $_SESSION['xmTitle'] = $exm['Title'];
                        $_SESSION['duration'] = $exm['Period'];
                        $strt = date("Y-m-d H:i:s");
                        $_SESSION['startTime'] = $strt;
                        $end = date("Y-m-d H:i:s", strtotime('+'.$_SESSION['duration'].'minutes', strtotime($_SESSION['startTime'])));
                        $_SESSION['endTime'] = $end;
                        $_SESSION['validTime'] = TRUE;
                        $_SESSION['qsnNo'] = $exm['qsnNo'];
                        $_SESSION['pmark'] = $exm['pMark'];
                        $_SESSION['nmark'] = $exm['nMark'];
                        $_SESSION['qsns'] = "<?xml version='1.0' encoding='UTF-8'?>\n";
                        $qry = $mysqli->query("SELECT * FROM `questions` WHERE `EID` = '$eid'");
                        $res = $qry->fetch_assoc();
                        $_SESSION['qsns'] .= $mysqli->escape_string($res['Qsns']);
                        $xml = simplexml_load_string($_SESSION['qsns']) or die("ERROR : Cannot Read Object");
                        $cnt = count($xml->Qsn);
                        $_SESSION['qnos'] = array();
                        for($i = 0 ; $i < $cnt ; $i++){
                            array_push($_SESSION['qnos'], $i);
                        }
                        shuffle($_SESSION['qnos']);
                        echo "<script type='text/javascript'>"
                        . "window.open('Exam.php','Exam','status=1','resizable=1','scrollbars=0',location='studentUI.php');"
                        . "</script>";
                    }
                }
            }