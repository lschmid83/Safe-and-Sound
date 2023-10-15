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
define('HTTP_CATALOG_SERVER', 'http://localhost:7080');
define('HTTPS_CATALOG_SERVER', 'http://localhost:7080');
define('ENABLE_SSL_CATALOG', 'false'); // secure webserver for catalog module
define('DIR_FS_DOCUMENT_ROOT', 'C:/wamp/www/safensound/'); // where the pages are located on the server
define('DIR_WS_ADMIN', '/safensound/admin/'); // absolute path required
define('DIR_FS_ADMIN', 'C:/wamp/www/safensound/admin/'); // absolute pate required
define('DIR_WS_CATALOG', '/safensound/'); // absolute path required
define('DIR_FS_CATALOG', 'C:/wamp/www/safensound/'); // absolute path required
define('DIR_WS_IMAGES', 'images/');
define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
define('DIR_WS_CATALOG_IMAGES', DIR_WS_CATALOG . 'catalog/images/');
define('DIR_WS_INCLUDES', 'includes/');
define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');
define('DIR_WS_CATALOG_LANGUAGES', DIR_WS_CATALOG . 'includes/languages/');
define('DIR_FS_CATALOG_LANGUAGES', DIR_FS_CATALOG . 'includes/languages/');
define('DIR_FS_CATALOG_IMAGES', DIR_FS_CATALOG . '/catalog/images/');
define('DIR_FS_CATALOG_MODULES', DIR_FS_CATALOG . 'includes/modules/');
define('DIR_FS_BACKUP', DIR_FS_ADMIN . 'backups/');

// define our database connection
define('DB_SERVER', 'localhost:3306'); // eg, localhost - should not be empty for productive servers
define('DB_SERVER_USERNAME', 'web13-sns');
define('DB_SERVER_PASSWORD', 'admin');
define('DB_DATABASE', 'web13-sns');
define('USE_PCONNECT', 'false'); // use persisstent connections?
define('STORE_SESSIONS', ''); // leave empty '' for default handler or set to 'mysql'

/*
define('HTTP_SERVER', 'http://www.safensound.co.uk'); // eg, http://localhost - should not be empty for productive servers
define('HTTP_CATALOG_SERVER', 'http://www.safensound.co.uk');
define('HTTPS_CATALOG_SERVER', '');
define('ENABLE_SSL_CATALOG', 'false'); // secure webserver for catalog module
define('DIR_FS_DOCUMENT_ROOT', '/home/sites/safensound.co.uk/public_html/'); // where the pages are located on the server
define('DIR_WS_ADMIN', '/admin/'); // absolute path required
define('DIR_FS_ADMIN', '/home/sites/safensound.co.uk/public_html/admin/'); // absolute pate required
define('DIR_WS_CATALOG', '/'); // absolute path required
define('DIR_FS_CATALOG', '/home/sites/safensound.co.uk/public_html/'); // absolute path required
define('DIR_WS_IMAGES', 'images/');
define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
define('DIR_WS_CATALOG_IMAGES', DIR_WS_CATALOG . 'catalog/images/');
define('DIR_WS_INCLUDES', 'includes/');
define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');
define('DIR_WS_CATALOG_LANGUAGES', DIR_WS_CATALOG . 'includes/languages/');
define('DIR_FS_CATALOG_LANGUAGES', DIR_FS_CATALOG . 'includes/languages/');
define('DIR_FS_CATALOG_IMAGES', DIR_FS_CATALOG . '/catalog/images/');
define('DIR_FS_CATALOG_MODULES', DIR_FS_CATALOG . 'includes/modules/');
define('DIR_FS_BACKUP', DIR_FS_ADMIN . 'backups/');

// define our database connection
define('DB_SERVER', 'localhost'); // eg, localhost - should not be empty for productive servers
define('DB_SERVER_USERNAME', 'web13-sns');
define('DB_SERVER_PASSWORD', 'N1cole');
define('DB_DATABASE', 'web13-sns');
define('USE_PCONNECT', 'false'); // use persisstent connections?
define('STORE_SESSIONS', ''); // leave empty '' for default handler or set to 'mysql'
*/

?>