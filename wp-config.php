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
define('DB_NAME', 'baza23113_41540');

/** MySQL database username */
define('DB_USER', 'admin23113_41540');

/** MySQL database password */
define('DB_PASSWORD', '6Le41b!gIr');

/** MySQL hostname */
define('DB_HOST', '23113.m.tld.pl');

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
define('AUTH_KEY',         '}as(C^{2`&M;DCpnQ fNu;j}-I[ fjrFi-`(G^`yBMDW{Ag8= 639yK|%m13H-uX');
define('SECURE_AUTH_KEY',  'HnNYB_C)r5_zK60HS?(5Hect%f7{@bPq,}}Bc&1em7(WqL#gucw.&gdsxe6kE,f9');
define('LOGGED_IN_KEY',    '#LndKtIX}LcO3bKqssU} ~w#1]&*yml`M;bJm3`K.LpbE)o1=*irIP#n$zXG!Gqb');
define('NONCE_KEY',        '/>EhsG;9?j3ju[PS$4m=GJLwoxJ^l=r~=<oNX.^1Y3Hs{XqS:cO}5R2:p0XL*zN_');
define('AUTH_SALT',        'Ci>%,M(]<&KP9]+o9TaD0S,dKfE-Q6cFhDc`h,Q|nrO;GcUxMN.1$/8#c[_u(U]X');
define('SECURE_AUTH_SALT', 'aN4y!>,c=~WI|/){O=x.TE~?Ip5^EyX[I`~6NUW.=v%)JvF]ZV$06W|`dq:z eCe');
define('LOGGED_IN_SALT',   'A5=/ Y<R]kC<k2&3u$LS}u;Rsn&i(=JcHIZ;gTUD6x1R[26Dj:T#ng{zIrY6W#(@');
define('NONCE_SALT',       ',yTYXepH?}PBvyw|=U#]Ad043ZO2Jzm9xPMebH50{]v0y:ZQ4`KqvO^L3^$n) [1');

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
