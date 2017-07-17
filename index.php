<?php
require_once( __DIR__ . '/lib/dbhandler.php' );
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>File Sharing</title>
    <script src="./lib/dropzone.js"></script>
    <script src="./lib/jq.js"></script>
    <link rel="stylesheet" href="./lib/styles.css"/>
</head>
<body>

<div id="content">
    <div class="clear">
        <h1 class="left">Dokumente</h1>
        <div class="right">
            <img src="img/logo-stsg.jpg" alt="Logo Stadt St. Gallen">
        </div>
    </div>

	<?php

	if ( ! file_exists( UPLOAD_FOLDER ) && ! is_writable( UPLOAD_FOLDER ) ) {
		echo '<div class="error">Der Upload Ordner kann nicht beschrieben werden, oder es gibt ihn nicht.</div>';
	}

	?>
    <p>Einfach Dokumente und Dateien Teilen.<br/>Öffentlich mit Link.</p>
    <div id="link">Link zum teilen: <br/></div>
    <form action="/uploader.php" class="dropzone" id="thedropzone">Datei hier hineinziehen, oder klicken zum auswählen.
        <div id="success"><span>✔</span></div>
        <span class="small">(max 100 MB)</span></form>
</div>
<div id="footer">
    <p><a href="http://www.alea-iacta.ch">alea iacta digital gmbh</a></p>
</div>
<script type="text/javascript">
    Dropzone.options.thedropzone = {
        init: function () {
            this.on("success", function (file, message) {
                $('#link').show('fast');
                $('#success').show('fast');
                $('#link').append('<input type="text" value="<?php echo HOSTNAME . "/download?"; ?>' + message + '" onClick="this.select();"/><br />');
            });
        }
    };
</script>
</body>
</html>