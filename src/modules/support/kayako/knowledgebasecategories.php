<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/whmcs-integration
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
if (!empty($_parentCategoryID)) {
	$_searchParameters = array('GetArticleCount', $_parentCategoryID);
} else {
	$_searchParameters = array($_parentCategoryID);
}

$_knowledgebaseArticleCount = $_restClient->get($_articalController, $_searchParameters);
$_totalArticles             = $_knowledgebaseArticleCount['totalarticles'];

$_tdWidth = round(100 / $_settings['categorycolumns']);

$_index                               = 1;
$_knowledgebaseCategoryContainer      = array();
$_knowledgebaseCategoryContainer_Temp = array();
$_privateCategoryContainer            = array();

foreach ($_knowledgebaseCategoryContainer_Complete as $_key => $_knowledgebaseCategory) {

	if ($_knowledgebaseCategory['categorytype'] == '3') {
		$_privateCategoryContainer[] = $_knowledgebaseCategory['id'];
		continue;
	}

	$_knowledgebaseCategory['articles'] = array_slice($_categoryArticleList[$_knowledgebaseCategory['id']], 0, $_settings['categoryarticlelimit']);
	$_knowledgebaseCategory['tdwidth']  = $_tdWidth;

	$_knowledgebaseCategoryContainer_Temp[$_knowledgebaseCategory['id']] = $_knowledgebaseCategory;

	if (isset($_GET['catid']) && $_knowledgebaseCategory['parentkbcategoryid'] == $_GET['catid'] || !isset($_GET['catid']) && $_knowledgebaseCategory['parentkbcategoryid'] == 0) {

		if ($_index > $_settings['categorycolumns']) {
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

$_offset = ($_GET['page'] > 1 ? ($_GET['page'] - 1) * $_settings['recordsperpage'] : 0);

$_searchParameters                        = array('ListAll', $_parentCategoryID, $_settings['recordsperpage'], $_offset);
$_parentCategoryArticleContainer_Complete = $_restClient->get($_articalController, $_searchParameters);
$_parentCategoryArticleContainer_Complete = $_parentCategoryArticleContainer_Complete['kbarticle'];

$_knowledgebaseArticleContainer = array();
foreach ($_parentCategoryArticleContainer_Complete as $_knowledgebaseArticle) {

	if (in_array($_parentCategoryID, $_privateCategoryContainer)) {
		continue;
	}

	if (in_array($_parentCategoryID, $_knowledgebaseArticle['categories'][0]) || in_array($_parentCategoryID, $_knowledgebaseArticle['categories'][0]['categoryid'])) {
		$_knowledgebaseArticleContainer[] = $_knowledgebaseArticle;
	}
}

$_knowledgebaseArticleContainer = SortRecords($_knowledgebaseArticleContainer, 'subject');

$smarty->assign('_knowledgebaseCategoryListContainer', $_knowledgebaseCategoryContainer);
$smarty->assign('_knowledgebaseCategoryCount', count($_knowledgebaseCategoryContainer));

$smarty->assign('_knowledgebaseArticleContainer', $_knowledgebaseArticleContainer);
$smarty->assign('_knowledgebaseArticleCount', count($_knowledgebaseArticleContainer));

$smarty->assign('_knowledgebaseURL', WHMCS_URL . 'knowledgebase.php?catid=' . $_parentCategoryID);
$smarty->assign('_totalArticleCount', $_totalArticles);
$smarty->assign('_pageOffset', (isset($_GET['page']) ? $_GET['page'] : 1));
$smarty->assign('_lastPage', ceil($_totalArticles / $_settings['recordsperpage']));
$smarty->assign('_recordsPerPage', $_settings['recordsperpage']);

$templatefile = 'knowledgebasecat';