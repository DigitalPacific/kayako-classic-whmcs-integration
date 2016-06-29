<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/whmcs-integration
 */

if (!empty($_GET['tid'])) {
	$_ticketID = $_GET['tid'];
} else {
	$_ticketID = $_GET['ticketid'];
}

//Get ticket properties
$_ticketObject = kyTicket::get($_ticketID);
$clientsdetails = getclientsdetails($_userid);

if ($_ticketObject->getEmail() != $clientsdetails['email']) {
	$smarty->assign('_noPermissions', true);
} else {
	$_ticketContainer                    = array();
	$_ticketContainer['ticketid']        = $_ticketObject->getId();
	$_ticketContainer['displayticketid'] = $_ticketObject->getDisplayId();
	$_ticketContainer['departmentid']    = $_ticketObject->getDepartmentId();
	$_ticketContainer['departmenttitle'] = $_ticketObject->getDepartment()->getTitle();
	$_ticketContainer['departmenttype']  = $_ticketObject->getDepartment()->getType();

	if ($_ticketContainer['departmenttype'] == kyDepartment::TYPE_PRIVATE) {
		$_ticketContainer['department'] = 'Private';
	} else {
		$_ticketContainer['department'] = $_ticketContainer['departmenttitle'];
	}

	$_ticketContainer['ticketstatusid']  = $_ticketObject->getStatusId();
	$_ticketContainer['status']          = $_ticketObject->getStatus()->getTitle();
	$_ticketContainer['statusbgcolor']   = $_ticketObject->getStatus()->getStatusBackgroundColor();
	$_ticketContainer['priorityid']      = $_ticketObject->getPriorityId();
	$_ticketContainer['priority']        = $_ticketObject->getPriority()->getTitle();
	$_ticketContainer['prioritybgcolor'] = $_ticketObject->getPriority()->getBackgroundColor();
	$_ticketContainer['userid']          = $_ticketObject->getUserId();
	$_ticketContainer['tickettypeid']    = $_ticketObject->getTypeId();
	$_ticketContainer['type']            = $_ticketObject->getType()->getTitle();
	$_ticketContainer['userid']          = $_ticketObject->getUserId();
	$_ticketContainer['fullname']        = $_ticketObject->getFullName();
	$_ticketContainer['email']           = $_ticketObject->getEmail();
	$_ticketContainer['ownerstaffid']    = $_ticketObject->getOwnerStaffId();
	$_ticketContainer['owner']           = $_ticketObject->getOwnerStaffName();
	$_ticketContainer['lastreplier']     = $_ticketObject->getLastReplier();
	$_ticketContainer['subject']         = $_ticketObject->getSubject();
	$_ticketContainer['dateline']        = $_ticketObject->getCreationTime();
	$_ticketContainer['lastactivity']    = $_ticketObject->getLastActivity();

	//Get ticket posts
	$_ticketPostObjectContainer = kyTicketPost::getAll($_ticketContainer['ticketid']);

	$_ticketPostContainer = array();
	foreach ($_ticketPostObjectContainer as $_ticketPostObject) {

		$_ticketPost['ticketpostid']    = $_ticketPostObject->getId();
		$_ticketPost['ticketid']        = $_ticketPostObject->getTicketId();
		$_ticketPost['dateline']        = $_ticketPostObject->getDateline();
		$_ticketPost['userid']          = $_ticketPostObject->getUserId();
		$_ticketPost['fullname']        = $_ticketPostObject->getFullName();
		$_ticketPost['email']           = $_ticketPostObject->getEmail();
		$_ticketPost['emailto']         = $_ticketPostObject->getEmailTo();
		$_ticketPost['subject']         = $_ticketPostObject->getSubject();
		$_ticketPost['ipaddress']       = $_ticketPostObject->getIPAddress();
		$_ticketPost['hasattachments']  = $_ticketPostObject->getHasAttachments();
		$_ticketPost['creator']         = $_ticketPostObject->getCreatorType();
		$_ticketPost['isthirdparty']    = $_ticketPostObject->getIsThirdParty();
		$_ticketPost['ishtml']          = $_ticketPostObject->getIsHTML();
		$_ticketPost['isemailed']       = $_ticketPostObject->getIsEmailed();
		$_ticketPost['staffid']         = $_ticketPostObject->getStaffId();
		$_ticketPost['contents']        = nl2br($_ticketPostObject->getContents());
		$_ticketPost['issurveycomment'] = $_ticketPostObject->getIsSurveyComment();

		$_creatorLabel = 'user';
		if ($_ticketPost['isthirdparty'] == '1' || $_ticketPost['creator'] == kyTicketPost::CREATOR_THIRDPARTY) {

			$_badgeClass   = 'ticketpostbarbadgered';
			$_badgeText    = 'Third Party';
			$_creatorLabel = 'thirdparty';
		} else if ($_ticketPost['creator'] == kyTicketPost::CREATOR_CC) {

			$_badgeClass   = 'ticketpostbarbadgered';
			$_badgeText    = 'Recipient';
			$_creatorLabel = 'cc';
		} else if ($_ticketPost['creator'] == kyTicketPost::CREATOR_BCC) {

			$_badgeClass   = 'ticketpostbarbadgered';
			$_badgeText    = 'BCC';
			$_creatorLabel = 'bcc';
		} else if ($_ticketPost['creator'] == kyTicketPost::CREATOR_USER) {

			$_badgeClass   = 'ticketpostbarbadgeblue';
			$_badgeText    = 'User';
			$_creatorLabel = 'user';
		} else if ($_ticketPost['creator'] == kyTicketPost::CREATOR_STAFF) {

			$_badgeClass   = 'ticketpostbarbadgered';
			$_badgeText    = 'Staff';
			$_creatorLabel = 'staff';
		}
		$_ticketPost['creatorlabel'] = $_creatorLabel;

		$_postTitle                = 'Posted on: ' . $_ticketPost['dateline'];
		$_ticketPost['posttitle']  = $_postTitle;
		$_ticketPost['badgeclass'] = $_badgeClass;
		$_ticketPost['badgetext']  = $_badgeText;

		$_ticketPostMinimumHeight = 300;

		$_ticketPost['minimumheight'] = $_ticketPostMinimumHeight;

		$_attachmentObjectContainer = $_ticketPostObject->getAttachments();

		//Process ticket post attachments
		$_ticketAttachmentContainer = array();
		foreach ($_attachmentObjectContainer as $_attachmentObject) {

			$_ticketAttachment         = array();
			$_ticketAttachment['name'] = $_attachmentObject->getFileName();
			$_ticketAttachment['size'] = FormattedSize($_attachmentObject->getFileSize());

			$_fileExtension = mb_strtolower(substr($_ticketAttachment['name'], (strrpos($_ticketAttachment['name'], '.') + 1)));
			$_mimeData      = GetMimeData($_fileExtension);

			$_attachmentIcon = 'icon_file.gif';
			if (isset($_mimeData[1])) {
				$_attachmentIcon = $_mimeData[1];
			}

			$_ticketAttachment['icon'] = $_attachmentIcon;
			$_ticketAttachment['link'] = WHMCS_URL . 'viewticket.php?tid=' . $_GET['ticketid'] . '&aid=' . $_attachmentObject->getId();

			$_ticketAttachmentContainer[] = $_ticketAttachment;
		}

		$_ticketPost['attachments'] = $_ticketAttachmentContainer;

		$_ticketPostContainer[$_ticketPost['ticketpostid']] = $_ticketPost;
	}

	//Process customfields
	$_customFieldGroupContainer       = array();
	$_customFieldGroupObjectContainer = $_ticketObject->getCustomFieldGroups();

	foreach ($_customFieldGroupObjectContainer as $_customFieldGroupObject) {
		$_customFieldGroup['title'] = $_customFieldGroupObject->getTitle();

		$_customFieldGroup['_fields'] = array();
		$_customFieldObjectContainer  = $_customFieldGroupObject->getFields();

		foreach ($_customFieldObjectContainer as $_customFieldObject) {
			$_customFieldGroup['_fields'][] = RenderCustomField($_customFieldObject, MODE_EDIT);
		}

		$_customFieldGroupContainer[$_customFieldGroupObject->getId()] = $_customFieldGroup;
	}

	$smarty->assign('_ticketContainer', $_ticketContainer);
	$smarty->assign('_ticketPostContainer', $_ticketPostContainer);
	$smarty->assign('_customFieldGroupContainer', $_customFieldGroupContainer);
}
