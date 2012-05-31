<?php
session_start();
ob_start("ob_gzhandler");
ini_set('display_errors', 'off');
ini_set('memory_limit', '64M');
set_time_limit(1024); /* Maximum execution time of each script, in seconds */
include('config.php');
include('functions.php');
$url_address = $_POST['url_address'];
$upload_destination = $_POST['server_root'];
$wpVersion = $_REQUEST['wpversion'];
define(ABSPATH, $upload_destination); // Absolute Path

// Header of the site is placed here normally.

if(isset($_GET['step']))
	$step = $_GET['step'];
else
	$step = 0;

echo "<div class=\"content\">\n";

switch($step){
	case 0:
?>
<h1>Custom Setup</h1>
<p>Welcome to the custom setup for WordPress.</p>
<p style="color:red;">Please note: This is <strong>not</strong>, <em>I repeat</em>, <strong>not the official WordPress</strong> setup. It is more or less exactly the same but with the extras added on top.</p>
<p>If you have changed your mind and wish to install WordPress using the default setup process, click <a href="<?php echo $url_address; ?>/wp-admin/install.php">here</a>, otherwise, let&#8217;s begin.</p>
<p>Now before getting started, some information on the database is required. You will need to know the following items before proceeding.</p>
<ol>
	<li>Database name</li>
	<li>Database username</li>
	<li>Database password</li>
	<li>Database host</li>
	<li>Table prefix (if you want to run more than one WordPress in a single database) </li>
</ol>
<p>These items should have been supplied to you by your Web Host. If you do not have this information, then you will need to contact them before you can continue. If you&#8217;re all ready, let&#8217;s begin. &hellip;</p>
<p><strong>If for any reason this automatic file creation doesn&#8217;t work, don&#8217;t worry. All this does is fill in the database information to a configuration file. You may also simply open <code>wp-config-template.php</code> in a text editor, fill in your information, and save it as <code>wp-config.php</code>.</p>
<p><code>wp-config-template.php</code> has the same values as <code>wp-config-sample.php</code> with a few more added adjustments.</strong></p>
<p style="color:red;">Please note: At the end of the setup both <code style="color:#000000;">wp-config-template.php</code> and <code style="color:#000000;">wp-config-sample.php</code> will be deleted for security purposes.</p>
<form action="<?php echo $url_address; ?>/custom-setup.php?step=1" method="post">
<input type="hidden" name="url_address" value="<?php echo $url_address; ?>" />
<p class="step"><center><input class="button green" type="submit" name="step1" value="Let&#8217;s Go!" /></center></p>
</form>
<?php break; case 1: // Database form. ?>
<h1>Custom Setup -> Database</h1>
<form method="post" action="<?php echo $url_address; ?>/custom-setup.php?step=2">
	<p>Enter your database connection details below. If you&#8217;re not sure about these, contact your host and ask them what they are.</p>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="dbname">Database Name</label></th>
			<td><input name="dbname" id="dbname" type="text" size="25" value="database_name" /></td>
			<td>The name of the database you want to run WordPress in.</td>
		</tr>
		<tr>
			<th scope="row"><label for="uname">User Name</label></th>
			<td><input name="uname" id="uname" type="text" size="25" value="username" /></td>
			<td>Your MySQL username</td>
		</tr>
		<tr>
			<th scope="row"><label for="pwd">Password</label></th>
			<td><input name="pwd" id="pwd" type="text" size="25" value="password" /></td>
			<td>...and MySQL password.</td>
		</tr>
		<tr>
			<th scope="row"><label for="dbhost">Database Host</label></th>
			<td><input name="dbhost" id="dbhost" type="text" size="25" value="localhost" /></td>
			<td>You should be able to get this info from your web host, if <code>localhost</code> does not work.</td>
		</tr>
		<tr>
			<th scope="row"><label for="portnumber">Port Number (optional)</label></th>
			<td><input name="port" id="dbhost" type="text" size="5" value="" /></td>
			<td>Only enter if it is required for your web host to connect.</td>
		</tr>
		<tr>
			<th scope="row"><label for="prefix">Table Prefix</label></th>
			<td><input name="prefix" id="prefix" type="text" id="prefix" value="wp_<?php echo time(); ?>_" size="25" /></td>
			<td>If you want to run multiple WordPress installations in a single database, change this. <span style="color:red;">P.S. This table prefix is auto-generated with the a time stamp.</span></td>
		</tr>
	</table>
	<input type="hidden" name="url_address" value="<?php echo $url_address; ?>" />
<p class="step"><center><input class="button green" type="submit" name="step2" value="Submit and Test Connection" /></center></p>
</form>
<?php
	break;

	case 2:
	$dbname  = trim($_POST['dbname']);
	$uname   = trim($_POST['uname']);
	$passwrd = trim($_POST['pwd']);
	$dbhost  = trim($_POST['dbhost']);
	$port = trim($_POST['port']);
	$prefix  = trim($_POST['prefix']);
	if(empty($prefix)){ $prefix = 'wp_'; }

	// Validate $prefix: it can only contain letters, numbers and underscores
	if(preg_match('|[^a-z0-9_]|i', $prefix)){ die('<strong>ERROR</strong>: "Table Prefix" can only contain numbers, letters, and underscores.'); }

	// Test the db connection.
	define('DB_NAME', $dbname);
	define('DB_USER', $uname);
	define('DB_PASSWORD', $passwrd);
	define('DB_HOST', $dbhost);
	define('PORT', $port);
	define('PREFIX', $prefix);

	// We'll fail here if the values are no good.
	if(!empty($port)){
		$link = mysql_connect(DB_HOST.':'.PORT, DB_USER, DB_PASSWORD);
	}
	else{
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	}
	if(!$link){
		die('<p class="step">Whoops! Doesn\'t look like your connection to your database is correct. Please go back and <a href="'.$url_address.'/custom-setup.php?step=1" onclick="javascript:history.go(-1);return false;">Try Again</a>.</p>');
	}

	if(!file_exists(dirname(__FILE__)."/wp-config.php")){ //If a wordpress configuration file has not been created then continue
	// Copy wp-config-template.php file
	if(!copy('wp-config-template.php', 'wp-config.php')){ echo '<p>The configuration file was unable to copy. Hit the refresh button to try again. If this continues not to copy, please make sure your folder permissions are set as \'755\'. Other wise please report it to me on my <a href="http://www.sebs-studio.com/contact" target="_blank">contact</a> page at my site. Thank you.</p>'; }
	else{ echo '<p>Your <code>wp-config.php</code> file has been created. Your database connections have been inserted and salted.</p>'; }
	}
	else{ // A config file already exists and the template file has been removed.
		echo '<p>It looks like you hit refresh. You already have a <code>wp-config.php</code> file created and both the config template and the config sample files have already been removed. To make a change in your <code>wp-config.php</code> file you will have to open it on your host.</p>';
	}

	// Remove the wp-config-template.php file from the users server
	if(file_exists(dirname(__FILE__)."/wp-config-template.php")){ unlink(dirname(__FILE__).'/wp-config-template.php'); }
	// Removes the original wp-config-sample.php file from the users server
	if(file_exists(dirname(__FILE__)."/wp-config-sample.php")){ unlink(dirname(__FILE__).'/wp-config-sample.php'); }

	// Read the file and its content.
	$string = file_get_contents(ABSPATH.'wp-config.php');

	// Define matching arrays of find/replace strings
	$find			= array('database_name_here', 'username_here', 'password_here', 'localhost');

	$findSalt	= array('AUTH_VALUE', 'AUTH_SECURE_VALUE', 'LOGGED_IN_VALUE', 'NONCE_VALUE', 'AUTH_PEPPER', 'AUTH_SECURE_PEPPER', 'LOGGED_IN_PEPPER', 'NONCE_PEPPER');

	// Fetch or generate keys and salts.
	$secret_keys = file('https://api.wordpress.org/secret-key/1.1/salt/');
	if(!empty($secret_keys)){
		foreach($secret_keys as $line => $value){
			$secret_keys[$line] = substr($value, 28, 64);
		}
	}

	// Replace Database Connections with Users Input
	$replace	= array($dbname, $uname, $passwrd, !empty($port) ? $dbhost.':'.$port : $dbhost);
	// Replace keys and salts with fetch generated keys and salts.
	$replaceSalt = array($secret_keys[0], $secret_keys[1], $secret_keys[2], $secret_keys[3], $secret_keys[4], $secret_keys[5], $secret_keys[6], $secret_keys[7]);

	// Replace strings
	$string = str_replace($find, $replace, $string);
	$string = str_replace($findSalt, $replaceSalt, $string);
	
	// Replace PREFIX value only
	$string = str_replace('TABLE_PREFIX', $prefix, $string);

	// Write new strings in the content of the file.
	file_put_contents(ABSPATH.'wp-config.php', $string);
?>
<p>Ok, you've made it through this part of the installation. Great! WordPress can now communicate with your database. When you are ready to setup &hellip;</p>
<form action="<?php echo $url_address; ?>/custom-setup.php?step=3" method="post">
<input type="hidden" name="url_address" value="<?php echo $url_address; ?>" />
<p class="step"><center><input class="button green" type="submit" name="step3" value="Customise WordPress" /></center></p>
</form>
<?php
	break;

	case 3: // Customise the wp-config.php file.
?>
<h1>Custom Setup -> Additional Options</h1>
<form method="post" action="<?php echo $url_address; ?>/custom-setup.php?step=4">
	<p>This is were the fun begins. Use this form to select and adjust what you would like WordPress to do.</p>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="dbug_mode">Debug Mode</label></th>
			<td>
			<select name="wp_debug">
			<option value="false" selected="selected">False</option>
			<option value="true">True</option>
			</select>
			</td>
			<td>For developers: WordPress debugging mode.</td>
		</tr>
		<tr>
			<th scope="row"><label for="trash">Trash</label></th>
			<td><input name="wp_trash" type="text" size="3" value="1" /></td>
			<td>If you don’t like the trash feature at all, then you can always set the number to 0, and get rid of it entirely. Otherwise enter how many days you wish to keep your content in the trash until removed permantly.</td>
		</tr>
		<tr>
			<th scope="row"><label for="autosave">AutoSave</label></th>
			<td><input name="wp_autosave" type="text" size="3" value="300" /></td>
			<td>Sets how long (in seconds) you want WordPress to wait until autosaving your posts.</td>
		</tr>
		<tr>
			<th scope="row"><label for="revisions">Revisions</label></th>
			<td>
			<select name="wp_post_revisions">
			<option value="false" selected="selected">False</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
			<option value="13">13</option>
			<option value="14">14</option>
			<option value="15">15</option>
			<option value="16">16</option>
			<option value="17">17</option>
			<option value="18">18</option>
			<option value="19">19</option>
			<option value="20">20</option>
			</select>
			</td>
			<td>If you want revisions, select a number for the amount of revisions you wish to have per post.</td>
		</tr>
		<tr>
			<th scope="row"><label for="memory">Memory</label></th>
			<td><input name="wp_memory" type="text" size="3" value="64" /></td>
			<td>Set the memory size you would like for WordPress.</td>
		</tr>
		<tr>
			<th scope="row"><label for="optimize">Database Optimization and Repair</label></th>
			<td>
			<select name="wp_db_optimize">
			<option value="false">False</option>
			<option value="true" selected="selected">True</option>
			</select>
			</td>
			<td>If set to true, you can view this page: <em style="color:orange;"><?php echo $url_address; ?>/wp-admin/maint/repair.php</em></td>
		</tr>
		<tr>
			<th scope="row"><label for="network">Multi Network</label></th>
			<td>
			<select name="wp_multi_network">
			<option value="false" selected="selected">False</option>
			<option value="true">True</option>
			</select>
			</td>
			<td>For multi-site network functionality, set to true.</span></td>
		</tr>
		<tr>
			<th scope="row"><label for="ssl_login">Secure Login with SSL</label></th>
			<td>
			<select name="wp_login_secure">
			<option value="false" selected="selected">False</option>
			<option value="true">True</option>
			</select>
			</td>
			<td>Secure SSL Login Page.</span></td>
		</tr>
	</table>
	<p class="step"><center><input class="button green" type="submit" name="step4" value="Insert Customizations" /></center></p>
	<input type="hidden" name="url_address" value="<?php echo $url_address; ?>" />
</form>
<?php
	break;
	
	case 4: // Insert customizations into config file.

	$wpDebug		= trim($_POST['wp_debug']);
	$wpTrash		= trim($_POST['wp_trash']);
	$wpAutosave		= trim($_POST['wp_autosave']);
	$wpRevisions	= trim($_POST['wp_post_revisions']);
	$wpMemory		= trim($_POST['wp_memory']);
	$wpRepair		= trim($_POST['wp_db_optimize']);
	$wpNetwork		= trim($_POST['wp_multi_network']);
	$wpSecure		= trim($_POST['wp_login_secure']);

	// Validate $trash: it can only contain numbers.
	if(preg_match('|[^0-9]|i', $wpTrash)){ die('<strong>ERROR</strong>: "Trash" can only contain numbers.'); }
	// Validate $autosave: it can only contain numbers.
	if(preg_match('|[^0-9]|i', $wpAutosave)){ die('<strong>ERROR</strong>: "AutoSave" can only contain numbers.'); }
	// Validate $memory: it can only contain numbers.
	if(preg_match('|[^0-9]|i', $wpMemory)){ die('<strong>ERROR</strong>: "Memory" can only contain numbers.'); }

	// Define each value.
	define('WP_DEBUG', $wpDebug);
	define('EMPTY_TRASH_DAYS', $wpTrash);
	define('AUTOSAVE_INTERVAL', $wpAutosave);
	define('WP_POST_REVISIONS', $wpRevisions);
	define('WP_MEMORY_LIMIT', $wpMemory.'M');
	define('WP_ALLOW_REPAIR', $wpRepair);
	define('WP_ALLOW_MULTISITE', $wpNetwork);
	define('FORCE_SSL_ADMIN', $wpSecure);

	// Read the file and its content.
	$string = file_get_contents(ABSPATH.'wp-config.php');

	if($wpDebug == false){ $wpDebug = false; }

	// Define matching arrays of find/replace strings
	$find = array('debug_mode_value', 'empty_trash_value', 'autosave_time_value', 'post_revisions_value', '64', 'repair_value', 'multisite_value', 'ssl_admin_value');
	$replace = array($wpDebug, $wpTrash, $wpAutosave, $wpRevisions, $wpMemory, $wpRepair, $wpNetwork, $wpSecure);

	// Replace strings
	$string = str_replace($find, $replace, $string);

	// Write new strings in the content of the file.
	file_put_contents(ABSPATH.'wp-config.php', $string);

	// Set this file to read-only.
	chmod(ABSPATH.'wp-config.php', 0666);
?>
<p>Congratulations, your configuration file is now setup and secure. <!--Now we need to upload WordPress table structure and default values. Once that is done we can then config your site Title and Description, your fake 'admin' user (as a subscriber) and create your real administrator account.-->You can now continue to the main WordPress setup.</p>
<p>More features may come in the future for the custom setup. If you have any ideas on additions to be added, please send me an email at sebastien[at]sebs-studio.com</p>
<p>Thank you for using <?php echo wpsetup_title; ?> - Custom Setup.</p>
<p class="step"><center><a href="<?php echo $url_address; ?>/wp-admin/install.php" class="button">Install WordPress</a></center></p>
<?php
	// These files are removed here for the moment until further progress with the custom setup.
	if(file_exists(dirname(__FILE__)."/tempconfig.php")){ unlink(dirname(__FILE__).'/tempconfig.php'); }
	if(file_exists(dirname(__FILE__)."/functions.php")){ unlink(dirname(__FILE__).'/functions.php'); }

	break;

	case 5:

	// Require the configurations from the 'wp-config.php' file once.
	require_once('wp-config.php');
	// Link to the database connection that the user had entered in the 'wp-config.php' file.
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$link){
		die('<p class="step">Whoops! I\'t looks like your connection to your database is incorrect to what you have entered in your \'<code>wp-config.php</code>\' file. You will have to manually edit your database connection in your \'<code>wp-config.php</code>\' file and correct the problem before continuing. Once done, hit refresh[F5] on this page and we should be able to continue the process.</p>');
	}
	// Select the database that the user had entered in the 'wp-config.php' file.
	$db_selected = mysql_select_db(DB_NAME, $link);
	if(!$db_selected){
		die('<p class="step">Whoops! Can\'t use '.DB_NAME.' : '. mysql_error().'</p>');
	}
	if(file_exists("create-database-structure.php")){ include_once("create-database-structure.php"); }
	// Connection is closed to the database until we need to connect again later on.
	mysql_close($link);
