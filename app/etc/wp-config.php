<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wp');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '9AflDtkT3-mYLf9y^ZgnBLsR^gnZ $9d&=00T:Xp&$wwe(n:V:.Z*8nz+HL$({|u');
define('SECURE_AUTH_KEY',  '#.G GOGFzZeKq-L6+>SXA*dcT0J=S.~-eDsfER;tiI=]ZXcEYE`X|ED!h(a%jLC/');
define('LOGGED_IN_KEY',    '$aB}ybVE Jn-}_]r?--gw?~CKVa2rx38Ngh[kAEznl)> pHR*h!3|M4e:nXT 1q<');
define('NONCE_KEY',        '7~l{s?pn$h3k}D.w`:VL[jcG;`g8Ayg/?$m^>+S}ekcsm,J4>_Eojv&!P!!!g5Sx');
define('AUTH_SALT',        '!,EZ2Rn3W+e(I b]J;7)*bU6a2hj)On8C5# 4fTdU[on=S*9OnH7H?vqK_ib2MIl');
define('SECURE_AUTH_SALT', ']lEr>r@/uz|||uf{!ZQq1sYBi3YlDpq&{s$>B!#=k},aSg>zz,t]m(j}QmP>JQh1');
define('LOGGED_IN_SALT',   ': |P{P*S0 `[z [5IGp4[fgV8IsY8~+$z$X6i7Gl8U*/{nh[XQrIq;<~o(*ZOXv.');
define('NONCE_SALT',       'XFKb_-|5Y]:;E+^O+d4wpUWhC[!e~MebbJRo?(uihndcOktW/W*%K5OEB_Z0*OnQ');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
