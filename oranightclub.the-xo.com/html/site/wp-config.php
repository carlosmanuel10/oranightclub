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
define('DB_NAME', 'ora_wp');

/** MySQL database username */
define('DB_USER', 'ora-usr');

/** MySQL database password */
define('DB_PASSWORD', 'Quay58m6HExbm6Ke');

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
define('AUTH_KEY',         'ccpx-@f!JG&|&6Kghe29LyJzZV#+hwWrW8Q-/2Q9<4s*j1MokuRAhsbkgw8cfBvt');
define('SECURE_AUTH_KEY',  '*jJ+E2}YBrVi@5#&8lPH64N=fM.]~$A0.$|6Z{f!t)K&ytwz=UX/-g=C`5tI{#]8');
define('LOGGED_IN_KEY',    '{,(D7P4tn|--{ch$%+K`5M$gsq&J)Z}qF=eKZ0yDG%yt#`g#7UUr?%HOHc#YrA:c');
define('NONCE_KEY',        'H<*I$45wucta9lgxgDk?Krj)KAt2Hv_O~K:=-aQh]Ta|`5!o(*G#eQJ~(f Ue/dz');
define('AUTH_SALT',        ')!=[25i[DIEu[.0f8>co/mZe5 3ji~ -jC/]i|##m?>;5+*h0>|kvpv}kqjCY0:Z');
define('SECURE_AUTH_SALT', 'nD2LMKewb:_q.:Kc)hu}+~aa4~xFyF=I-9Z{j 0Kx7YTkHq!-^X7xCJ^7z~n$Jj?');
define('LOGGED_IN_SALT',   'L_^+k0oL*b2)WBU#<2%1<] AO4*@!);#cL.?9-+Tq.nQN?F]iWPLzM5@t2fBs.6$');
define('NONCE_SALT',       'iq+YH%RwJ2?19L9Lr#(CyhW](g$w+kXN??wM{TP:F(x-_Xj?]zLUhx6d?UdN>VuL');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'ora_';

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