?>
<h1>Custom Setup -> Data Inserting</h1>
<form method="post" action="<?php echo $url_address; ?>/custom-setup.php?step=6">
	<p>Ok, your tables have been inserted, now we need to insert some data.</p> 
	<table class="form-table">
		<tr>
			<th scope="row"><label for="site_title">Site Title *</label></th>
			<td><input name="site_title" type="text" size="80" value="" />
					<p>Type the title of the site.</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="site_description">Site Description</label></th>
			<td><input name="site_description" type="text" size="80" value="" />
					<p>Type the description of the site.</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="fake_admin">Fake Admin User</label></th>
			<td>
			<select name="fake_admin">
			<option value="no" selected="selected">No</option>
			<option value="yes">Yes</option>
			</select>
			<span><big>If yes, a fake 'admin' user will be created as a subscriber.</big></span>
			<p>This is a great security feature.</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="real_user">Username</label></th>
			<td><input name="username" type="text" size="30" value="username" />
					<p>Type the username you wish to have as administrator.</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="pass">Password</label></th>
			<td><input name="pass" type="password" size="30" value="password" />
					<p>Type in your password for the real admin user.</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="pass_verify">Password (Verify) *</label></th>
			<td><input name="pass2" type="password" size="30" value="passwordtwo" />
					<p>Type in the same password as the one above to verify.</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="admin_email">Admin E-mail *</label></th>
			<td><input name="admin_email" type="text" size="60" value="" />
					<p>Type in your e-mail address.<br />This e-mail address will be used to send you details of this setup.</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="site_public">Show in Search Engines</label></th>
			<td>
			<select name="show_site_public">
			<option value="false" selected="selected">Hide Me</option>
			<option value="true">Tell the World about Me</option>
			</select>
			<p>If you don't want your site seen publicly at the moment, then select 'Hide Me'.</p>
			</td>
		</tr>
	</table>
	<p class="step"><center><input name="submit" type="submit" value="Insert Customizations" class="button" /></center></p>
</form>

<?php
	break;

	case 6:

	break;

	case 7:
?>
<h1>Custom Setup -> Complete</h1>
<p>Everything is now ready and setup. You can now login to use WordPress.</p>
<p>More features may come in the future for the custom setup. If you have any ideas on additions to be added, please send me an email at sebastien[at]sebs-studio.com</p>
<p>Thank you for using <?php echo wpsetup_title; ?> - Custom Setup.</p>
<form id="setup" style="float:left;" action="<?php echo $url_address; ?>/wp-admin/install.php" method="post">
<p class="step"><center><input name="submit" type="submit" value="Login" class="button" /></center></p>
</form>
<?php
	break;
}
?>
<p><?php echo wpsetup_footer." ".date("Y"); ?></p>
</div>

</body>
</html>