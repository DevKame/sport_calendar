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

//################################################# HANDLES GET REQUESTS:
// RETURNS Boolean INDICATING IF A USER IS LOGGED IN
if($_SERVER["REQUEST_METHOD"] === "GET")
{
    $events = getAllEvents();
    if(is_string($events)) {
        $res["reason"] = "connection-problems";
    }
    else {
        $failedUpdates = 0;
        foreach($events as $event)
        {
            if(isEventOld($event))
            {
                $turnToOldResult = setEventAsOld($event["id"]);
                if(is_string($turnToOldResult))
                {
                    $failedUpdates++;
                    break;
                } else {
                    $event["old"] = 1;
                }
            }
        }
        if($failedUpdates === 0)
        {
            $res["success"] = true;
            $res["events"] = $events;
        } else {
            $res["reason"] = "connection-problems";
        }
    }
}
//################################################# HANDLES POST REQUESTS
//################################################# DEPENDANT ON ITS TASK VALUE:
else if($_SERVER["REQUEST_METHOD"] === "POST")
{
    $req = json_decode(file_get_contents("php://input"));
    switch($req->task)
    {
        //################### CHANGES DATA OF A STUDENT:
        case "edit-student":
            $affectedRows = editStudent($req->id, $req->email,$req->firstname, $req->lastname, $req->chosengroups);
            if(is_int($affectedRows))
            {
                if($affectedRows === 1)
                {
                    $res["success"] = true;
                } else {
                    if($affectedRows === 0)
                    {
                        $res["reason"] = "no-changes";
                    } else {
                        $res["reason"] = "connection-problems";
                    }
                }
            }
            else {
                $res["reason"] = "connection-problems";
            }
            break;
        //################### VALIDATES DATA USED FOR CAHNGING A STUDENT:
        case "validate-student-edit":
            // VALIDATION OF EMAIL
            $evrimmedEmail = trim($req->email);
            if($evrimmedEmail === "")
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
            $evrimmedFirstname = trim($req->firstname);
            if($evrimmedFirstname === "")
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
            $evrimmedLastname = trim($req->lastname);
            if($evrimmedLastname === "")
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
        //################### DELETES A STUDENT BASED ON ITS ID:
        case "delete-student":
            $result = deleteStudent($req->id);
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
        //################### CREATES A NEW STUDENT:
        case "create-student":
            if(!is_string(createStudent($req->email,$req->firstname,$req->lastname,$req->chosengroups)))
            {
                $res["success"] = true;
            } else {
                $res["reason"] = "connection-problems";
            }
            break;
        //################### VALIDATES DATA FOR CREATING A NEW STUDENT:
        case "validate-student":
            // VALIDATION OF EMAIL
            $evrimmedEmail = trim($req->email);
            if($evrimmedEmail === "")
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
            $allEmails = getallEmails($req->email);
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
            $evrimmedFirstname = trim($req->firstname);
            if($evrimmedFirstname === "")
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
            $evrimmedLastname = trim($req->lastname);
            if($evrimmedLastname === "")
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
    }
}

function isEventOld($ev) {
    $now            = time();
    $event_stamp   = mktime((int)$ev["hour"], (int)$ev["minute"], 0, (int)$ev["month"], (int)$ev["day"], (int)$ev["year"]);
    $result = $event_stamp < $now ?
    true :
    false;
    return $result;
}




$res = json_encode($res);
echo $res;