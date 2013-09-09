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
 * @copyright      Copyright (c) 2001-2013, Kayako
 * @license        http://www.kayako.com/license
 * @link           http://www.kayako.com
 *
 * ###############################################
 */

/**
 * File to save comments
 *
 * @author Ruchi Kothari
 */
$_postData                = $_POST;
$_postData[$_itemIDKey]   = $_itemID;
$_postData['creatortype'] = CREATOR_USER;

$_kbarticlecomment = $_restClient->post($_commentController, array(), $_postData);

if (isset($_kbarticlecomment[$_rootCommentElement][0][id])) {
	$smarty->assign('_dialog', 'Thank you for submitting your feedback. Your comment might be flagged for review and will appear once approved by our staff.');
}