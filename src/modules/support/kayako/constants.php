<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/whmcs-integration
 */

//IMAGE URL
define('IMAGE_URL', WHMCS_URL . 'templates/kayako/images/');

//Comment constants
define('STATUS_PENDING', 1);
define('STATUS_APPROVED', 2);
define('STATUS_SPAM', 2);

define('CREATOR_STAFF', 1);
define('CREATOR_USER', 2);

//News Constants
define('TYPE_GLOBAL', 1);
define('TYPE_PUBLIC', 2);
define('TYPE_PRIVATE', 3);

define('STATUS_DRAFT', 1);
define('STATUS_PUBLISHED', 2);

//Mode of record update
define('MODE_EDIT', 'edit');
define('MODE_INSERT', 'insert');

define('SWIFT_CRLF', '\n')
?>