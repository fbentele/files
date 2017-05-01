<?php
require( './secure.php' );
require_once( './lib/settings.php' );

// Delete file and redirect
if ( isset( $_GET['delete'] ) ) {
	deleteFile( $_GET['delete'] );
	header( "location:/admin" );
}
?>

<html>
<head>
    <meta charset="utf-8">
    <title>File Sharing</title>
    <script src="./lib/dropzone.js"></script>
    <script src="./lib/jq.js"></script>
    <link rel="stylesheet" href="./lib/styles.css"/>
</head>
<body>
<div id="admincontent">
    <div id="changepwbox">
        <form action="/secure?changepw" method="post">
            <input name="oldpass" type="password" required="required" placeholder="Old Password"><br/>
            <input name="newpass" type="password" required="required" placeholder="New Password"><br/>
            <input name="newpass2" type="password" required="required" placeholder="Again New Password"><br/>
            <input type="submit">
        </form>
    </div>
    <h2>Storage used:</h2>
	<?php
	echo "The " . UPLOAD_FOLDER . " folder contains " . nicedirsize( UPLOAD_FOLDER ) . " of files.";
	?>
    <h2>Files without id:</h2>
	<?php
	$fileswithnoid = filesWithNoId();
	if ( $fileswithnoid ) {
		foreach ( $fileswithnoid as $file ) {
			echo '<li>' . $file . '</li>';
		}
	} else {
		echo "No files without id.";
	}
	?>

    <h2>Orphans:</h2>
	<?php
	$allOrphans = showOrphaned();
	if ( $allOrphans ) {
		echo '<ul class="orphans">';
		foreach ( $allOrphans as $orphan ) {
			echo '<li>
					<span class="trash">
						<a href="/admin?delete=' . $orphan->downloadId . '">
							<img src="/img/trash.png" />
						</a>
					</span>';
			echo $orphan->downloadId . "<br />";
			echo $orphan->filename;
			echo "</li>";
		}
		echo '</ul>';
	} else {
		echo "No database orphanes.";
	}
	?>

    <h2>All Files:</h2>
    <table class="allfiles" cellspacing="0">
        <tr>
            <th>Filename</th>
            <th>IP</th>
            <th>Times</th>
            <th>Uploaded</th>
            <th>Last downloaded</th>
            <th>Delete</th>
        </tr>
		<?php
		foreach ( getAllFiles() as $file ) {
			$lastdownloaded = ( $file->lastdownloaded == 0 ) ? "never" : $file->lastdownloaded;
			echo "<tr>";
			echo '<td><a href="/download?' . $file->downloadId . '">' . $file->filename . '</a></td>';
			echo "<td>" . $file->ip . "</td>";
			echo "<td>" . $file->downloaded . "</td>";
			echo "<td>" . $file->uploadtime . "</td>";
			echo "<td>" . $lastdownloaded . "</td>";
			echo "<td>" . '<span class="trash"><a href="/admin?delete=' . $file->downloadId . '"><img src="/img/trash.png" /></a></span>' . "</td>";
			echo "</tr>";
		}
		?>
    </table>
    <h2>All Users:</h2>
	<?php
	$allUsers = getAllUsers();
	if ( $allUsers ) {
		echo '<ul class="orphans">';
		foreach ( $allUsers as $user ) {
			echo '<li>
					<span class="trash">
						<a href="/admin?deleteuser=' . $user->username . '">
							<img src="/img/trash.png" />
						</a>
					</span>';
			echo $user->username;
			echo "<br /><br /></li>";
		}
		echo '</ul>';
	} else {
		echo "No Users";
	}
	?>
</div>
<div id="adminfooter"><a class="changepw" href="/secure?changepw">Change PW</a> &nbsp;| &nbsp;<a href="/secure?logout">Logout</a></div>
<script>
    $(document).ready(function () {
        $('.changepw').click(function (e) {
            e.preventDefault();
            $('#changepwbox').show();
        });
    });
</script>
</body>
</html>
