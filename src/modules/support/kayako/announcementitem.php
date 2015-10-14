<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/whmcs-integration
 */

// Retrieve news item
$_searchParameters = array('Get', $_GET['itemid']);
$_newsItem         = $_restClient->get($_newsController, $_searchParameters);

$_newsItem         = $_newsItem['newsitem'][0];

$_newsItem['parsedmonth'] = strftime('%b', $_newsItem['dateline']);
$_newsItem['parseddate']  = GetStrippedDay(strftime('%d', $_newsItem['dateline']));
$_newsItem['date']        = date('d F Y g:i A', $_newsItem['dateline']);

$smarty->assign('_newsItem', $_newsItem);

// Retrieve and Process comments for news item
$_searchParameters          = array('ListAll', $_GET['itemid']);
$_commentContainer_Complete = $_restClient->get($_commentController, $_searchParameters);
$_commentContainer_Complete = $_commentContainer_Complete['newsitemcomment'];

$_itemIDKey = 'newsitemid';
$_itemID    = $_GET['itemid'];

include 'comments.php';

$smarty->assign('_commentSubmitURL', WHMCS_URL . 'announcements.php?itemid=' . $_GET['itemid'] . '&action=savecomment');

$templatefile = 'announcementitem';