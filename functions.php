<?php
/** 
 * This will scan the WordPress download page
 * for the current version number.
 */
$cHTML = get_data('http://wordpress.org/download/');
list( , $cHTML) = explode('latest stable release of WordPress (Version ', $cHTML);
list($fVersion, ) = explode(')', $cHTML);
$current_version = trim($fVersion);

/* Detects the language of WordPress installing. */
function wplang($lang){
	if($lang == 'bs_BA'){ $wpLang = 'Bosanski'; }
	if($lang == 'ca'){ $wpLang = 'Català'; }
	if($lang == 'da_DK'){ $wpLang = 'Dansk'; }
	if($lang == 'es_ES'){ $wpLang = 'Español'; } // Spanish
	if($lang == 'eu'){ $wpLang = 'Euskara'; }
	if($lang == 'gl_ES'){ $wpLang = 'Galego'; }
	if($lang == 'he_IL'){ $wpLang = 'וורדפרס בעברית'; }
	if($lang == 'hu_HU'){ $wpLang = 'Magyar'; }
	if($lang == 'id_ID'){ $wpLang = 'Indonesian'; }
	if($lang == 'lv'){ $wpLang = 'Latviešu'; }
	if($lang == 'nb_NO'){ $wpLang = 'Norwegian (Bokmål)'; }
	if($lang == 'nn_NO'){ $wpLang = 'Norwegian (Nynorsk)'; }
	if($lang == 'nl_NL'){ $wpLang = 'Nederland'; }
	if($lang == 'ro_RO'){ $wpLang = 'Românâ'; }
	if($lang == 'sr_RS'){ $wpLang = 'Српски'; }
	if($lang == 'si_LK'){ $wpLang = 'සිංහල'; }
	if($lang == 'sk_SK'){ $wpLang = 'Slovenčina'; }
	if($lang == 'zh_TW'){ $wpLang = '正體中文'; }
	if($lang == 'fr_FR'){ $wpLang = 'Français'; } // French
	if($lang == 'de_DE'){ $wpLang = 'Deutschland'; } // German
	if($lang == 'it_IT'){ $wpLang = 'Italia'; } // Italian
	if($lang == 'ru_RU'){ $wpLang = 'Русский'; } // Russian
	if($lang == 'sv_SE'){ $wpLang = 'Sverige'; } // Swedish
	if($lang == 'zh_CN'){ $wpLang = '中文'; } // Chinese
	if($lang == 'ja'){ $wpLang = '日本'; } // Japanese
	if($lang == 'es_CL'){ $wpLang = 'Chile'; } // Chile
	if($lang == 'cy'){ $wpLang = 'Galician'; } // Cyprus
	if($lang == 'fi'){ $wpLang = 'Finnish'; } // Finland
	if($lang == 'hr'){ $wpLang = 'Hrvatski'; } // Croatian
	if($lang == 'pl_PL'){ $wpLang = 'Polish'; } // Polish
	if($lang == 'pt_PT'){ $wpLang = 'Portuguese (European)'; } // Portuguese (European)
	if($lang == 'pt_BR'){ $wpLang = 'Portuguese (Brazilian)'; } // Portuguese (Brazilian)
	if($lang == 'tr_TR'){ $wpLang = 'Türkiye'; } // Turkish
	if($lang == 'cs_CZ'){ $wpLang = 'Čeština'; } // Czech
	if($lang == 'fa_IR'){ $wpLang = 'فارسی'; } // Persian
	if($lang == 'th'){ $wpLang = 'Thai'; } // Thai
	if($lang == 'ko_KR'){ $wpLang = '한국어'; } // Korean
	
	echo ' in '.$wpLang;
}

/** 
 * This is used to identify if the 
 * $source exists before copying or 
 * removing it.
 */
function folderExists($source, $destination = '', $copy = '', $remove = ''){
	global $debugExtract;

	if(file_exists($source)){
		if($copy == 'yes'){
			copydir($source, $destination);
		} // if copy.
		if($remove == 'yes'){
			rrmdir($source);
		} // if remove.
	} // end if file exists.
}

