<?php
session_start();
ob_start("ob_gzhandler");
ini_set('display_errors', 'off');
ini_set('memory_limit', '64M');
set_time_limit(2048); /* Maximum execution time of each script, in seconds */
$debugExtract = $_POST['debug_mode'];
include('config.php');
include('functions.php');

displayHeader();
?>

<div class="content">
<?php
// Fetches the version of WordPress and if nightly build, it renames it so that it can be downloaded.
$wpversion = $_POST['wpversion'];
$wpnight = str_replace('/', '-', $wpversion);
// File path to WordPress.
if(!empty($_POST['wplanguage'])){
	$wplang = $_POST['wplanguage'];
	//$langFilter = str_replace("_", $_POST['wplanguage'], $_POST['wplanguage']);
	if($wplang == 'es_CL'){ $wpdomain = 'cl.'; } // Domain for Chile.
	else if($wplang == 'zh_CN'){ $wpdomain = 'cn.'; } // Domain for Chinese.
	else if($wplang == 'zh_TW'){ $wpdomain = 'tw.'; } // Domain for Taiwan.
	else if($wplang == 'pt_BR'){ $wpdomain = 'br.'; } // Domain for Portuguese (Brazilian).
	else{ $wpdomain = substr($_POST['wplanguage'], 0, 2).'.'; }
	$file_path = "http://".$wpdomain."wordpress.org/latest-".$wplang.".zip";
	$copy_path = "extract-".$wpversion."-".$wplang.".zip";
}
else{
	if($wpversion == 'nightly'){ $wpdir = 'nightly-builds/wordpress-latest'; $extract = $wpnight; }
	else{ $wpdir = $wpversion; $extract = $wpversion; }
	$file_path = "http://wordpress.org/".$wpdir.".zip";
	$copy_path = "extract-".$extract.".zip";
}
$headers = get_headers($file_path);
if($headers[0] == 'HTTP/1.1 200 OK'){
	echo "<ul>\n";
	if(file_exists($copy_path)){
		if($headers[6] > filesize($copy_path)){
			/* Download WordPress if filesize has changed. */
			copy($file_path, $copy_path);
			if($wpversion == 'latest'){
				echo "<li style=\"color:green\">The latest stable version (".$current_version.") of WordPress has been downloaded";
				if(!empty($_POST['wplanguage'])){ echo wplang($_POST['wplanguage']); }
				echo ".</li>\n";
			}
			else{ echo "<li style=\"color:green\">You have downloaded ".$wpversion."</li>\n"; }
		}
		elseif($headers[6] < filesize($copy_path)){
			/* Download WordPress if filesize has changed. */
			copy($file_path, $copy_path);
			if($wpversion == 'latest'){
				echo "<li style=\"color:green\">The latest stable version (".$current_version.") of WordPress has been downloaded";
				if(!empty($_POST['wplanguage'])){ echo wplang($_POST['wplanguage']); }
				echo ".</li>\n";
			}
			else{ echo "<li style=\"color:green\">You have downloaded version (".$wpversion.") of WordPress</li>\n"; }
		}
	}
	else{
		/* Download WordPress if it does not exist. */
		if(!copy($file_path, $copy_path)){
			if($wpversion == 'latest'){ echo "<p style=\"color:red;\">Failed to download the latest version (".$current_version.") of WordPress. Please try again by refreshing this page. [F5]</p>\n"; }
			else{ echo "<p style=\"color:red;\">Failed to download version (".$wpversion.") of WordPress . Please try again by refreshing this page. [F5]</p>\n"; }
			die("<p>".wpsetup_footer." ".date("Y")."</p>");
		}
		else{
			if($wpversion == 'latest'){ echo "<li style=\"color:green\">The latest stable version (".$current_version.") of WordPress has been downloaded";
			if(!empty($_POST['wplanguage'])){ echo wplang($_POST['wplanguage']); }
			echo ".</li>\n"; }
			else{ echo "<li style=\"color:green\">You have downloaded version (".$wpversion.") of WordPress</li>\n"; }
		}
	}
}
// FTP Connection
$ftp_server = $_POST['ftp_server'];
$ftp_user_name = $_POST['ftp_user_name'];
$ftp_user_pass = $_POST['ftp_user_pass'];
// File Destinations
$url_address = $_POST['url_address'];
$upload_destination = $_POST['server_root'];
$ftp_upload_destination = "ftp://{$ftp_server}/{$upload_destination}";
$templocation = $upload_destination."/wordpress";
// when upload of wordpress is complete, redirect to url address.
if(empty($url_address)){
	echo "<p style=\"color:red;\">You have not entered your URL Address. With out this you can not continue the setup once we have uploaded WordPress to your server. Please go back and type it in. Thank you.</p>\n";
	die("<p>".wpsetup_footer." ".date("Y")."</p>");
}
// Set up basic connection.
if(!empty($ftp_server)){
	$conn_id = @ftp_connect($ftp_server) or die("<p style=\"color:red;\">Couldn't connect to $ftp_server</p>");
}
else{
	echo "<p style=\"color:red;\">You have not entered your FTP Server. With out this we can not connect to your server to upload and extract WordPress. Please go back and type it in. Thank you.</p>\n";
	die("<p>".wpsetup_footer." ".date("Y")."</p>");
}
// Login with username and password.
if(empty($ftp_user_name) || empty($ftp_user_pass)){
	echo "<p style=\"color:red;\">FTP Connection is missing username or password. Please go back and type it in.</p>\n";
	die("<p>".wpsetup_footer." ".date("Y")."</p>");
}
else{
	if(@ftp_login($conn_id, $ftp_user_name, $ftp_user_pass)){
		echo "<li style=\"color:green;\">Connected successfully as $ftp_user_name@$ftp_server</li>\n";
		// Get the timeout of the given FTP stream
		$timeout = ftp_get_option($conn_id, FTP_TIMEOUT_SEC);
		echo "<li>Timeout for FTP Connection: <b>$timeout</b> seconds.</li>\n";
	}
	else{
		echo "<p style=\"color:red;\">Error logging into $ftp_server as $ftp_user_name . Please make sure you have typed your FTP login details correctly.</p>";
		die("<p>".wpsetup_footer." ".date("Y")."</p>");
	}
}
// Turn passive mode on.
ftp_pasv($conn_id, true);
if(!empty($upload_destination)){
	if(@ftp_chdir($conn_id, $upload_destination)){
		echo "<li style=\"color:green;\">Folder already successfully created at $upload_destination</li>\n";
		/* Upload latest version of WordPress. */
		if(@ftp_put($conn_id, $upload_destination."/".$copy_path, $copy_path, FTP_BINARY)){
			echo "<li style=\"color:green;\">WordPress has been uploaded successfully to <b>$upload_destination</b></li>\n";
			ftp_put($conn_id, $upload_destination."/pclzip.lib.php", "pclzip.lib.php", FTP_BINARY);
			if(enable_custom_wp_setup == 'yes'){
				ftp_put($conn_id, $upload_destination."/custom-setup.php", "custom-setup.php", FTP_BINARY);
				ftp_put($conn_id, $upload_destination."/wp-config-template.php", "wp-config-template.php", FTP_BINARY);
			}
			ftp_put($conn_id, $upload_destination."/config.php", "config.php", FTP_BINARY);
			ftp_put($conn_id, $upload_destination."/extract.php", "extract.php", FTP_BINARY);
			ftp_put($conn_id, $upload_destination."/functions.php", "functions.php", FTP_BINARY);
		}
		else{
			if(!file_exists($ftp_upload_destination."/".$copy_path)){
				echo "<li style=\"color:red;\">There was a problem uploading WordPress to <b>$upload_destination/$copy_path</b></li>\n";
				echo "</ul>\n";
				die("<p>".wpsetup_footer." ".date("Y")."</p>");
			}
		}
	}
	else{
		if(ftp_mkdir($conn_id, $upload_destination)){
			echo "<li style=\"color:green;\">Folder successfully created at $upload_destination</li>\n";
			/* Upload latest version of WordPress. */
			if(@ftp_put($conn_id, $upload_destination."/".$copy_path, $copy_path, FTP_BINARY)){
				echo "<li style=\"color:green;\">WordPress has been uploaded successfully to <b>$upload_destination</b></li>\n";
				ftp_put($conn_id, $upload_destination."/pclzip.lib.php", "pclzip.lib.php", FTP_BINARY);
				if(enable_custom_wp_setup == 'yes'){
					ftp_put($conn_id, $upload_destination."/custom-setup.php", "custom-setup.php", FTP_BINARY);
					ftp_put($conn_id, $upload_destination."/wp-config-template.php", "wp-config-template.php", FTP_BINARY);
				}
				ftp_put($conn_id, $upload_destination."/config.php", "config.php", FTP_BINARY);
				ftp_put($conn_id, $upload_destination."/extract.php", "extract.php", FTP_BINARY);
				ftp_put($conn_id, $upload_destination."/functions.php", "functions.php", FTP_BINARY);
			}
			else{
				if(!file_exists($ftp_upload_destination."/".$copy_path)){
					echo "<li style=\"color:red;\">There was a problem uploading WordPress to <b>$upload_destination/$copy_path</b></li>\n";
					echo "</ul>\n";
					die("<p>".wpsetup_footer." ".date("Y")."</p>");
				}
			}
		}
		else{
			echo "<li style=\"color:red;\">Folder/Path did not succeed in creating at <b><em>$upload_destination</em></b> Setup is unable to continue with the process. Please make sure that you have permission to create folders by FTP from your <b>Host</b> provider.</li>\n";
			echo "</ul>\n";
			die("<p>".wpsetup_footer." ".date("Y")."</p>");
		}
	}
?>
	<h1>WordPress Uploaded Successfully!</h1>
	<p>Now we just need to extract the files to your new directory. You will be redirected to your server to achieve this and the files will automatically be setup for you.</p>
	<?php if(!empty($_POST['wplanguage'])){ ?> 
	<form id="setup" action="<?php echo $url_address; ?>/extract.php?wpversion=<?php echo $wpversion.'-'.$wplang; ?>" method="post"> 
	<?php }else{ ?> 
		<form id="setup" action="<?php echo $url_address; ?>/extract.php?wpversion=<?php echo $wpversion; ?>" method="post"> 
	<?php } ?> 
	<input type="hidden" name="ftp_server" value="<?php echo $ftp_server; ?>" />
	<input type="hidden" name="url_address" value="<?php echo $url_address; ?>" />
	<input type="hidden" name="upload_destination" value="<?php echo $upload_destination; ?>" />
	<input type="hidden" name="language" value="<?php if(!empty($_POST['wplanguage'])){ echo $wplang; } ?>" />
	<?php if($debugExtract == 'yes'){ ?><input type="hidden" name="debug_mode" value="<?php echo $debugExtract; ?>" /><?php } ?>
	<p class="step"><input type="submit" name="Submit" value="Extract WordPress" class="button green" /></p>
	</form>
	<p><?php echo wpsetup_title; ?> has successfully uploaded: <script language="Javascript" src="<?php echo wpsetup_domain; ?>/counter.php?page=success"></script></p>
	<?php
}
else if(empty($upload_destination)){
	echo "<p style=\"color:red;\">You have not entered a server root. Please <a href=\"javascript: history.go(-1)\">go back</a> and insert one.</p>\n";
}
// close the connection to ftp server.
ftp_close($conn_id);

echo "<p>".wpsetup_footer." ".date("Y")."</p>";
?>
</div>

</body>
</html>