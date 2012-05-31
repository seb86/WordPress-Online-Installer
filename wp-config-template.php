<?php
/**
 * The base configurations of the WordPress.
 * 
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 * 
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 * 
 * @package WordPress
 * 
 * This wp-config.php file was created using the WordPress Online Installer.
 * http://www.wpsetup.org
 * 
 * Custom Setup created by Seb's Studio
 * http://www.sebs-studio.com
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'database_name_here');

/** MySQL database username */
define('DB_USER', 'username_here');

/** MySQL database password */
define('DB_PASSWORD', 'password_here');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'AUTH_VALUE');
define('SECURE_AUTH_KEY',  'AUTH_SECURE_VALUE');
define('LOGGED_IN_KEY',    'LOGGED_IN_VALUE');
define('NONCE_KEY',        'NONCE_VALUE');
define('AUTH_SALT',        'AUTH_PEPPER');
define('SECURE_AUTH_SALT', 'AUTH_SECURE_PEPPER');
define('LOGGED_IN_SALT',   'LOGGED_IN_PEPPER');
define('NONCE_SALT',       'NONCE_PEPPER');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'TABLE_PREFIX';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', debug_mode_value);

/** 
 * Change the 1 to 7 or however many days you want.
 * If you don’t like the trash feature at all, then you can always set the 
 * number to 0, and get rid of it entirely.
 */
define('EMPTY_TRASH_DAYS', empty_trash_value);

/** 
 * This sets how long you want WordPress to wait until autosaving your posts.
 * It also sets if you want to keep any revisions of your posts.
 * For revisions replace false with the amount of revisions you wish to have 
 * per post with a number i.e. 5.
 */
define('AUTOSAVE_INTERVAL', autosave_time_value); // 60 seconds (1 Minute) x5 = 300 seconds (5 Minutes).
define('WP_POST_REVISIONS', post_revisions_value); // False disables post revisions. 

/** 
 * This will override the default memory size.
 * By default it set as 64M which should be sufficient for most blogs.
 * You may increase it further if you wish.
 */
define('WP_MEMORY_LIMIT', '64M');

/** 
 * Auto Database Optimization. If set to true, you can see the settings 
 * on this page: http://www.yoursite.com/wp-admin/maint/repair.php
 */
define('WP_ALLOW_REPAIR', repair_value);

/** 
 * Enable Multi-Site Network. To enable the multi-site network 
 * functionality, change the value to true. Once set as true, 
 * there will be a new page in your wp-admin called “Network” 
 * located in Tools » Network.
 */
define('WP_ALLOW_MULTISITE', multisite_value);

// Secure SSL Login Page.
define('FORCE_SSL_ADMIN', ssl_admin_value);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

?>