<?php
require_once('./lib/settings.php');

function nicedirsize($dir) {
	$size = dirsize($dir);
  	if($size>1024 && $size<=1048576){
		$fsize= round($size/1024) . " KB";
	} elseif($size>1048576){
		$fsize=(round($size/104857)/10)." MB";
	} else {
		$fsize = $size . " B";
	}
   return $fsize;
}

function dirsize($dir){
    if (!is_dir($dir)) return false;
    $size = 0;
    $dh = opendir($dir);
    while(($entry = readdir($dh)) !== false) {
       if($entry == "." || $entry == "..") 
          continue;
       if(is_dir( $dir . "/" . $entry))
          $size += dirsize($dir . "/" . $entry);
       else
          $size += filesize($dir . "/" . $entry);
    }
    closedir($dh);
	return $size;
}

function checkDirSize($uploadFolder){
	if (dirsize("./" . $uploadFolder) > FOLDER_LIMIT){
		mail(ADMIN_MAIL, "Warning, files-folder is getting full", "Check your files folder, it might be full", "From: server@my-files.cc\r\n" . "X-Mailer: php");
	}
}

function replace_accents($string){ 
  return str_replace( array('à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý', ' '), array('a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y','y', 'A','A','A','A','A', 'C', 'E','E','E','E', 'I','I','I','I', 'N', 'O','O','O','O','O', 'U','U','U','U', 'Y', '_'), $string); 
}