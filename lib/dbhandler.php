<?php
// dbhandler.php

require_once('./lib/rb.php');
require_once('./lib/utils.php');
require_once('./lib/settings.php');

R::setup('sqlite:./lib/sql.db');

function saveObject($filename){
	$path_parts = pathinfo($filename, PATHINFO_EXTENSION);
    $file = R::dispense('file');
	$file->filename=$filename;
	$file->uploadtime=date('Ymd_His');
	$file->lastdownloaded=0;
	$file->ip=$_SERVER["REMOTE_ADDR"];
	$file->filetype=$path_parts;
	$file->downloadId=nextRandom($filename);
	$file->downloaded=0;
	$id = R::store($file);
	
	checkDirSize(UPLOAD_FOLDER);
	
	return $file->downloadId;
}

function nextRandom($salt){
	$theString=md5(rand() . time() . $salt);
	$file = R::findOne('file', 'download_id = ?', array($theString));
	if($file){
		return nextRandom();
	} else{
		return $theString;
	}
}

function getCleanAndUniqueFileNameFor($filename){
	$filename=replace_accents($filename);
	if(getFileForFilename($filename)){
		$filename = rand() . $filename;
		return getCleanAndUniqueFileNameFor($filename);
	} else {
		return $filename;
	}
}

function deleteFile($downloadId){
	$file = R::findOne('file', 'download_id = ?', array($downloadId));
	if($file){
		if(file_exists("./".UPLOAD_FOLDER."/".$file->filename)){
			unlink("./".UPLOAD_FOLDER."/".$file->filename);
		}
		R::trash($file);
	}
}

function getFileForFilename($filename){
	$file = R::findOne('file', 'filename = ?', array($filename));
	return $file?$file:false;
}

function incrementDownloadCount($downloadId){
	$file = R::findOne('file', 'download_id = ?', array($downloadId));
	if ($file){
		$file->downloaded = $file->downloaded + 1;
		$file->lastdownloaded = date('Ymd_His');
		R::store($file);
		return true;
	}
	return false;
}

function showOrphaned(){
	$orphaned= array();
	$all = R::findAll('file', ' ORDER BY id ');
    foreach($all as $file){
    	if(isset($file->filename)){
	    	if(!file_exists("./" . UPLOAD_FOLDER . "/" . $file->filename)){
				array_push($orphaned, $file); 
			}
	    }
    }
    return $orphaned;
}

function getFilePathForId($downloadId){
	$file = R::findOne('file', 'download_id = ?', array($downloadId));
	return UPLOAD_FOLDER. '/' . $file->filename;
}

function getAllFiles(){
	return R::findAll('file');
}

function getAllUsers(){
	return R::findAll('user');
}

function getUserData($username){
	$userdata = R::findOne('user', 'username = ?', array(strtolower($username)));
	return $userdata;
}

function changePassword($username, $newpasswordhash){
	$userdata = getUserData($username);
	$userdata->password=$newpasswordhash;
	$id = R::store($userdata);
	return $id?true:false;
}

function insertUser($username, $usernicename, $passwordhash, $email, $role){
	$user = R::dispense('user');
	$user->username=$username;
	$user->usernicename=$usernicename;
	$user->password=$passwordhash;
	$user->email=$email;
	$user->role=$role;
	$id = R::store($user);
	return $id;
}

function removeUser($username){
	$user = R::findOne('user', 'username = ?', array($username));
	R::trash($user);
}

function filesWithNoId(){
	$files = array();
	if ($handle = opendir('./' .UPLOAD_FOLDER . '/')) {
	    while (false !== ($file = readdir($handle))) {
	        if ($file != "." && $file != ".." && $file!="sql.db") {
	            array_push($files, $file);
	        }
	    }
	    
	    $fileswithnoid=array();
	    foreach($files as $filename){
		    if(!getFileForFilename($filename)){
		    	array_push($fileswithnoid, $filename);
		    }
	    }
	    closedir($handle);
    }
    return $fileswithnoid;
}