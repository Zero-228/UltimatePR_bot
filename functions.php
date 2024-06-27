<?php 

/**
 * UltimatePR Chatbot
 * 
 * Licensed under the Simple Commercial License.
 * 
 * Copyright (c) 2024 Nikita Shkilov nikshkilov@yahoo.com
 * 
 * All rights reserved.
 * 
 * This file is part of PenaltyPuff bot. The use of this file is governed by the
 * terms of the Simple Commercial License, which can be found in the LICENSE file
 * in the root directory of this project.
 */
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;

function debug($things, $decode=false, $clear=false) {

    $directory_path = $_SERVER['DOCUMENT_ROOT'] . '/temp';
    $file_path = $directory_path . '/debug.txt';
    if (!file_exists($directory_path)) {
        mkdir($directory_path, 0777, true);
    }
    $file = fopen($file_path, 'a+');

    if ($clear) {
        file_put_contents($file_path, '');
    }

    if ($decode) {
        $data = json_decode($things, true);
        $message = '[' . TIME_NOW . '] ' . print_r($data, true);
    } else {
        $message = '[' . TIME_NOW . '] ' . $things;
    }

    fwrite($file, $message . PHP_EOL);
    fclose($file);
}

function checkUser($userId){
    $dbCon = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $row = mysqli_query($dbCon, "SELECT userId FROM user WHERE userId='$userId'");
    $numRow = mysqli_num_rows($row);
    if ($numRow == 0) { return 'no_such_user'; } 
    elseif ($numRow == 1) { return 'one_user'; } 
    else { return false; error_log("ERROR! TWIN USER IN DB!");}
    mysqli_close($dbCon);
}

function createUser($user, $startedBot = false){
    $dbCon = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $username = "";
    $timeNow = TIME_NOW;
    if ($user['username']!='') { $username = $user['username']; } 
    else { $username = $user['first_name']." ".$user['last_name']; }
    mysqli_query($dbCon, "INSERT INTO user (userId, firstName, lastName, username, startedBot, language, lastVisit, registeredAt) VALUES ('" . $user['id'] . "', '" . $user['first_name'] . "', '" . $user['last_name'] . "', '" . $username . "', '" . $startedBot . "', '" . $user['language_code'] . "', '" . $timeNow . "', '" . $timeNow . "')");
    mysqli_close($dbCon);
}

function checkUserStatus($userId) {
    $dbCon = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $query = mysqli_query($dbCon, "SELECT deleted, banned FROM user WHERE userId='$userId'");
    $checkStatus = mysqli_fetch_assoc($query);
    mysqli_close($dbCon);
    if ($checkStatus) {
        if ($checkStatus['banned'] == 'yes') {
            return 'banned';
        } elseif ($checkStatus['deleted'] == 'yes') {
            return 'deleted';
        } else {
            return 'active';
        }
    } else {
        error_log('Error checking user\'s status: unknown user '.$userId);
    }
}

function getUsername($userId){
    $dbCon = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $result = mysqli_query($dbCon, "SELECT username FROM user WHERE userId='$userId'");
    if ($result && mysqli_num_rows($result) > 0) {
        $username = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        mysqli_close($dbCon);
        return $username['username'];
    } else {
        mysqli_close($dbCon);
        return msg("friend", $bot->userId());
    }
}

function checkRole($userId) {
    $dbCon = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $roleQuery = mysqli_query($dbCon, "SELECT role FROM user WHERE userId='$userId'");
    $roleNumRow = mysqli_num_rows($roleQuery);
    if ($roleNumRow == 1) {
        $role = mysqli_fetch_assoc($roleQuery);
        $role = $role['role'];
        return $role;
    } else {
        return "no user";
    }
    mysqli_close($dbCon);
}

function userBlockedBot($userId) {
    $dbCon = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $query = mysqli_query($dbCon, "UPDATE user SET deleted='yes' WHERE userId='$userId'");
    mysqli_close($dbCon);
}

function userActivatedBot($userId) {
    $dbCon = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $query = mysqli_query($dbCon, "UPDATE user SET deleted='no' WHERE userId='$userId'");
    mysqli_close($dbCon);
}

function createLog($timestamp, $entity, $entityId, $context, $message) {
    $dbCon = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $createLog = mysqli_query($dbCon, "INSERT INTO log (createdAt, entity, entityId, context, message) VALUES ('$timestamp', '$entity','$entityId','$context','$message')");
    if (!$createLog) {
        error_log("error with create log in DB");
    }
    mysqli_close($dbCon);
}

function lang($userId) {
    $dbCon = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $languageResult = mysqli_query($dbCon, "SELECT language FROM user WHERE userId='$userId'");
    $row = mysqli_fetch_assoc($languageResult);
    $language = isset($row['language']) ? $row['language'] : "Unknown";
    mysqli_free_result($languageResult);
    mysqli_close($dbCon);
    
    return $language;
}

function changeLanguage($userId, $newLang) {
    $dbCon = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $query = mysqli_query($dbCon, "UPDATE user SET language='$newLang' WHERE userId='$userId'");
    mysqli_close($dbCon);
}

function constructMenuButtons($lang) {
    $keyboard = ReplyKeyboardMarkup::make(resize_keyboard: true,)
    ->addRow(KeyboardButton::make(msg('menu_config', $lang)))
    ->addRow(KeyboardButton::make(msg('menu_profile', $lang)), KeyboardButton::make(msg('menu_promote', $lang)),)
    ->addRow(KeyboardButton::make(msg('change_language', $lang)), KeyboardButton::make(msg('menu_support', $lang)),);

    return $keyboard;
}

