<?php


$host =     "localhost";
$user =     "Kamedin";
$pw =       "12345";
$db =       "kame_apps";


////////////////////////////////////    STUDENT QUERIES  [start]  //////////////////////////////////////
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
////////////////////////////////////    GROUP QUERIES  [start]  //////////////////////////////////////
/** RENAMES A GROUP
 *  returns
 * {$affected | Exeption->getMessage()} => int | String */
function editGroup($id, $name) {
    $con = connect();
    $query =
    "UPDATE sport_cal_groups
    SET
    name = ?
    WHERE id = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "si", $name, $id);
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
/** VALIDATES AN INTENDED NEW GROUPNAME FOR EDITING THIS GROUP
 *  SKIPS ITS OWN NAME
 *  returns
 * {$matches | Exeption->getMessage()} => Array | String */
function doubleCheckForEdit($id) {
    $con = connect();
    $query =
    "SELECT name FROM sport_cal_groups
    WHERE NOT id = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $fName);
        $matches = [];
        while(mysqli_stmt_fetch($stmt)) {
            $matches[] = $fName;
        }
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return $matches;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }
}
/** DELETES A GROUP BASED ON ITS ID
 *  returns
 * {true | Exeption->getMessage()} => Bool | String */
function deleteGroup($id) {
    $con = connect();
    $query =
    "DELETE FROM sport_cal_groups
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
/** CREATES A NEW GROUP
 *  returns
 * {true | Exeption->getMessage()} => Bool | String */
function createGroup($name) {
    $con = connect();
    $query =
    "INSERT INTO sport_cal_groups
    (name)
    VALUES (?)";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $name);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return true;
    }
    catch(Exeption $e) {
        return $e.getMessage();
    }
}
/** FETCHES ALL EXISTENT GROUPS
 *  returns
 * {$groups | Exeption->getMessage()} => Array | String */
function getAllGroups() {
    $con = connect();
    $groups = [];
    $query =
    "SELECT * FROM sport_cal_groups";
    $stmt = mysqli_prepare($con, $query);
    try {
        mysqli_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $name);
        mysqli_stmt_store_result($stmt);
        $totalGroups = mysqli_stmt_num_rows($stmt);
        if($totalGroups === 0) {
            mysqli_stmt_free_result($stmt);
            mysqli_stmt_close($stmt);
        }
        else {
            while(mysqli_stmt_fetch($stmt)) {
                $groups[] = ["id" => $id, "name" => $name];
            }
            mysqli_stmt_free_result($stmt);
            mysqli_stmt_close($stmt);
        }
        mysqli_close($con);
        return $groups;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }

}
////////////////////////////////////    GROUP QUERIES  [end]  //////////////////////////////////////
/** USES A USER-ID TO FETCH THIS USERS DATA
 *  args:
 *  {$id}                               => int
 *  returns
 * {$userData | Exeption->getMessage()} => assoz. Array | String */
function getUserDataFromID($id) {
    $con = connect();
    $userData = [];
    $query =
    "SELECT * FROM sport_cal_user
    WHERE id = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $fID, $fEmail, $fFn, $fLn, $fRole, $fGroups, $fPw);
        mysqli_stmt_store_result($stmt);
        while(mysqli_stmt_fetch($stmt)) {
            $userData["id"] = $fID;
            $userData["email"] = $fEmail;
            $userData["firstname"] = $fFn;
            $userData["lastname"] = $fLn;
            $userData["role"] = $fRole;
            $userData["groups"] = $fGroups;
        }
    
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return $userData;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        return $e->getMessage();
    }

}
/** FETCHES THE CORRESPONDING ID TO AN EMIAL
 *  args:
 *  {$email}            => String
 *  returns
 *  {$result | $error}  => int | String (message from catchBlock) */
function getUserIDFromEmail($email) {
    $con = connect();
    $id;
    $query =
    "SELECT id
    FROM sport_cal_user
    WHERE email = ?";
    try {
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $fetchedID);
        while(mysqli_stmt_fetch($stmt)) {
            $id = $fetchedID;
        }
        $result = $fetchedID;
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);

        return (int)$result;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        $error = $e->getMessage();
        return $e-getMessage();
    }
}
/** CHECKS IF A PASSWORD FITS TO AN EXISTING EMAIL
 *  args:
 *  {$pw}               => String
 *  {$email}            => String
 *  returns
 *  {$result | $error}  => Boolean | String (message from catchBlock) */
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
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);

        return $result;
    }
    catch(Exeption $e) {
        mysqli_close($con);
        $error = $e->getMessage();
        return $e-getMessage();
    }
}
/** CHECKS IF AN EMAIL EXISTS IN THE DATABASE
 *  args:
 *  {$email}            => String
 *  returns
 *  {$result | $error}  => int (0 or 1) | String (message from catchBlock) */
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
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
    }
    catch(Exeption $e) {
        mysqli_close($con);
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