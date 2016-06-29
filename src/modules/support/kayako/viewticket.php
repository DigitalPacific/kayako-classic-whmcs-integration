<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/whmcs-integration
 */

//Include config file
require_once __DIR__ . '/config.php';

//Include all necessary classes and helper methods
require_once 'API/kyIncludes.php';

require_once 'functions.php';

//Include constants file
require_once 'constants.php';

$clientsdetails = getclientsdetails($_userid);
if (!isset($clientsdetails['email'])) {
	header('Location: ' . WHMCS_URL . 'clientarea.php');
}

//Initialize the client
kyConfig::set(new kyConfig(API_URL, API_KEY, SECRET_KEY));

//Process ticket status
$_ticketStatusObjectContainer = kyTicketStatus::getAll()->filterByType(kyDepartment::TYPE_PUBLIC);

$_ticketStatusContainer = [];
foreach ($_ticketStatusObjectContainer as $_ticketStatusObject) {
	$_ticketStatus['ticketstatusid'] = $_ticketStatusObject->getId();
	$_ticketStatus['title']          = $_ticketStatusObject->getTitle();
	$_ticketStatus['displayorder']   = $_ticketStatusObject->getId();
	$_ticketStatus['markasresolved'] = $_ticketStatusObject->getMarkAsResolved();
	$_ticketStatus['statustype']     = $_ticketStatusObject->getType();
	$_ticketStatus['displaycount']   = $_ticketStatusObject->getDisplayCount();
	$_ticketStatus['statuscolor']    = $_ticketStatusObject->getStatusColor();
	$_ticketStatus['statusbgcolor']  = $_ticketStatusObject->getStatusBackgroundColor();
	$_ticketStatus['displayicon']    = $_ticketStatusObject->getDisplayIcon();
	$_ticketStatus['triggersurvey']  = $_ticketStatusObject->getTriggerSurvey();

	$_ticketStatusContainer[$_ticketStatusObject->getId()] = $_ticketStatus;
}

//Process ticket priorities
$_ticketPriorityObjectContainer = kyTicketPriority::getAll()->filterByType(kyDepartment::TYPE_PUBLIC);

$_ticketPriorityContainer = [];
foreach ($_ticketPriorityObjectContainer as $_ticketPriorityObject) {
	$_ticketPriority['priorityid']           = $_ticketPriorityObject->getId();
	$_ticketPriority['title']                = $_ticketPriorityObject->getTitle();
	$_ticketPriority['displayorder']         = $_ticketPriorityObject->getDisplayOrder();
	$_ticketPriority['type']                 = $_ticketPriorityObject->getType();
	$_ticketPriority['frcolorcode']          = $_ticketPriorityObject->getForegroundColor();
	$_ticketPriority['bgcolorcode']          = $_ticketPriorityObject->getBackgroundColor();
	$_ticketPriority['displayicon']          = $_ticketPriorityObject->getDisplayIcon();
	$_ticketPriority['uservisibilitycustom'] = $_ticketPriorityObject->getUserVisibilityCustom();

	$_ticketPriorityContainer[$_ticketPriorityObject->getId()] = $_ticketPriority;
}

if (isset($_GET['aid'])) {

	$_ticketAttachment = kyTicketAttachment::get($_GET['tid'], $_GET['aid']);

	Download($_ticketAttachment->getFileName(), $_ticketAttachment->getContents());
} else if ($_GET['action'] == 'reply') {

	$_user = kyUser::search($clientsdetails['email']);
	$_user = $_user[0];

	$_ticketObject = kyTicket::get($_GET['ticketid']);

	$_ticketPost = kyTicketPost::createNew($_ticketObject, $_user, $_POST['replycontents'])
					->create();

	//Save ticket post attachments
	foreach ($_FILES['ticketattachments']['tmp_name'] as $_key => $_ticketAttachment) {
		kyTicketAttachment::createNewFromFile($_ticketPost, $_ticketAttachment, $_FILES['ticketattachments']['name'][$_key])
			->create();
	}

	header('Location: ' . WHMCS_URL . 'viewticket.php?ticketid=' . $_GET['ticketid']);

} else if ($_REQUEST['action'] == 'update') {

	require_once 'updateticket.php';
	header('Location: ' . WHMCS_URL . 'viewticket.php?ticketid=' . $_GET['ticketid']);

} else {
	require_once 'ticketview.php';
}

$smarty->assign('_ticketPriorityContainer', $_ticketPriorityContainer);
$smarty->assign('_ticketStatusContainer', $_ticketStatusContainer);
$smarty->assign('_dateFormat', $_settings['dateformat']);
$smarty->assign('_submitURL', WHMCS_URL . 'viewticket.php');
$smarty->assign('_imageURL', IMAGE_URL);
$smarty->assign('_templateURL', getcwd() . '/templates/kayako');
$smarty->assign('_canChangeTicketProperties', true);
$smarty->assign('_expandPostReply', false);
$smarty->assign('_jscssURL', 'templates/kayako');

$templatefile = "viewticket";
