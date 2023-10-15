<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)
define('HTTP_SERVER', 'http://localhost:7080'); // eg, http://localhost - should not be empty for productive servers
define('HTTPS_SERVER', 'http://localhost:7080'); // eg, https://localhost - should not be empty for productive servers
define('ENABLE_SSL', false); // secure webserver for checkout procedure?
define('HTTP_COOKIE_DOMAIN', '127.0.0.1');
define('HTTPS_COOKIE_DOMAIN', '');
define('HTTP_COOKIE_PATH', '');
define('HTTPS_COOKIE_PATH', '');
define('DIR_WS_HTTP_CATALOG', '/safensound/');
define('DIR_WS_HTTPS_CATALOG', '/safensound/');
define('DIR_WS_IMAGES', 'catalog/images/');
define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
define('DIR_WS_INCLUDES', 'includes/');
define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');

define('DIR_WS_DOWNLOAD_PUBLIC', 'pub/');
define('DIR_FS_CATALOG', 'C:/wamp/www/safensound/');
define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');

// define our database connection
define('DB_SERVER', 'localhost:3306'); // eg, localhost - should not be empty for productive servers
define('DB_SERVER_USERNAME', 'web13-sns');
define('DB_SERVER_PASSWORD', 'admin');
define('DB_DATABASE', 'web13-sns');
define('USE_PCONNECT', 'false'); // use persistent connections?
define('STORE_SESSIONS', ''); // leave empty '' for default handler or set to 'mysql'

/*
define('HTTP_SERVER', 'http://www.safensound.co.uk'); // eg, http://localhost - should not be empty for productive servers
define('HTTPS_SERVER', ''); // eg, https://localhost - should not be empty for productive servers
define('ENABLE_SSL', false); // secure webserver for checkout procedure?
define('HTTP_COOKIE_DOMAIN', 'www.safensound.co.uk');
define('HTTPS_COOKIE_DOMAIN', '');
define('HTTP_COOKIE_PATH', '');
define('HTTPS_COOKIE_PATH', '');
define('DIR_WS_HTTP_CATALOG', '/');
define('DIR_WS_HTTPS_CATALOG', '/');
define('DIR_WS_IMAGES', 'catalog/images/');
define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
define('DIR_WS_INCLUDES', 'includes/');
define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');

define('DIR_WS_DOWNLOAD_PUBLIC', 'pub/');
define('DIR_FS_CATALOG', '/home/sites/safensound.co.uk/public_html/');
define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');

// define our database connection
define('DB_SERVER', 'localhost'); // eg, localhost - should not be empty for productive servers
define('DB_SERVER_USERNAME', 'web13-sns');
define('DB_SERVER_PASSWORD', 'N1cole');
define('DB_DATABASE', 'web13-sns');
define('USE_PCONNECT', 'false'); // use persistent connections?
define('STORE_SESSIONS', ''); // leave empty '' for default handler or set to 'mysql'
*/

?>
