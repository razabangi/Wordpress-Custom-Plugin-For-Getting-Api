<?php
/**
 * Check all the required files exist
 */
$current_path = __DIR__ . DIRECTORY_SEPARATOR . '../';

if (!file_exists($current_path . 'vendor/autoload.php')) {
    die('Please install the composer dependencies for this to work!');
}

if (!file_exists($current_path . '.env')) {
    die('Please create the .env file containing the environment variables');
}

/**
 * Load Environment Settings from .env file into $_ENV superglobal
 */
require_once $current_path . 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::create($current_path);
$dotenv->load();
unset($dotenv);

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', getenv('DB_NAME'));

/** MySQL database username */
define('DB_USER', getenv('DB_USER'));

/** MySQL database password */
define('DB_PASSWORD', getenv('DB_PASS'));

/** MySQL hostname */
define('DB_HOST', getenv('DB_HOST'));

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', getenv('DB_CHARSET'));

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', getenv('DB_COLLATE'));

define('FS_METHOD', getenv('direct'));


/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'J9TRN]e{lP<?>E7T_7g![ox`L+Hxd,:]YIfu.d=ZJxF4g~3sy;VsDYQ+!E7D$[RU');
define('SECURE_AUTH_KEY',  'E]X?hu<Oy|8_LuS|6v)- &#AP+TCv<o,Ej=zW{b$V^&;N>,(RJ+V<Lr|UK-H@1+%');
define('LOGGED_IN_KEY',    'W1KM=ANh1cxr}9b|@nXh,sA>du/b;t_IwJHXnm2gTbT*d*ha#Ba1:;&..H9?lU>L');
define('NONCE_KEY',        '{:8;#M:1QPm6N-@=Izc&LYO Q@#qEVbPyKfKPB7XBo_g/2vPRO?8%E<Dy6/>G&9l');
define('AUTH_SALT',        'L{cO&bIq;|&@`g*Cn-uB^L}WX:IKR%yn^#<|ja87|}SQ@rvI5]JuSZgMe*;>zy)u');
define('SECURE_AUTH_SALT', 'Riy?}K`@3nF2q]V!WXfTStoI0*dyqA`>hDyH> ]ZeLHRrA,LxYT(jQE@.9t7H1P*');
define('LOGGED_IN_SALT',   '#58j-=06.-^,d5iL%(GPk/!b$e#cQl[>#6HQx%lrgeM:YU7]f/rhwIf+RtMc=W*+');
define('NONCE_SALT',       '`XRdQjZ_}tf+3KDqRWeaVS5]^ZT}T_ii0u@u<yKEi#aG4nY*YYg4Ep*}RKjJW,|L');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = getenv('DB_PREFIX');

/**
 * WordPress Address (URL)
 */
define('WP_SITEURL', getenv('WP_SITEURL'));

/**
 * WordPress Blog Address (URL)
 */
define('WP_HOME', getenv('WP_HOME'));

/**
 * Enable/Disable Post Revisions
 */
define('WP_POST_REVISIONS', getenv('WP_POST_REVISIONS'));

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', getenv('WP_DEBUG'));
define('WP_DEBUG_LOG', getenv('WP_DEBUG_LOG'));
define('WP_DEBUG_DISPLAY', getenv('WP_DEBUG_DISPLAY'));

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
