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
 * File to show Knowledgebase categories
 *
 * @author Ruchi Kothari
 */

if (isset($_GET['catid'])) {
	$_parentCategoryID = $_GET['catid'];
} else {
	$_parentCategoryID = 0;
}

$smarty->assign('_knowledgebaseCategoryID', $_parentCategoryID);

//Retrieve Knowledgebase categories
$_searchParameters                        = array('GetList');
$_knowledgebaseCategoryContainer_Complete = $_restClient->get($_categoryController, $_searchParameters);
$_knowledgebaseCategoryContainer_Complete = $_knowledgebaseCategoryContainer_Complete['kbcategory'];

//Retrieve Knowledgebase articles
$_searchParameters                       = array('GetList');
$_knowledgebaseArticleContainer_Complete = $_restClient->get($_articalController, $_searchParameters);
$_knowledgebaseArticleContainer_Complete = $_knowledgebaseArticleContainer_Complete['kbarticle'];

//Add articles to corresponding categories
$_categoryArticleList = array();
foreach ($_knowledgebaseArticleContainer_Complete as $_key => $_knowledgebaseArticle) {

	$_knowledgebaseArticle['subject']                          = GetSubstr($_knowledgebaseArticle['subject'], 0, $_settings['subjectcharlimit']);
	$_knowledgebaseArticleContainer_Complete[$_key]['subject'] = $_knowledgebaseArticle['subject'];

	$_knowledgebaseArticle['contentstext']                          = GetSubstr($_knowledgebaseArticle['contentstext'], 0, $_settings['articlecharlimit']);
	$_knowledgebaseArticleContainer_Complete[$_key]['contentstext'] = $_knowledgebaseArticle['contentstext'];

	if (is_array($_knowledgebaseArticle['categories'][0]['categoryid'])) {
		foreach ($_knowledgebaseArticle['categories'][0]['categoryid'] as $_categoryID) {
			$_categoryArticleList[$_categoryID][] = $_knowledgebaseArticle;
		}
	} else {
		$_categoryArticleList[$_knowledgebaseArticle['categories'][0]['categoryid']][] = $_knowledgebaseArticle;
	}
}

$_tdWidth = round(100 / $_settings['categorycolumns']);

$_index                               = 1;
$_knowledgebaseCategoryContainer      = array();
$_knowledgebaseCategoryContainer_Temp = array();

foreach ($_knowledgebaseCategoryContainer_Complete as $_key => $_knowledgebaseCategory) {
	$_knowledgebaseCategory['articles'] = array_slice($_categoryArticleList[$_knowledgebaseCategory['id']], 0, $_settings['categoryarticlelimit']);
	$_knowledgebaseCategory['tdwidth']  = $_tdWidth;

	$_knowledgebaseCategoryContainer_Temp[$_knowledgebaseCategory['id']] = $_knowledgebaseCategory;

	if (isset($_GET['catid']) && $_knowledgebaseCategory['parentkbcategoryid'] == $_GET['catid'] || !isset($_GET['catid']) && $_knowledgebaseCategory['parentkbcategoryid'] == 0) {

		if ($_index > CATEGORY_COLUMNS) {
			$_index                              = 1;
			$_knowledgebaseCategory['jumptorow'] = true;
		} else {
			$_knowledgebaseCategory['jumptorow'] = false;
		}

		$_knowledgebaseCategoryContainer[$_knowledgebaseCategory['id']] = $_knowledgebaseCategory;

		$_index++;
	}
}

$_knowledgebaseCategoryContainer_Complete = $_knowledgebaseCategoryContainer_Temp;
unset($_knowledgebaseCategoryContainer_Temp);

if ($_parentCategoryID != 0) {
	$_parentCategoryList = RetreiveParentCategoryList($_parentCategoryID, $_knowledgebaseCategoryContainer_Complete);
	$smarty->assign('_parentCategoryList', $_parentCategoryList);
}

$_knowledgebaseArticleContainer = array();
foreach ($_knowledgebaseArticleContainer_Complete as $_knowledgebaseArticle) {
	if (in_array($_parentCategoryID, $_knowledgebaseArticle['categories'][0]) || in_array($_parentCategoryID, $_knowledgebaseArticle['categories'][0]['categoryid'])) {
		$_knowledgebaseArticleContainer[] = $_knowledgebaseArticle;
	}
}

$_knowledgebaseArticleContainer = SortRecords($_knowledgebaseArticleContainer, 'subject');

$_knowledgebaseArticleContainer_Recent = SortRecords($_knowledgebaseArticleContainer_Complete, 'dateline');
$_knowledgebaseArticleContainer_Recent = array_slice($_knowledgebaseArticleContainer_Recent, 0, $_settings['recentarticlelimit']);

$_knowledgebaseArticleContainer_Popular = SortRecords($_knowledgebaseArticleContainer_Complete, 'views');
$_knowledgebaseArticleContainer_Popular = array_slice($_knowledgebaseArticleContainer_Popular, 0, $_settings['populararticlelimit']);

$smarty->assign('_knowledgebaseCategoryListContainer', $_knowledgebaseCategoryContainer);
$smarty->assign('_knowledgebaseCategoryCount', count($_knowledgebaseCategoryContainer));

$smarty->assign('_knowledgebaseArticleContainer', $_knowledgebaseArticleContainer);
$smarty->assign('_knowledgebaseArticleCount', count($_knowledgebaseArticleContainer));

$smarty->assign('_knowledgebaseArticleContainer_Recent', $_knowledgebaseArticleContainer_Recent);
$smarty->assign('_knowledgebaseArticleContainer_Popular', $_knowledgebaseArticleContainer_Popular);

$templatefile = 'knowledgebasecat';