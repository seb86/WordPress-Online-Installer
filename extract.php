<?php
session_start();
ob_start("ob_gzhandler");
ini_set('display_errors', 'off');
ini_set('memory_limit', '64M');
set_time_limit(1024); /* Maximum execution time of each script, in seconds */
include('config.php');
include('functions.php');
$debugExtract = $_POST['debug_mode'];
$url_address = $_POST['url_address'];
$upload_destination = dirname(__FILE__);
$templocation = "wordpress";

displayHeader();
?>

<div class="content">
<h1>Now Extracting</h1>

<?php
/* Checks if the unzipper file is on server. */
if(file_exists($upload_destination.'/pclzip.lib.php')){ include($upload_destination.'/pclzip.lib.php'); }
else{
	if(enable_custom_wp_setup != 'yes'){ if(file_exists($upload_destination.'/functions.php')){ unlink($upload_destination.'/functions.php'); } }
	if(file_exists($upload_destination.'/extract.php')){ unlink($upload_destination.'/extract.php'); }
	echo "<p>".wpsetup_title." did not upload the zip extractor fully to your host. Please try again and sorry for the inconvience.</p>";
	die("<p>".wpsetup_footer." ".date("Y")."</p>");
}
/* Filename of the zip file to extract. */
if(!empty($_GET['language'])){ $wplang = $_GET['language']; }
$file = "extract-".$_GET['wpversion']."".$wplang.".zip";
$archive = new PclZip($file);
if(($v_result_list = $archive->extract(PCLZIP_OPT_PATH, $upload_destination)) == 0){
	echo "<li style=\"color:red;\">Error: <b>".$archive->errorInfo(true)."</b></li>\n";
	echo "</ul>";
	die("<p>".wpsetup_footer." ".date("Y")."</p>");
}
else{
	/* Move WordPress files to the destination directory. */
	folderExists($templocation, $upload_destination, 'yes', '');
}
/* Removes content from WP-Admin folder */
folderExists($templocation, '', '', 'yes');
/* Removes temporary files. */
if(!is_dir($templocation)){
	if(file_exists($upload_destination.'/'.$file)){
		unlink($upload_destination.'/'.$file);
		unlink($upload_destination.'/pclzip.lib.php');
		if(enable_custom_wp_setup != 'yes'){
			unlink($upload_destination.'/functions.php');
		}
		unlink($upload_destination.'/extract.php');
	}
}
echo "<li style=\"color:green\">Temporary files removed.</li>\n";
echo "</ul>\n";
chmod($upload_destination, 0755); /* Make folder no longer writable for the public. */
?>
	<h1>WordPress Extracted Successfully!</h1>
	<p>Now you are free to start setting up WordPress to connect to your database and create a unique username and password.</p>
	<p>Any major changes made to future versions of WordPress, I will make sure this service works for any new files or folder directories added to the system.</p>
	<form id="setup"<?php if(enable_custom_wp_setup == 'yes'){ echo 'style="float:left;"'; } ?> action="<?php echo $url_address; ?>/wp-admin/install.php" method="post">
	<input type="hidden" name="upload_destination" value="<?php echo $upload_destination; ?>" />
	<p class="step"><input type="submit" name="Submit" value="Setup WordPress" class="button green" /></p>
	</form>
	<form id="setup"<?php if(enable_custom_wp_setup == 'yes'){ echo 'style="float:right;"'; } ?> action="<?php echo $url_address; ?>/custom-setup.php" method="post">
	<input type="hidden" name="url_address" value="<?php echo $url_address; ?>" />
	<input type="hidden" name="upload_destination" value="<?php echo $upload_destination; ?>" />
	<p class="step"><input type="submit" name="Submit" value="Setup WordPress and More" class="button blue" /></p>
	</form>
	<div style="clear:both;"></div>
	<p><?php echo wpsetup_footer." ".date("Y"); ?></p>
</div>
<?php if(enable_custom_wp_setup != 'yes'){ unlink($upload_destination.'/config.php'); } ?>
</body>
</html>