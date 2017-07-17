<?php
require( './lib/dbhandler.php' );

if ( count( $_FILES ) > 0 ) {
	$newname = getCleanAndUniqueFileNameFor( $_FILES['file']['name'] );
	if ( strlen( $newname ) > 3 && move_uploaded_file( $_FILES['file']['tmp_name'], './' . UPLOAD_FOLDER . '/' . $newname ) ) {
		echo saveObject( $newname );
		notify_admin($newname);
	} else {
		echo 'failed';
	}
	exit();
}

function notify_admin($file) {
	mail( ADMIN_MAIL, "Neue Datei hochgeladen " . $file, "Waren Sie das? Dann ignorieren Sie diese Mail, andernfalls schauen Sie doch mal unter " . HOSTNAME . "/admin nach ob da alles noch stimmt." , "From: files@files.stadt.sg\r\n" . "X-Mailer: php" );
}