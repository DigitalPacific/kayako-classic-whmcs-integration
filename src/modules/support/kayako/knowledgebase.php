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

require_once 'constants.php';

//Initialize the client
kyConfig::set(new kyConfig(API_URL, API_KEY, SECRET_KEY));

$_categoryController   = '/Knowledgebase/Category';
$_articalController    = '/Knowledgebase/Article';
$_commentController    = '/Knowledgebase/Comment';
$_attachmentController = '/Knowledgebase/Attachment';
$_restClient           = kyConfig::get()->getRESTClient();

if (isset($_GET['aid'])) {

	$_searchParameters    = ['Get', $_GET['articleid'], $_GET['aid']];
	$_attachmentContainer = $_restClient->get($_attachmentController, $_searchParameters);
	$_attachment          = $_attachmentContainer['kbattachment'][0];

	Download($_attachment['filename'], base64_decode($_attachment['contents']));
} else if (isset($_GET['articleid'])) {

	if ($_GET['action'] == 'savecomment') {

		$_itemIDKey          = 'knowledgebasearticleid';
		$_itemID             = $_GET['articleid'];
		$_rootCommentElement = 'kbarticlecomment';
		include 'savecomment.php';
	}
	include 'knowledgebasearticle.php';
} else {
	include 'knowledgebasecategories.php';
}

$smarty->assign('_settings', $_settings);

$smarty->assign('_baseURL', WHMCS_URL);
$smarty->assign('_templateURL', getcwd() . '/templates/kayako');
$smarty->assign('_jscssURL', 'templates/kayako');