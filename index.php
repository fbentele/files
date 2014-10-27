<?php
/*
* Upload Files
*
*/
require_once('./lib/dbhandler.php');
?>

<!DOCTYPE html>  
<html>  
<head>  
	<meta charset="utf-8">  
    <title>File Sharing</title>
    <script src="./lib/dropzone.js"></script>
    <script src="./lib/jq.js"></script>	
    <link rel="stylesheet" href="./lib/styles.css" />
</head>  
<body>
    <div id="content">
		<p>Quickly share files with others.<br />Neither guaranteed.<br />Nor secure.</p>
	    <div id="link">Share this link: <br /></div>
	    <form action="/uploader.php" class="dropzone" id="thedropzone">Drop Files here, or click to choose
	    <div id="success"><span>✔</span></div>
	    <span class="small">(max 100 MB)</span></form>
    </div>
    <div id="footer">
	    <p>~Flo, 2013<br />
	    	Using <a href="http://jquery.com/" target="_blank">jQuery</a>, <a href="http://www.redbeanphp.com/" target="_blank">RedBeanPHP</a> and <a href="http://www.dropzonejs.com/" target="_blank">dropzone.js</a></p>
    </div>
    <script type="text/javascript">
		Dropzone.options.thedropzone = {
			init: function() {
				this.on("success", function(file, message) {
					$('#link').show('fast');
					$('#success').show('fast');
					$('#link').append('<input type="text" value="<?php echo HOSTNAME . "/download?"; ?>'+message+'" onClick="this.select();"/><br />');
				});
			}
		};
	</script>
</body>  
</html>