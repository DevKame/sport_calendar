<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//TODO: change session_name to KDNAPCKIE for production
session_name("KDNAPCKIE-local");
session_start();

$res = [];
$res["success"] = false;
//################################################# CORS HEADERS:
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
//################################################# GENERAL HEADERS:
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

require("../database/db.php");

/** TRAINERS CAN HAVE DIFFERENT ROLES:
 *  TRAINER
 *          creates students
 *          creates Events
 *          creates groups
 *          creates Trainings
 *          creates Trainers
 *          signs for training an event
 *          editing event info
 *  SENIOR-TRAINER
 *          (all of the above, plus:)
 *          edits students
 *          edits Events
 *          edits groups
 *          edits Trainings
 *          edits Trainers
 *  ADMIN
 *          (everything)
 */



 //################################################# HANDLES GET REQUESTS:
// RETURNS Boolean INDICATING IF A USER IS LOGGED IN
if($_SERVER["REQUEST_METHOD"] === "GET")
{
    $trainings = getAllTrainings();
    if(is_string($trainings)) {
        $res["reason"] = "connection-problems";
    }
    else {
        $res["success"] = true;
        $res["trainings"] = $trainings;
    }
}
//################################################# HANDLES POST REQUESTS
//################################################# DEPENDANT ON ITS TASK VALUE:
else if($_SERVER["REQUEST_METHOD"] === "POST")
{
    $req = json_decode(file_get_contents("php://input"));
    switch($req->task)
    {
        //################### CHANGES DATA OF A TRAINER:
        case "edit-trainer":
            $affectedRows = editTrainer($req->id, $req->email,$req->firstname, $req->lastname, $req->role, $req->chosengroups);
            if(is_int($affectedRows))
            {
                if($affectedRows === 1)
                {
                    $res["success"] = true;
                } else {
                    $res["reason"] = "connection-problems";
                }
            }
            else {
                $res["reason"] = "connection-problems";
            }
            break;
        //################### VALIDATES DATA USED FOR CHANGING A TRAINER:
        case "validate-trainer-edit":
            // VALIDATION OF EMAIL
            $trimmedEmail = trim($req->email);
            if($trimmedEmail === "")
            {
                $res["reason"] = "invalid-email-value";
                break;
            }
            if(strlen($req->email) > 40)
            {
                $res["reason"] = "email-too-long";
                break;
            }
            if(!filter_var($req->email, FILTER_VALIDATE_EMAIL))
            {
                $res["reason"] = "invalid-email-value";
                break;
            }
            $allEmails = getEmailsExeptOwn($req->email, $req->id);
            if(is_string($allEmails))
            {
                $res["reason"] = "connection-problems";
                break;
            }
            else {
                if($allEmails > 0) {
                    $res["reason"] = "found-double";
                    break;
                }
            }
            // VALIDATION OF FIRSTNAME
            $trimmedFirstname = trim($req->firstname);
            if($trimmedFirstname === "")
            {
                $res["reason"] = "invalid-firstname-value";
                break;
            }
            if(strlen($req->firstname) > 16)
            {
                $res["reason"] = "firstname-too-long";
                break;
            }
            // VALIDATION OF LASTNAME
            $trimmedLastname = trim($req->lastname);
            if($trimmedLastname === "")
            {
                $res["reason"] = "invalid-lastname-value";
                break;
            }
            if(strlen($req->lastname) > 32)
            {
                $res["reason"] = "lastname-too-long";
                break;
            }
            $res["success"] = true;
            break;
        //################### DELETES A TRAINING USING ITS ID:
        case "delete-training":
            $result = deleteTraining($req->id);
            if(is_bool($result)) {
                if($result) {
                    $res["success"] = true;
                } else {
                    $res["reason"] = "connection-problems";
                }
            } else {
                $res["reason"] = "connection-problems";
            }
            break;
        //################### CREATES A NEW TRAINING:
        case "create-training":
            if(!is_string(createTraining($req->name, $req->chosengroups)))
            {
                $res["success"] = true;
            } else {
                $res["reason"] = "connection-problems";
            }
            break;
        //################### VALIDATES DATA FOR CREATING A NEW TRAINING:
        case "validate-training":
            // VALIDATION OF DOUBLE NAME
            $allTrainingNames = getallTrainingNames($req->name);
            if(is_string($allTrainingNames))
            {
                $res["reason"] = "connection-problems";
                break;
            }
            else {
                if($allTrainingNames > 0) {
                    $res["reason"] = "found-double";
                    break;
                }
            }
            // VALIDATION OF NAME
            $trimmedName = trim($req->name);
            if($trimmedName === "" || strlen($trimmedName) > 24)
            {
                $res["reason"] = "invalid-name";
                break;
            }
            // VALIDATION OF GROUPS
            //TODO: ADD THIS GROUP LENGTH CHECK TO STUDENTS TOO
            if(strlen($req->chosengroups) > 256)
            {
                $res["reason"] = "groups-too-long";
                break;
            }
            $res["success"] = true;
            break;
    }
}




$res = json_encode($res);
echo $res;