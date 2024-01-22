<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$res = [];
$res["success"] = false;
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");
if($_SERVER["REQUEST_METHOD"] === "GET")
{
    $res["content"] = "GET Request hat wunderbar geklappt";
}
else if($_SERVER["REQUEST_METHOD"] === "POST")
{
    $req = json_decode(file_get_contents("php://input"));
    require("../database/db.php");
    switch($req->task)
    {
        case "try-login":
            $emailMatches = doesEmailExist($req->email);
            if(is_int($emailMatches))
            {
                if($emailMatches === 0) {
                    $res["reason"] = "email-doesnt-exist";
                }
                else {
                    $passwordCorrect = isPasswordCorrect($req->password, $req->email);
                    if(is_bool($passwordCorrect)) {
                        if($passwordCorrect) {
                            $res["success"] = true;
                        } else {
                            $res["reason"] = "wrong-pw";
                        }
                    } else {
                        $res["reason"] = "connection-problems";
                    }
                }
            }
            else {
                $res["reason"] = "connection-problems";
            }
            break;
    }
}




$res = json_encode($res);
echo $res;