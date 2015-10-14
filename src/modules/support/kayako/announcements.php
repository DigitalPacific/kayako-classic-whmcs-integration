<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/whmcs-integration
 */

require_once __DIR__ . '/config.php';
require_once 'API/kyConfig.php';
require_once 'API/kyRESTClientInterface.php';
require_once 'API/kyRESTClient.php';
require_once 'API/kyHelpers.php';

//Include common functions
require_once 'functions.php';

//Include constants file
require_once 'constants.php';

kyConfig::set(new kyConfig(API_URL, API_KEY, SECRET_KEY));
$_restClient = kyConfig::get()->getRESTClient();

$_newsController    = '/News/NewsItem';
$_commentController = '/News/Comment';

if (isset($_GET['itemid'])) {
	if ($_GET['action'] == 'savecomment') {
		$_itemIDKey          = 'newsitemid';
		$_itemID             = $_GET['itemid'];
		$_rootCommentElement = 'newsitemcomment';
		include 'savecomment.php';
	}

	include 'announcementitem.php';
} else {
	include 'announcementlist.php';
}

$smarty->assign('_baseURL', WHMCS_URL);
$smarty->assign('_templateURL', getcwd() . '/templates/kayako');
$smarty->assign('_jscssURL', 'templates/kayako');