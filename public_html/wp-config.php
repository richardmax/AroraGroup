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
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'aroragro_main');

/** MySQL database username */
define('DB_USER', 'aroragro_main');

/** MySQL database password */
define('DB_PASSWORD', 'xam33Bod');

/** MySQL hostname */
define('DB_HOST', '10.168.1.47');

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
define('AUTH_KEY',         'c*D].Q&JorVNB*%.cgFI?Ue$6OeMVEy-cI^BPwQ^FmrY`)/v#G=m7_hU_7:9;|UB');
define('SECURE_AUTH_KEY',  'TL6N#[D+ktX@xBC2F>0t0Y=QAW8g[TzJgP(TX|RkW&lJqpK2Z-wvR^}PM)[7t>sy');
define('LOGGED_IN_KEY',    'vy{QY@9jP(7v3NlJ+MdiX]z``M82=s//-lot)&xrnsL#XA5X@ AV5{G]V%O>tR#_');
define('NONCE_KEY',        '[y:ydg__278>[*8_<94+BOiqOT11F$K*A~B&dekt$KZP?z(HC^+TB$_AV8`,AA`g');
define('AUTH_SALT',        'Aa29<}2qdD9bzr]p*lL+9;{JKLi7OBzOw4?ap9Pf{H*x6W$>9:ZOiN]jp=CP.#lo');
define('SECURE_AUTH_SALT', ' 7E/qQ#~TB*%*6>iJEKZE=`/)9UdEHG`vuf|i3Q^LI#?>o-Jed2ndrLN0k~da3?z');
define('LOGGED_IN_SALT',   ']=ap5dd|~P;>_Ml|mJMPok&0~%Pm0},uVumJQ!@rIrH*rLb+VR)g~m{{gixqWdyY');
define('NONCE_SALT',       '^#5z+_1^E#wFDamwZMx?8R$1jSXU7pT+DEy6[ DO9&Fvw7 ;43P#NyNa43Y8 ~We');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* Multisite */
define( 'WP_ALLOW_MULTISITE', true );
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', true);
define('DOMAIN_CURRENT_SITE', 'digital.ltd.uk');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

/* domain mapping */
define('SUNRISE', 'on');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
