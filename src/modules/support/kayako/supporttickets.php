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

//Include constants file
require_once 'constants.php';

//Initialize the client
kyConfig::set(new kyConfig(API_URL, API_KEY, SECRET_KEY));
$clientsdetails = getclientsdetails($_userid);

$_ticketDepartmentContainer = kyDepartment::getAll()->filterByModule(kyDepartment::MODULE_TICKETS)->filterByType(kyDepartment::TYPE_PUBLIC);

$_ticketStatusObjectContainer = kyTicketStatus::getAll()->filterByType(kyTicketStatus::TYPE_PUBLIC);

$_ticketStatusContainer = [];
foreach ($_ticketStatusObjectContainer as $_ticketStatusObject) {

	$_ticketStatusID                 = $_ticketStatusObject->getId();
	$_ticketStatus['ticketstatusid'] = $_ticketStatusID;
	$_ticketStatus['title']          = $_ticketStatusObject->getTitle();
	$_ticketStatus['markasresolved'] = $_ticketStatusObject->getMarkAsResolved();

	$_ticketStatusContainer[$_ticketStatusID] = $_ticketStatus;
}

//Show resolved ticket or not
if (!isset($_GET['showResolved'])) {
	$_showResolved = 'false';
} else {
	$_showResolved = $_GET['showResolved'];
}

if (!isset($_GET['order']) || $_GET['order'] == 'ASC') {

	$_sortOrder = 'ASC';
	$_order     = true;
} else {
	$_sortOrder = 'DESC';
	$_order     = false;
}

if (!isset($_GET['sortby'])) {

	$_sortBy    = 'LastActivity';
	$_sortOrder = 'DESC';
	$_order     = false;
} else {
	$_sortBy = $_GET['sortby'];
}

$_offset = ($_GET['page'] > 1 ? ($_GET['page'] - 1) * $_settings['recordsperpage']: 0 );

if ($_order) {
	$_sortOrderFlip = 'DESC';
} else {
	$_sortOrderFlip = 'ASC';
}

$_totalTicketCount = kyTicket::getTicketCount($_ticketDepartmentContainer, $_ticketStatusObjectContainer, [], [], $clientsdetails['email']);

$_ticketObjectContainer = kyTicket::getAll($_ticketDepartmentContainer, $_ticketStatusObjectContainer, [], [], $clientsdetails['email'], $_settings['recordsperpage'], $_offset, $_sortBy, $_sortOrder);

$_ticketContainer     = [];
$_resolvedTicketCount = 0;

foreach ($_ticketObjectContainer as $_ticketObject) {

	$_ticket                    = [];
	$_ticketID                  = $_ticketObject->getId();
	$_ticketStatus              = $_ticketObject->getStatusId();
	$_ticket['ticketid']        = $_ticketID;
	$_ticket['ticketstatusid']  = $_ticketStatus;
	$_ticket['displayticketid'] = $_ticketObject->getDisplayId();
	$_ticket['departmentid']    = $_ticketObject->getDepartmentId();
	$_ticket['department']      = $_ticketObject->getDepartment()->getTitle();
	$_ticket['status']          = $_ticketObject->getStatus()->getTitle();
	$_ticket['statusbgcolor']   = $_ticketObject->getStatus()->getStatusBackgroundColor();
	$_ticket['priorityid']      = $_ticketObject->getPriorityId();
	$_ticket['priority']        = $_ticketObject->getPriority()->getTitle();
	$_ticket['prioritybgcolor'] = $_ticketObject->getPriority()->getBackgroundColor();
	$_ticket['userid']          = $_ticketObject->getUserId();
	$_ticket['tickettypeid']    = $_ticketObject->getTypeId();
	$_ticket['type']            = $_ticketObject->getType()->getTitle();
	$_ticket['userid']          = $_ticketObject->getUserId();
	$_ticket['fullname']        = $_ticketObject->getFullName();
	$_ticket['email']           = $_ticketObject->getEmail();
	$_ticket['ownerstaffid']    = $_ticketObject->getOwnerStaffId();
	$_ticket['lastreplier']     = $_ticketObject->getLastReplier();
	$_ticket['subject']         = $_ticketObject->getSubject();
	$_ticket['dateline']        = $_ticketObject->getCreationTime();
	$_ticket['lastactivity']    = $_ticketObject->getLastActivity();

	/*if (isset($_ticketStatusContainer[$_ticketStatus]) && $_ticketStatusContainer[$_ticketStatus]['markasresolved'] == '1') {
		$_ticket['isresolved'] = true;
		$_resolvedTicketCount++;
	}*/

	$_ticketContainer[$_ticketID] = $_ticket;
}

$smarty->assign('_ticketContainer', $_ticketContainer);
//$smarty->assign('_resolvedTicketCount', $_resolvedTicketCount);
$smarty->assign('_submitTicketURL', WHMCS_URL . 'submitticket.php');
$smarty->assign('_listTicketURL', WHMCS_URL . 'supporttickets.php');
$smarty->assign('_imageURL', WHMCS_URL . 'templates/kayako/images');
$smarty->assign('_viewTicketURL', WHMCS_URL . 'viewticket.php');
$smarty->assign('_sortOrderFlip', $_sortOrderFlip);
$smarty->assign('_sortBy', $_sortBy);
$smarty->assign('_sortOrder', $_sortOrder);
$smarty->assign('_showResolved', $_showResolved);
$smarty->assign('_totalTicketCount', $_totalTicketCount);
$smarty->assign('_pageOffset', (isset($_GET['page'])? $_GET['page'] : 1));
$smarty->assign('_lastPage', ceil($_totalTicketCount/$_settings['recordsperpage']));
$smarty->assign('_recordsPerPage', $_settings['recordsperpage']);
$smarty->assign('_templateURL', getcwd() . '/templates/kayako');
$smarty->assign('_jscssURL', 'templates/kayako');

$templatefile = "supporttickets";
