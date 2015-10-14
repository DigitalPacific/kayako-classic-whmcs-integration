<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/whmcs-integration
 */

$_searchParameters = array('Get', $_GET['articleid']);

$_knowledgebaseArticle = $_restClient->get($_articalController, $_searchParameters);
$_knowledgebaseArticle = $_knowledgebaseArticle['kbarticle'][0];

if (isset($_knowledgebaseArticle['categories'][0]['categoryid']) && $_knowledgebaseArticle['categories'][0]['categoryid'] > 0) {
	$_searchParameters      = array('Get', $_knowledgebaseArticle['categories'][0]['categoryid']);
	$_knowledgebaseCategory = $_restClient->get($_categoryController, $_searchParameters);

	// Return if its private category
	if ($_knowledgebaseCategory['kbcategory'][0]['categorytype'] == '3') {
		return false;
	}
}

$_staffController  = '/Base/Staff';
$_searchParameters = array('Get', $_knowledgebaseArticle['creatorid']);
$_staffUser        = $_restClient->get($_staffController, $_searchParameters);
$_staffUser        = $_staffUser['staff'][0];

$_knowledgebaseArticle['author'] = $_staffUser['fullname'];
$_knowledgebaseArticle['date']   = date('d F Y g:i A', $_knowledgebaseArticle['dateline']);

if ($_GET['catid'] != 0) {
	$_searchParameters      = array('Get', $_GET['catid']);
	$_knowledgebaseCategory = $_restClient->get($_categoryController, $_searchParameters);
	$_kbCategoryContainer   = $_knowledgebaseCategory['category'][0];
}

//Retrieve and process comments
$_searchParameters          = array('ListAll');
$_commentContainer_Complete = $_restClient->get($_commentController, $_searchParameters);
$_commentContainer_Complete = $_commentContainer_Complete['kbarticlecomment'];

$_itemIDKey = 'kbarticleid';
$_itemID    = $_GET['articleid'];

include 'comments.php';

//Process article attachments
$_searchParameters    = array('ListAll', $_GET['articleid']);
$_attachmentContainer = $_restClient->get($_attachmentController, $_searchParameters);

$_attachmentContainer = $_attachmentContainer['kbattachment'];

foreach ($_attachmentContainer as $_key => $_attachment) {
	$_attachmentContainer[$_key]['name'] = $_attachment['filename'];
	$_attachmentContainer[$_key]['size'] = FormattedSize($_attachment['filesize']);

	$_fileExtension = mb_strtolower(substr($_attachment['filename'], (strrpos($_attachment['filename'], '.') + 1)));
	$_mimeData      = GetMimeData($_fileExtension);

	$_attachmentIcon = 'icon_file.gif';
	if (isset($_mimeData[1])) {
		$_attachmentIcon = $_mimeData[1];
	}

	$_attachmentContainer[$_key]['icon'] = $_attachmentIcon;
	$_attachmentContainer[$_key]['link'] = WHMCS_URL . 'knowledgebase.php?articleid=' . $_GET['articleid'] . '&aid=' . $_attachment['id'];
}

$smarty->assign('_knowledgebaseArticle', $_knowledgebaseArticle);
$smarty->assign('_kbCategoryContainer', $_kbCategoryContainer);
$smarty->assign('_attachmentContainer', $_attachmentContainer);

$smarty->assign('_imageURL', IMAGE_URL);
$smarty->assign('_commentSubmitURL', WHMCS_URL . 'knowledgebase.php?articleid=' . $_GET['articleid'] . '&action=savecomment');

$templatefile = 'knowledgebasearticle';
