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
    $groups = getAllGroups();
    if(is_string($groups)) {
        $res["reason"] = "connection-problems";
    }
    else {
        $res["success"] = true;
        $res["groups"] = $groups;
    }
}
//################################################# HANDLES POST REQUESTS
//################################################# DEPENDANT ON ITS TASK VALUE:
else if($_SERVER["REQUEST_METHOD"] === "POST")
{
    $req = json_decode(file_get_contents("php://input"));
    switch($req->task)
    {
        //################### REMOVES A DELETED GROUP FROM ALL POSSIBLE TRAININGS:
        case "update-event-groups":
            $allEvents = getAllEventGroups();
            if(is_string($allEvents))
            {
                $res["reason"] = "connection-problems";
            } else {

                $failedUpdates = 0;
                foreach($allEvents as $event)
                {
                    $groups = json_decode($event["groups"]);
                    $idx = array_search($req->id, $groups);
                    if(is_int($idx))
                    {
                        unset($groups[$idx]);
                        $groups = array_values($groups);
                        $newJSONGroupString = json_encode($groups);
                        $updateResult = updateEventGroups($event["id"], $newJSONGroupString);
                        if(!is_bool($updateResult))
                        {
                            $failedUpdates++;
                            break;
                        }
                        else {
                            if(!$updateResult)
                            {
                                $failedUpdates++;
                            }
                        }
                        
                    }
                }
                if($failedUpdates === 0)
                {
                    $res["success"] = true;
                } else {
                    $res["reason"] = "connection-problems";
                }
            }
            break;
        //################### REMOVES A DELETED GROUP FROM ALL POSSIBLE TRAININGS:
        case "update-training-groups":
            $allTrainings = getAllTrainingGroups();
            if(is_string($allTrainings))
            {
                $res["reason"] = "connection-problems";
            } else {

                $failedUpdates = 0;
                foreach($allTrainings as $training)
                {
                    $groups = json_decode($training["groups"]);
                    $idx = array_search($req->id, $groups);
                    if(is_int($idx))
                    {
                        unset($groups[$idx]);
                        $groups = array_values($groups);
                        $newJSONGroupString = json_encode($groups);
                        $updateResult = updateTrainingGroups($training["id"], $newJSONGroupString);
                        if(!is_bool($updateResult))
                        {
                            $failedUpdates++;
                            break;
                        }
                        else {
                            if(!$updateResult)
                            {
                                $failedUpdates++;
                            }
                        }
                        
                    }
                }
                if($failedUpdates === 0)
                {
                    $res["success"] = true;
                } else {
                    $res["reason"] = "connection-problems";
                }
            }
            break;
        //################### REMOVES A DELETED GROUP FROM ALL POSSIBLE USERS:
        case "update-user-groups":
            $allUsers = getAllUserGroups();
            if(is_string($allUsers))
            {
                $res["reason"] = "connection-problems";
            } else {

                $failedUpdates = 0;
                foreach($allUsers as $user)
                {
                    $groups = json_decode($user["groups"]);
                    $idx = array_search($req->id, $groups);
                    if(is_int($idx))
                    {
                        unset($groups[$idx]);
                        $groups = array_values($groups);
                        $newJSONGroupString = json_encode($groups);
                        $updateResult = updateUserGroups($user["id"], $newJSONGroupString);
                        if(!is_bool($updateResult))
                        {
                            $failedUpdates++;
                            break;
                        }
                        else {
                            if(!$updateResult)
                            {
                                $failedUpdates++;
                            }
                        }
                        
                    }
                }
                if($failedUpdates === 0)
                {
                    $res["success"] = true;
                } else {
                    $res["reason"] = "connection-problems";
                }
            }
            break;
        //################### RENAMES A GROUP BASED ON ITS ID:
        case "edit-group":
            $affectedRows = editGroup($req->id, $req->name);
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
        //###### VALIDATES AN INTENDED GROUP NAME BUT IGNORES THE GROUP ITSELF:
        case "validate-group-edit":
            // CHECK IF THE VALUE IS VALID:
            $trimmedName = trim($req->name);
            if($trimmedName === "")
            {
                $res["reason"] = "invalid-value";
                break;
            }
            // CHECK IF THE VALUE IS LONGER THAN 25 CHARACTERS:
            if(strlen($req->name) > 25)
            {
                $res["reason"] = "too-long";
                break;
            }
            // CHECK IF THE VALUE ALREADY EXISTS:
            $doubles = 0;
            $matches = doubleCheckForEdit($req->id, $req->name);
            if(is_string($matches))
            {
                $res["reason"] = "connection-problems";
            }
            else {
                foreach($matches as $name)
                {
                    if(strtolower($name) === strtolower($req->name))
                    {
                        $doubles++;
                        break;
                    }
                }
                if($doubles === 0)
                {
                    $res["success"] = true;
                } else {
                    $res["reason"] = "found-double";
                }
            }
            break;
        //################### DELETES A GROUP BASED ON ITS ID:
        case "delete-group":
            $result = deleteGroup($req->id);
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
        //################### CREATES A NEW GROUP:
        case "create-group":
            if(!is_string(createGroup($req->name)))
            {
                $res["success"] = true;
            } else {
                $res["reason"] = "connection-problems";
            }
            break;
        //################### VALIDATES AN INTENDED GROUP NAME:
        case "validate-group":
            // FIRST CHECK IF THE VALUE IS VALID:
            $trimmedName = trim($req->name);
            if($trimmedName === "")
            {
                $res["reason"] = "invalid-value";
                break;
            }
            // FIRST CHECK IF THE VALUE IS LONGER THAN 25 CHARACTERS:
            if(strlen($req->name) > 25)
            {
                $res["reason"] = "too-long";
                break;
            }
            // FIRST CHECK IF THE VALUE ALREADY EXISTS:
            $allGroups = getAllGroups();
            $doubles = 0;
            foreach($allGroups as $group) {
                if(strtolower($group["name"]) === strtolower($req->name))
                {
                    $doubles++;
                    break;
                }
            }
            if($doubles > 0) {
                $res["reason"] = "found-double";
            } else {
                $res["success"] = true;
            }
            break;
    }
}




$res = json_encode($res);
echo $res;