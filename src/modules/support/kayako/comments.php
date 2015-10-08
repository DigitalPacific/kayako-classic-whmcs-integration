<?php
/**
 * ###############################################
 *
 * WHMCS Integration
 * _______________________________________________
 *
 * @author         Ruchi Kothari
 *
 * @package        WHMCS Integration
 * @copyright      Copyright (c) 2001-2015, Kayako
 * @license        http://www.kayako.com/license
 * @link           http://www.kayako.com
 *
 * ###############################################
 */

/**
 * File to handle comments
 *
 * @author Ruchi Kothari
 */

$_commentContainer = array();

foreach ($_commentContainer_Complete as $_comment) {

	$_comment['avatarurl'] = WHMCS_URL . 'templates/kayako/images/defaultavtar.png';
	$_comment['date']      = date('d F Y g:i A', $_comment['dateline']);

	if ($_comment['creatortype'] == CREATOR_STAFF) {
		$_comment['isstaff'] = true;
	}

	if ($_comment['commentstatus'] == STATUS_APPROVED && $_comment[$_itemIDKey] == $_itemID) {
		$_commentContainer[$_comment['id']] = $_comment;
	}
}

$_finalCommentContainer = array();

RetrieveCommentHierarchy(0, $_commentContainer, $_finalCommentContainer);

$smarty->assign('_commentContainer', $_finalCommentContainer);
$smarty->assign('_commentCount', count($_finalCommentContainer));

if (isset($clientsdetails)) {
	$smarty->assign('_commentsFullName', $clientsdetails['firstname'] . ' ' . $clientsdetails['lastname']);
	$smarty->assign('_commentsEmail', $clientsdetails['email']);
}

$smarty->assign('_canPostComments', $_settings['canpostcomments']);