function checkChanel($chanelId){
    $dbCon = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $row = mysqli_query($dbCon, "SELECT chanelId FROM chanel WHERE chanelId='$chanelId'");
    $numRow = mysqli_num_rows($row);
    if ($numRow == 0) { return 'no_such_chanel'; } 
    elseif ($numRow == 1) { return 'one_chanel'; } 
    else { return false; error_log("ERROR! TWIN CHANEL IN DB!");}
    mysqli_close($dbCon);
}

function createChanel($chanel){
    $dbCon = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $timeNow = TIME_NOW;
    $type = $chanel['type']->value;
    mysqli_query($dbCon, "INSERT INTO chanel (chanelId, title, username, type, updated_at, created_at) VALUES ('" . $chanel['id'] . "', '" . $chanel['title'] . "', '" . $chanel['username'] . "', '" . $type . "', '" . $timeNow . "', '" . $timeNow . "')");
    mysqli_close($dbCon);
}

function updateChanelStatus($chanelId, $status){
    $dbCon = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $timeNow = TIME_NOW;
    mysqli_query($dbCon, "UPDATE chanel SET status='$status' WHERE chanelId='$chanelId'");
    mysqli_close($dbCon);
}

function checkUserInChanel($userId, $chanelId){
    $dbCon = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $row = mysqli_query($dbCon, "SELECT * FROM users_in_chanels WHERE chanelId='$chanelId' AND userId='$userId'");
    $numRow = mysqli_num_rows($row);
    if ($numRow == 0) { return 'user_not_added'; } 
    elseif ($numRow == 1) { 
        $user = mysqli_fetch_assoc($row);
        $response = [
            'role' => $user['role'],
            'status' => $user['status'],
            'updated_at' => $user['updated_at'],
            'created_at' => $user['created_at'],
        ];
        return $response;
    } 
    else { return false; error_log("ERROR! TWIN USER IN CHANEL!");}
    mysqli_close($dbCon);
}

function addUserInChanel($user){
    $dbCon = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $timeNow = TIME_NOW;
    mysqli_query($dbCon, "INSERT INTO users_in_chanels (userId, chanelId, role, status, updated_at, created_at) VALUES ('" . $user['userId'] . "', '" . $user['chanelId'] . "', '" . $user['role'] . "', 'active', '" . $timeNow . "', '" . $timeNow . "')");
    mysqli_close($dbCon);
}

function checkUsersChanel($userId) {
    $dbCon = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    $row = mysqli_query($dbCon, "SELECT chanelId, role FROM users_in_chanels WHERE (userId='$userId' AND role='admin') OR (userId='$userId' AND role='creator')");
    $numRow = mysqli_num_rows($row);
    
    if ($numRow == 0) {
        mysqli_close($dbCon);
        return 'chanel_not_found';
    } else {
        $response = [];
        
        while ($info = mysqli_fetch_assoc($row)) {
            $chanelId = $info['chanelId'];
            $role = $info['role'];
            
            $chanelRow = mysqli_query($dbCon, "SELECT title FROM chanel WHERE chanelId='$chanelId'");
            $chanelInfo = mysqli_fetch_assoc($chanelRow);
            $name = $chanelInfo['title'];
            
            $response[] = [
                'chanelId' => $chanelId,
                'role' => $role,
                'name' => $name,
            ];
        }
        
        mysqli_close($dbCon);
        return $response;
    }
}


function getChanelInfo($chanelId) {
    $dbCon = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $query_info = mysqli_query($dbCon, "SELECT * FROM chanel WHERE chanelId='$chanelId'");
    $fetch_info = mysqli_fetch_assoc($query_info);
    return $fetch_info;
    mysqli_close($dbCon);
}

function createSupportMsg($userId, $msg){
    $dbCon = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $timeNow = TIME_NOW;
    mysqli_query($dbCon, "INSERT INTO support (userId, message, status, updated_at, created_at) VALUES ('$userId', '$msg', 'active', '$timeNow','$timeNow')");
    mysqli_close($dbCon);
}

function checkUserInChanelRole($userId, $chanelId) {
    $dbCon = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $roleQuery = mysqli_query($dbCon, "SELECT role FROM users_in_chanels WHERE userId='$userId' AND chanelId='$chanelId'");
    $roleNumRow = mysqli_num_rows($roleQuery);
    if ($roleNumRow == 1) {
        $role = mysqli_fetch_assoc($roleQuery);
        $role = $role['role'];
        return $role;
    } else {
        return "not a user";
    }
    mysqli_close($dbCon);
}

function updateUserRoleInChanel($userId, $chanelId, $role) {
    $dbCon = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    mysqli_query($dbCon, "UPDATE users_in_chanels SET role='$role' WHERE userId='$userId' AND chanelId='$chanelId'");
    mysqli_close($dbCon);
}


function writeLogFile($string, $clear = false){
    $timeNow = TIME_NOW;
    $log_file_name = __DIR__."/temp/message.txt";
    if($clear == false) {
        $now = date("Y-m-d H:i:s");
        file_put_contents($log_file_name, $timeNow." ".print_r($string, true)."\r\n", FILE_APPEND);
    }
    else {
        file_put_contents($log_file_name, '');
        file_put_contents($log_file_name, $timeNow." ".print_r($string, true)."\r\n", FILE_APPEND);
    }
}
?>