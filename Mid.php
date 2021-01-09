<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function abc() {
    global $x;
    $x += 10;
    return $x;
}
$x = 10;
echo $x;
print_r(abc($x));
echo $x;