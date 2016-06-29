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

//Include common functions
require_once 'functions.php';

//Include constants file
require_once 'constants.php';

//Initialize the client
kyConfig::set(new kyConfig(API_URL, API_KEY, SECRET_KEY));

$clientsdetails = getclientsdetails($_userid);
if (!isset($_REQUEST['step'])) {

	$_allDepartments = kyDepartment::getAll()->filterByModule(kyDepartment::MODULE_TICKETS)->filterByType(kyDepartment::TYPE_PUBLIC);
	$_topDepartments = $_allDepartments->orderByDisplayOrder();

	$_departmentMap = [];
	foreach ($_topDepartments as $_topDepartment) {

		$_department                   = [];
		$_topDepartmentID              = $_topDepartment->getId();
		$_department['departmentid']   = $_topDepartmentID;
		$_department['title']          = $_topDepartment->getTitle();
		$_department['departmenttype'] = $_topDepartment->getType();

		$_departmentMap[$_topDepartmentID] = $_department;

		$_childDepartments = $_allDepartments->filterByParentDepartmentId($_topDepartmentID)->orderByDisplayOrder();

		foreach ($_childDepartments as $_childDepartment) {

			$_department                   = [];
			$_department['departmentid']   = $_childDepartment->getId();
			$_department['title']          = $_childDepartment->getTitle();
			$_department['departmenttype'] = $_childDepartment->getType();
		}
	}

	$smarty->assign('_departments', $_departmentMap);
	$smarty->assign('_departmentFormURL', WHMCS_URL . 'submitticket.php?step=2');

	$templatefile = "selectdepartment";
} else if ($_REQUEST['step'] == 2) {

	if (!empty($_POST['departmentid'])) {

		// get priorities of 'public' type only
		$_ticketPriorityContainer = kyTicketPriority::getAll()->filterByType(kyTicketPriority::TYPE_PUBLIC)->orderByDisplayOrder();

		$_ticketPriorities = [];
		foreach ($_ticketPriorityContainer as $_ticketPriorityObject) {

			$_ticketPriority['priorityid'] = $_ticketPriorityObject->getId();
			$_ticketPriority['title']      = $_ticketPriorityObject->getTitle();
			$_ticketPriorities[]           = $_ticketPriority;
		}

		$smarty->assign('_ticketPriorities', $_ticketPriorities);

		//Get Ticket Custom fields
		$_customFieldObjectContainer = kyCustomFieldDefinition::getAll();

		$_customFields = [];
		foreach ($_customFieldObjectContainer as $_customFieldObject) {
			$_customFields[$_customFieldObject->getGroupId()][] = RenderCustomField($_customFieldObject, MODE_INSERT);
		}

		// Custom field groups
		$_customFieldGroupContainer = [];

		// fetch custom field groups based on selected department
		$_customFieldGroupObjectContainer = kyCustomFieldGroup::getAll(['departmentid' => $_POST['departmentid']])->filterByGroupType([
																																				kyCustomFieldGroup::GROUP_STAFFTICKET,
																																				kyCustomFieldGroup::GROUP_STAFFUSERTICKET,
																																				kyCustomFieldGroup::GROUP_USERTICKET
																																		   ]);
		foreach ($_customFieldGroupObjectContainer as $_customFieldGroupObject) {

			if (isset($_customFields[$_customFieldGroupObject->getId()])) {

				$_customFieldGroup            = [];
				$_customFieldGroup['title']   = $_customFieldGroupObject->getTitle();
				$_customFieldGroup['_fields'] = $_customFields[$_customFieldGroupObject->getId()];

				$_customFieldGroupContainer[$_customFieldGroupObject->getId()] = $_customFieldGroup;
			}
		}

		$smarty->assign('_customFieldGroupContainer', $_customFieldGroupContainer);
		$smarty->assign('_ticketFormURL', WHMCS_URL . 'submitticket.php?step=3');
		$smarty->assign('_departmentID', $_POST['departmentid']);
		$smarty->assign('_templateURL', getcwd() . '/templates/kayako');
		$smarty->assign('_imageURL', WHMCS_URL . 'templates/kayako/images');

		$templatefile = 'ticketform';
	} else {
		header('Location: ' . WHMCS_URL . 'submitticket.php');
	}
} else if ($_REQUEST['step'] == 3) {

	if (!empty($_POST)) {

		//Set Defaults for a new ticket
		$_defaultStatusID   = kyTicketStatus::getAll()->filterByType(kyTicketStatus::TYPE_PUBLIC)->first()->getId();
		$_defaultPriorityID = kyTicketPriority::getAll()->filterByType(kyTicketStatus::TYPE_PUBLIC)->first()->getId();
		$_defaultTypeID     = kyTicketType::getAll()->filterByType(kyTicketStatus::TYPE_PUBLIC)->first()->getId();

		kyTicket::setDefaults($_defaultStatusID, $_defaultPriorityID, $_defaultTypeID);

		//Create ticket
		$_department = kyDepartment::get($_POST['departmentid']);
		$_priority   = kyTicketPriority::get($_POST['ticketpriorityid']);
		$_ticket     = kyTicket::createNewAuto($_department, $clientsdetails['firstname'] . ' ' . $clientsdetails['lastname'], $clientsdetails['email'], $_POST['ticketmessage'], $_POST['ticketsubject'])
			->setPriority($_priority)
			->setIgnoreAutoResponder($_settings['ignoreautoresponder'])
			->create();

		$_ticketPosts = $_ticket->getPosts();

		//Save ticket attachments
		foreach ($_FILES['ticketattachments']['tmp_name'] as $_key => $_ticketAttachment) {
			kyTicketAttachment::createNewFromFile($_ticketPosts[0], $_ticketAttachment, $_FILES['ticketattachments']['name'][$_key])
				->create();
		}

		//Save custom fields
		$_ticket->setCustomFieldValuesFromPOST();
		$_ticket->updateCustomFields();

		$smarty->assign('_ticketDisplayID', $_ticket->getDisplayId());
		$smarty->assign('_ticketSubject', $_POST['ticketsubject']);
		$smarty->assign('_ticketMessage', nl2br($_POST['ticketmessage']));

		$templatefile = 'ticketConfirmation';
	} else {
		header('Location: ' . WHMCS_URL . 'submitticket.php');
	}
} else {
	header('Location: ' . WHMCS_URL . 'submitticket.php');
}

$smarty->assign('_jscssURL', 'templates/kayako');
