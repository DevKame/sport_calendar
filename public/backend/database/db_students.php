<?php

////////////////////////////////////    STUDENT QUERIES  [start]  //////////////////////////////////////
/** CHANGES A STUDENT
 *  returns
 * {$affected | Exeption->getMessage()} => int | String */
function editStudent($id, $email, $fn, $ln, $groups) {
    $con = connect();
    $query =
    "UPDATE sport_cal_user
    SET
    email = ?,
    firstname = ?,
    lastname = ?,
    groups = ?
    WHERE id = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ssssi", $email, $fn, $ln, $groups, $id);
        mysqli_stmt_execute($stmt);
        $affected = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return $affected;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }
}
/** CHECKS IF THERE IS A DOUBLICAT EMAIL DESPITE THE ONE CONNECTED TO THE PROVIDED ID
 *  returns
 * {$totalEmails | Exeption->getMessage()} => int | String */
function getEmailsExeptOwn($email, $id) {
    $con = connect();
    $students = [];
    $query =
    "SELECT email
    FROM sport_cal_user
    WHERE email = ?
    AND NOT id = ?";
    $stmt = mysqli_prepare($con, $query);
    try {
        mysqli_stmt_bind_param($stmt, "si", $email, $id);
        mysqli_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $totalEmails = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        
        mysqli_close($con);
        return $totalEmails;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }

}
/** DELETES A STUDENT BASED ON ITS ID
 *  returns
 * {true | Exeption->getMessage()} => Bool | String */
function deleteStudent($id) {
    $con = connect();
    $query =
    "DELETE FROM sport_cal_user
    WHERE id = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $affected = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        if($affected === 0)
        {
            return false;
        }
        else {
            return true;
        }
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }
}
/** CREATES A NEW STUDENT
 *  returns
 * {true | Exeption->getMessage()} => Bool | String */
function createStudent($email, $firstname, $lastname, $groups) {
    $con = connect();
    $query =
    "INSERT INTO sport_cal_user
    (email, firstname, lastname, role, groups, password)
    VALUES (?,?,?, 'STUDENT', ?, '12345')";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $email, $firstname, $lastname, $groups);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return true;
    }
    catch(Exeption $e) {
        return $e.getMessage();
    }
}
/** FETCHES ALL EXISTENT EMAILS AND RETURNS THEIR TOTAL
 *  returns
 * {$totalEmails | Exeption->getMessage()} => int | String */
function getAllEmails($email) {
    $con = connect();
    $students = [];
    $query =
    "SELECT email
    FROM sport_cal_user
    WHERE email = ?";
    $stmt = mysqli_prepare($con, $query);
    try {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $totalEmails = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        
        mysqli_close($con);
        return $totalEmails;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }

}
/** FETCHES ONLY NAME AND ID OF ALL STUDENTS
 *  returns
 * {$groups | Exeption->getMessage()} => Array | String */
function getStudentNameAndID() {
    $con = connect();
    $students = [];
    $query =
    "SELECT
    id,
    firstname,
    lastname
    FROM sport_cal_user
    WHERE role = 'STUDENT'";
    $stmt = mysqli_prepare($con, $query);
    try {
        mysqli_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $firstname, $lastname);
        mysqli_stmt_store_result($stmt);
        $totalStudents = mysqli_stmt_num_rows($stmt);
        if($totalStudents === 0) {
            mysqli_stmt_free_result($stmt);
            mysqli_stmt_close($stmt);
        }
        else {
            while(mysqli_stmt_fetch($stmt)) {
                $students[] =
                [
                    "id" => $id,
                    "firstname" => $firstname,
                    "lastname" => $lastname,
                ];
            }
            mysqli_stmt_free_result($stmt);
            mysqli_stmt_close($stmt);
        }
        mysqli_close($con);
        return $students;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }

}
/** FETCHES ALL EXISTENT STUDENTS
 *  returns
 * {$groups | Exeption->getMessage()} => Array | String */
function getAllStudents() {
    $con = connect();
    $students = [];
    $query =
    "SELECT
    id,
    email,
    firstname,
    lastname,
    role,
    groups
    FROM sport_cal_user
    WHERE role = 'STUDENT'";
    $stmt = mysqli_prepare($con, $query);
    try {
        mysqli_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $email, $firstname, $lastname, $role, $groups);
        mysqli_stmt_store_result($stmt);
        $totalStudents = mysqli_stmt_num_rows($stmt);
        if($totalStudents === 0) {
            mysqli_stmt_free_result($stmt);
            mysqli_stmt_close($stmt);
        }
        else {
            while(mysqli_stmt_fetch($stmt)) {
                $students[] =
                [
                    "id" => $id,
                    "email" => $email,
                    "firstname" => $firstname,
                    "lastname" => $lastname,
                    "role" => $role,
                    "groups" => $groups,
                ];
            }
            mysqli_stmt_free_result($stmt);
            mysqli_stmt_close($stmt);
        }
        mysqli_close($con);
        return $students;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }

}
////////////////////////////////////    STUDENT QUERIES  [end]  //////////////////////////////////////
