<?php
define('WP_CACHE', true); // Added by WP Rocket

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

$envPath = __DIR__ . '/.env'; // путь к вашему файлу

if (file_exists($envPath)) {
  $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($lines as $line) {
    // Пропускаем комментарии
    if (strpos(trim($line), '#') === 0)
      continue;

    // Разделяем по первому знаку "="
    list($name, $value) = explode('=', $line, 2);

    $name = trim($name);
    $value = trim($value);

    // Убираем лишние кавычки, если они есть
    $value = trim($value, '"\'');

    // Ключевой момент: устанавливаем переменную в окружение
    putenv("{$name}={$value}");
    $_ENV[$name] = $value;
    $_SERVER[$name] = $value;
  }
}

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', getenv('DATABASE_NAME'));

/** Database username */
define('DB_USER', getenv('DATABASE_USER'));

/** Database password */
define('DB_PASSWORD', getenv('DATABASE_PASSWORD'));

/** Database hostname */
define('DB_HOST', getenv('DATABASE_HOST'));

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');


define('WP_HOME', 'http://localhost:8080');
define('WP_SITEURL', 'http://localhost:8080');

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'ZWCUeH qFs2z^wuHOAPYOAI x`3Hd# s@mC]?bMzc-Hw9)RHb4~f*f-?{w*JAW[O');
define('SECURE_AUTH_KEY', '(Mr&}EwE7aL~bq:[D0n#D,tGEan{uFOYVB@$lBIv*@+<@UcZ%wU>d,]Dy)EWtzQ}');
define('LOGGED_IN_KEY', '/U4B{Pk|?#,dHq{E6a8WU8ws[G#XeZ_-}+/7AhJh>(F{NL3ww^qfUW7Z.j#tffi)');
define('NONCE_KEY', '&A;{mtq8BX2p19hZwnq)| uMVAEzJdn`sm;+LM>WPce 1E.8xSe[k*2n]o:nc?jR');
define('AUTH_SALT', 'v?48EA(,{ZSu|Wp/Qv!rX>_CA}yF?O}_G]l`4v#!SW77a@:!Bk][*SSs|5J+I<eI');
define('SECURE_AUTH_SALT', 'Gb$EWe ?^EOG%:goSdr!ySy6SP:`wF3J1A_[oQb8GB_Gl!1d~V- ;HRWl;WZ4BN=');
define('LOGGED_IN_SALT', 'O1{q>@fl,jX]aByBk7ur>b?~PS09Pk_QR6{qVv- #m16F^PaoavSD?aO1ZN0T`W]');
define('NONCE_SALT', '5FyXi{`dH:!86&+Ik1zmcD5lk?oG3^{H6WZ-YB=PSZR($j:4ys#&V~,ay37aQ0&5');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define('WP_DEBUG', false);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
  define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';