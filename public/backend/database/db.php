<?php


$host =     "localhost";
$user =     "Kamedin";
$pw =       "12345";
$db =       "kame_apps";


function isPasswordCorrect($pw, $email) {
    $con = connect();
    $password;
    $query =
    "SELECT password
    FROM sport_cal_user
    WHERE email = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $fetchedPassword);
        while(mysqli_stmt_fetch($stmt)) {
            $password = $fetchedPassword;
        }
        $result = $pw === $fetchedPassword;

        return $result;
    }
    catch(Exeption $e) {
        $error = $e->getMessage();
        return $e-getMessage();
    }
}
function doesEmailExist($email) {
    $con = connect();
    $result = 0;
    $error = "";
    $query =
    "SELECT email
    FROM sport_cal_user
    WHERE email = ?";

    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $result = mysqli_stmt_num_rows($stmt);
    }
    catch(Exeption $e) {
        $error = $e->getMessage();
    }

    if($error === "") {
        return $result;
    } else {
        return $error;
    }

}
function connect() {
    global $host, $user, $pw, $db;
    return mysqli_connect($host, $user, $pw, $db);
}