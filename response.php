<?php
    session_start();
    $from = strtotime(date('Y-m-d H:i:s'));
    $to = strtotime($_SESSION['endTime']);
    $diff = $to-$from;
    echo gmdate("H:i:s",$diff);