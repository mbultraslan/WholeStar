<?php

$link = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("Error " . mysqli_error($link));

function db_query($query) {
    global $link;
    return mysqli_query($link, $query);
}

function db_input($string) {
    global $link;
    return mysqli_real_escape_string($link, $string);
}