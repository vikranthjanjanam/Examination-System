<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    require("db.php");
    session_start();
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                //if(isset($_POST['examForm'])){
                    $xml = simplexml_load_string($_SESSION['qsns']) or die("ERROR : Cannot Read Object");
                    $cnt = count($xml->Qsn);
                    $pScore=$nScore=$n=0;
                    while($n < $_SESSION['qsnNo']){
                        $qid = "q".strval($xml->Qsn[$_SESSION['qnos'][$n]]->no);
                        $sel = "Opt".$xml->Qsn[$_SESSION['qnos'][$n]]->Crct;
                        $cr = $xml->Qsn[$_SESSION['qnos'][$n]]->$sel;
                        if($_POST[$qid] == $cr){
                            $pScore++;
                        }
                        else if($_POST[$qid] != "nUll"){
                            $nScore++;
                        }
                        $n++;
                    }
                    $_SESSION['score'] = ( $pScore * $_SESSION['pmark'] ) - ( $nScore * $_SESSION['nmark'] );
                    $_SESSION['validTime'] = FALSE;
                    header("location: Results.php");
                //}
            }