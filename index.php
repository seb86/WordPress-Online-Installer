<?php
/**
 * WordPress Online Installer
 * Version: 1.3
 * Created by Seb's Studio
 * URL: http://www.sebs-studio.com
 */

session_start();
ob_start("ob_gzhandler");
include('config.php');
include('functions.php');

// Header of the site goes here normally.
?>

<div class="content">
<h1>Welcome</h1>
<p>You have arrived on the very first WordPress Online Installer. Here you are able to setup WordPress very easy and save time with out any hassle.</p>
<p>Simply click on "Download WordPress Now!" and a fresh installation will be downloaded and extracted for you ready to setup straight away. It saves you time from manually downloading WordPress, extract the content and upload it to your server by FTP manually.</p>
<p>So let's get started on installing your new WordPress. Simply enter your connections below, select the language, version and the location you wish to install WordPress in.</p>
<?php
if(empty($_SERVER['HTTPS'])){
	echo '<p style="color:red">Your FTP credentials are not encrypted. Please make sure you start filling in the form below on the <a href="https://www.wpsetup.org">secured</a> version of this site before proceeding.</p>';
}
else{
	echo '<p style="color:green">Your FTP credentials are encrypted.</p>';
}
?>
<quote style="color:red">Please note that this site has no part of, or endorsed by, WordPress or Automattic what so ever. All copyrights of WordPress are reserved by Automatic. This is a service site created by <a href="http://www.sebs-studio.com" target="_blank" title="Sebs Studio" rel="external">Sebs Studio</a> to help you install WordPress quicker with ease.</quote>
<p style="color:blue"><b>Custom Setup</b>: Allows you to customise your 'wp-config.php' file. Increase memory, setup trash, autosave and revisions, enable networking, enable debugging mode and more including deleting the wp-config-sample.php file once complete. You will need <b><em>allow_url_fopen</em></b> enabled in your PHP config file for the salt keys to be fetched and added to the <b><em>wp-config.php</em></b> file.</p>
<h1>Setup</h1>
<p style="color:red"><b>Final Note</b>: You must have permission to create folders by FTP. Check with your host provider that you are able to create a new folder to extract into by FTP. This will not work unless you have support.</p>
<form id="setup" method="post" action="<?php echo wpsetup_domain; ?>/upload.php">
	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="url">URL Address *</label>
			</th>
			<td>
			<input name="url_address" type="text" size="70" placeholder="http://www.yourdomain.com" value="http://" onblur="this.value=(this.value=='') ? 'http://' : this.value;" onfocus="this.value=(this.value=='http://') ? '' : this.value;" />
			<p>Type in your website url address.</p>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="ftp_server">FTP Server *</label>
			</th>
			<td>
			<input name="ftp_server" type="text" size="70" placeholder="ftp.yourdomain.com" value="ftp." onblur="this.value=(this.value=='') ? 'ftp.' : this.value;" onfocus="this.value=(this.value=='ftp.') ? '' : this.value;" />
			<p>Type in your ftp server address.</p>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="ftp_user_name">Username *</label>
			</th>
			<td>
			<input name="ftp_user_name" type="text" size="30" value="" />
			<p>Type in your username.</p>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="ftp_user_pass">Password *</label>
			</th>
			<td>
			<input name="ftp_user_pass" type="password" size="30" value="" />
			<p>Type in your password.</p>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="url">Directory Root *</label>
				<p>(Absolute Path)</p>
			</th>
			<td>
			<input name="server_root" type="text" size="70" placeholder="www/{directory-root}" value="www/{directory-root}" onblur="this.value=(this.value=='') ? 'www/{directory-root}' : this.value;" onfocus="this.value=(this.value=='www/{directory-root}') ? '' : this.value;" />
			<p>Type in you directory root. This is the folder directory that WordPress will be uploaded and extracted to. Please make sure that the folder directory matches the location of your 'URL Address' if you are inserting WordPress to a new directory.</p>
			<p style="color:red;"><b>Note</b>: Where <b><em>{directory-root}</em></b> is, type in the location where you want to upload WordPress to if it is not directly on the main domain directory.</p>
			<p style="color:red;"><b>Note</b>: Not all directory roots begin with '<b style="color:lightblue;">www/</b>' so please check with your host provider to ask what it is.</p>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="language">Language</label>
				<p>(optional)</p>
			</th>
			<td>
			<select name="wplanguage" size="1">
			<option value="bs_BA">Bosanski</option>
			<option value="ca">Català</option>
			<option value="cs_CZ">Čeština</option>
			<option value="es_CL">Chile</option>
			<option value="zh_CN">中文</option>
			<option value="hr">Hrvatski</option>
			<option value="da_DK">Dansk</option>
			<option value="de_DE">Deutschland</option>
			<option value="" selected="selected">English (Default)</option>
			<option value="es_ES">Español</option>
			<option value="eu">Euskara</option>
			<option value="fi">Finnish</option>
			<option value="fr_FR">Français</option>
			<option value="gl_ES">Galego</option>
			<option value="cy">Galician</option>
			<option value="he_IL">וורדפרס בעברית</option>
			<option value="hu_HU">Magyar</option>
			<option value="id_ID">Indonesian</option>
			<option value="it_IT">Italia</option>
			<option value="ja">日本</option>
			<option value="ko_KR">한국어</option>
			<option value="lv">Latviešu</option>
			<option value="fa_IR">فارسی</option>
			<option value="nb_NO">Norwegian (Bokmål)</option>
			<option value="nn_NO">Norwegian (Nynorsk)</option>
			<option value="nl_NL">Nederland</option>
			<option value="pl_PL">Рolish</option>
			<option value="pt_PT">Portuguese (European)</option>
			<option value="pt_BR">Portuguese (Brazilian)</option>
			<option value="ro_RO">Românâ</option>
			<option value="ru_RU">Русский</option>
			<option value="sr_RS">Српски</option>
			<option value="si_LK">සිංහල</option>
			<option value="sk_SK">Slovenčina</option>
			<option value="sv_SE">Sverige</option>
			<option value="zh_TW">正體中文</option>
			<option value="th">Thai</option>
			<option value="tr_TR">Türkiye</option>
			</select>
			<p>Select the language of WordPress you wish to install if it is not English.</p>
			<p style="color:red;"><b>Note</b>: Only the latest version is available for other languages. Sometimes the version might be a few stages behind the English. This is to do with WordPress themselves, not the installer.</p>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="version_of_wordpress">Version</label>
				<p>(optional)</p>
			</th>
			<td>
			<select name="wpversion" size="1">
			<option value="latest" selected="selected">Current Version (<?php echo $current_version; ?>)</option>
			<option value="nightly">Latest Nightly Build</option>
			<optgroup label="Previous Versions">
			<option value="wordpress-3.2.1">3.2.1 (Last Version of 3.2)</option>
			<option value="wordpress-3.1.4">3.1.4 (Last Version of 3.1)</option>
			</optgroup>
			<optgroup label="Beta">
			<option value="latest">None</option>
			<!--option value="wordpress-3.4-beta4">3.4-beta4</option-->
			</optgroup>
			<optgroup label="Release Candidate">
			<option value="latest">None</option>
			<!--option value="wordpress-3.4-RC1">3.4-RC1</option-->
			</optgroup>
			</select>
			<p>Select the version of WordPress you wish to install if it is not the latest stable version. Beta's and Release Candidates are available when released. <span style="color:red;">Use Nightly Builds at your own risk as I will not support them. Nightly Builds are only in English!</span></p>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="debug_extract">Debug Mode</label>
				<p>(optional)</p>
			</th>
			<td>
			<select name="debug_mode" size="1">
			<option value="no" selected="selected">No</option>
			<option value="yes">Yes</option>
			</select>
			<p>If you wish to view the folders and files extracted.</p>
			</td>
		</tr>
	</table>
	<p class="step"><center><input type="submit" name="Submit" value="Download WordPress Now!" class="button white" /></center></p>
</form>
<p><?php echo wpsetup_footer." 2011-".date("Y"); ?></p>
</div>

</body>
</html>