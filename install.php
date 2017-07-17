<?php
require_once( __DIR__ . '/lib/dbhandler.php' );


// Todo: 	check dependencies, 
// 			create db, sqlite or mysql/mariadb
//			import initial user, 
//			check permission on files folder,
// 			write settings file, etc.

session_start();

// comment this to activate install script!
// die( 'Sorry, you need to enable the install script!' );

if ( isset( $_GET['action'] ) ) {
	switch ( $_GET['action'] ) {
		case 'createadmin':
			echo 'create admin user...<br />';
			createAdminUser();
			break;
		case 'nukeit':
			echo 'nukeing db...<br />';
			DB::nukeit();
			break;
		case 'initialdata':
			echo 'inserting data...';


	$path_parts = pathinfo('20170302_parlament_sg.zip', PATHINFO_EXTENSION);
    $file = R::dispense('file');
	$file->filename='20170302_parlament_sg.zip';
	$file->uploadtime=date('Ymd_His');
	$file->lastdownloaded=0;
	$file->ip=$_SERVER["REMOTE_ADDR"];
	$file->filetype=$path_parts;
	$file->downloadId='d60b05e878a4ef6fe7cad66669861b6b';
	$file->downloaded=0;
	$id = R::store($file);

	checkDirSize(UPLOAD_FOLDER);

	return $file->downloadId;



			break;
	}
}

echo '<h1>Install Helper Script</h1>';
echo '<a href="/">Home</a><br />';
echo '<a href="/install?action=nukeit">Delete DB</a><br />';
echo '<a href="/install?action=createadmin">Create Admin</a><br />';
echo '<a href="/install?action=initialdata">Insert dummy data</a><br />';


function createAdminUser() {
	// intially insert admin user
	echo insertUser( "admin", "Administrator", hashPlaintext( "1234" ), "", "admin" );
	echo 'admin user created!';

}

function populateQuestions() {
	$admin = DB::find( 'user', 'username', 'admin' );
	DB::create( 'item', array(
		'question'    => 'Wie lautet die Kreisformel?',
		'solution'    => 'r^2*Pi (Radios in der zweiten Potenz mal Pi',
		'subject'     => 'Mathematik',
		'topic'       => 'Geometrie Grundlagen',
		'shared'      => 'true',
		'answerlines' => '1',
		'points'      => '2',
		'owner'       => $admin
	) );
	DB::create( 'item', array(
		'question'    => 'Wie viel beträgt der Innenwinkeln im gleichschenkligen Dreieck?',
		'solution'    => '30°',
		'subject'     => 'Geometrie',
		'topic'       => 'Geometrie Grundlagen',
		'shared'      => 'true',
		'answerlines' => '1',
		'points'      => '4',
		'owner'       => $admin
	) );
	echo 'dummy data inserted!';
}