/** 
 * Copies all of WordPress directories and 
 * files to the new destination.
 */
function copydir($source, $destination){
	global $debugExtract;

	$dir_handle = @opendir($source) or die("<li style=\"color:red;\">Unable to open folder directory. Make sure that you have setup your folder permissions to '777' temporarily.</li>\n");
	while($file = readdir($dir_handle)){
		if(is_dir($source."/".$file) && $file != "." && $file != ".."){
			if(is_dir($destination."/".$file)){
				if(file_exists($destination."/".$file)){
					$oldumask = umask(0);
					mkdir($destination."/".$file, 0777); /* Create folder if it does not exist. */
					chmod($destination."/".$file, 0777); /* Set folder to write. */
					umask($oldumask);
				} // end if no directory exists.
				if(rename($source."/".$file, $destination."/".$file)){
					if($debugExtract == 'yes'){ echo "<p><em>$destination</em>/<span style=\"color:green;\">$file</span></p>"; }
				}
				else{
					echo "<p><em>$destination</em>/<span style=\"color:red;\">$file</span></p>";
				}
			} // if file is a directory.
			if(rename($source."/".$file, $file)){
				if($debugExtract == 'yes'){ echo "<p><em>$destination</em>/<span style=\"color:green;\">$file</span></p>"; }
			}
			else{
				echo "<p><em>$destination</em>/<span style=\"color:red;\">$file</span></p>";
			}
		}
		else if($file != "." && $file != ".." && !is_dir($source."/".$file)){
			if(rename($source."/".$file, $file)){
				if($debugExtract == 'yes'){ echo "<p><em>$destination</em>/<span style=\"color:green;\">$file</span></p>"; }
			}
			else{
				echo "<p><em>$destination</em>/<span style=\"color:red;\">$file</span></p>";
			}
		}
	}
	closedir($dir_handle);
}

/* Removes all WordPress temporary extracted files. */
function rrmdir($dir){
	$dir_handle = opendir($dir) or die("Unable to open directory.");
	while($file = readdir($dir_handle)){
		if(is_dir($dir.'/'.$file) && $file != "." && $file != ".."){
			if(is_dir($dir.'/'.$file)){
				if(file_exists($dir."/".$file)){
					unlink($dir.'/'.$file);
				}
			}
			else{
				unlink($file);
			}
		}
	}
	closedir($dir_handle);
	rmdir($dir);
}

/**
 * Get a web file from a URL. Return an array 
 * containing the HTTP server response header 
 * fields and content.
 */
function get_web_page($url){
	$options = array(
		CURLOPT_RETURNTRANSFER => true,     // return web page
		CURLOPT_HEADER         => false,    // don't return headers
		CURLOPT_FOLLOWLOCATION => true,     // follow redirects
		CURLOPT_ENCODING       => "",       // handle compressed
		CURLOPT_USERAGENT      => wpsetup_title, // who am i
		CURLOPT_AUTOREFERER    => true,     // set referer on redirect
		CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
		CURLOPT_TIMEOUT        => 120,      // timeout on response
		CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
	);

	$ch      = curl_init($url);
	curl_setopt_array($ch, $options);
	$content = curl_exec($ch);
	$err     = curl_errno($ch);
	$errmsg  = curl_error($ch);
	$header  = curl_getinfo($ch);
	curl_close($ch);

	$header['errno']   = $err;
	$header['errmsg']  = $errmsg;
	$header['content'] = $content;
	return $header;
}

/**
 * This is the function that does the magic 
 * and grabs external data from your client's 
 * websites.
 */
function get_data($url){
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$data = curl_exec($ch);
	$httpheaders = curl_getinfo($ch,CURLINFO_HTTP_CODE);
	curl_close($ch);
	if($httpheaders == "404"){ return "noplugin"; }else{ return $data; }
}
?>