<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/whmcs-integration
 */

$_postData                = $_POST;
$_postData[$_itemIDKey]   = $_itemID;
$_postData['creatortype'] = CREATOR_USER;

$_kbarticlecomment = $_restClient->post($_commentController, array(), $_postData);

if (isset($_kbarticlecomment[$_rootCommentElement][0][id])) {
	$smarty->assign('_dialog', 'Thank you for submitting your feedback. Your comment might be flagged for review and will appear once approved by our staff.');
}