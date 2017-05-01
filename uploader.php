<?php
require( './lib/dbhandler.php' );

if ( count( $_FILES ) > 0 ) {
	$newname = getCleanAndUniqueFileNameFor( $_FILES['file']['name'] );
	if ( strlen( $newname ) > 3 && move_uploaded_file( $_FILES['file']['tmp_name'], './' . UPLOAD_FOLDER . '/' . $newname ) ) {
		echo saveObject( $newname );
	} else {
		echo 'failed';
	}
	exit();
}