<?php
require('./lib/dbhandler.php');

$fileId="";
$filePath="";
foreach($_GET as $key => $value){
	$fileId=$key;
	$filePath = getFilePathForId($fileId);
	if (strlen($filePath)==0){
		die();
	}
	incrementDownloadCount($fileId);
}

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Length: ".(string)(filesize($filePath)));
header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
header("Content-Transfer-Encoding: binary\n");

readfile($filePath);

exit();