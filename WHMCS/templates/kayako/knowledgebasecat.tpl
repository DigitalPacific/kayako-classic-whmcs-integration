<link rel="stylesheet" type="text/css" href="{$_jscssURL}/css/kayako.css" />
<script type="text/javascript" src="{$_jscssURL}/js/kayako.js"></script>

<div class="boxcontainer">
	<div class="boxcontainerlabel">Knowledgebase {if $_parentCategoryList != false}: {foreach key=_categoryID name=_parentCategory item=_categoryItem from=$_parentCategoryList}{if $smarty.foreach._parentCategory.last}{$_categoryItem}{else}<a href="{$_baseURL}knowledgebase.php?catid={$_categoryID}">{$_categoryItem}</a> &gt; {/if} {/foreach} {/if} </div>

	<div class="boxcontainercontent">
		{if $_showEmptyViewWarning == true}
		<div class="infotextcontainer">
		{$_language.noinfoinview}
		</div>
		{/if}

		{if $_showEmptyViewWarning == false}
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
		<tr>
		{foreach key=_kbCategoryID item=_knowledgebaseCategory from=$_knowledgebaseCategoryListContainer}
			{if $_knowledgebaseCategory.jumptorow == true}
				</tr><tr>
			{/if}

			{if $_knowledgebaseCategory.title == false}
				<td>&nbsp;</td>
			{else}
				<td width="{$_knowledgebaseCategory.tdwidth}%" align="left" valign="top">
				<div class="kbcategorytitlecontainer"><div class="kbcategorytitle" onclick="javascript: window.location.href='{$_baseURL}knowledgebase.php?catid={$_knowledgebaseCategory.id}';"><a href="{$_baseURL}knowledgebase.php?catid={$_knowledgebaseCategory.id}">{$_knowledgebaseCategory.title}</a><span class="kbcategorycount">{if $_knowledgebaseCategory.totalarticles > 0}({$_knowledgebaseCategory.totalarticles}){/if}</span></div>
				{foreach key=_kbArticleID item=_knowledgebaseArticle from=$_knowledgebaseCategory.articles}
				<div class="kbarticlecategorylistitem"><a href="{$_baseURL}knowledgebase.php?articleid={$_knowledgebaseArticle.kbarticleid}&catid={$_knowledgebaseCategory.id}">{$_knowledgebaseArticle.subject}</a></div>
				{/foreach}
				</div>
				</td>
			{/if}
		{/foreach}
		</tr>
		</table>
		{/if}

		{if $_hasNoCategories == false}
		<br /><br />
		{/if}

		{if $_knowledgebaseArticleCount > 0}
		{foreach key=_kbArticleID item=_knowledgebaseArticle from=$_knowledgebaseArticleContainer}
		<div class="kbarticlecontainer{if $_knowledgebaseArticle.isfeatured == '1'} kbarticlefeatured{/if}">
		<div class="kbarticle"><a href="{$_baseURL}knowledgebase.php?articleid={$_knowledgebaseArticle.kbarticleid}&catid={$_knowledgebaseCategoryID}">{$_knowledgebaseArticle.subject}</a></div>
		<div class="kbarticletext">{$_knowledgebaseArticle.contentstext}</div>
		</div>
		{/foreach}
		{/if}

		{if $_knowledgebaseCategoryID == 0 && $_showEmptyViewWarning == false}
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
		<tr>
		{if $_settings.kb_enpopulararticles == '1'}
		<td width="50%" align="left" valign="top"><div class="kbrightstrip">
		<table class="hlineheader hlinegray"><tr><th rowspan="2" nowrap>Most Popular</th><td>&nbsp;</td></tr><tr><td class="hlinelower">&nbsp;</td></tr></table>
		</div>
		<ol>
		{foreach key=_kbArticleID item=_knowledgebaseArticle from=$_knowledgebaseArticleContainer_Popular}
		<li class="kbarticlelist">
		<div class="kbarticlelistitem"><a href="{$_baseURL}knowledgebase.php?articleid={$_knowledgebaseArticle.kbarticleid}&catid={$_knowledgebaseCategoryID}">{$_knowledgebaseArticle.subject}</a></div>
		</li>
		{/foreach}
		</ol>
		</td>
		{/if}

		{if $_settings.kb_enlatestarticles == '1'}
		<td width="{if $_settings.kb_enpopulararticles == '1'}50%{else}100%{/if}" align="left" valign="top">
		<div><table class="hlineheader hlinegray"><tr><th rowspan="2" nowrap>Recent Articles</th><td>&nbsp;</td></tr><tr><td class="hlinelower">&nbsp;</td></tr></table></div>
		<ol>
		{foreach key=_kbArticleID item=_knowledgebaseArticle from=$_knowledgebaseArticleContainer_Recent}
		<li class="kbarticlelist">
		<div class="kbarticlelistitem"><a href="{$_baseURL}knowledgebase.php?articleid={$_knowledgebaseArticle.kbarticleid}&catid={$_knowledgebaseCategoryID}">{$_knowledgebaseArticle.subject}</a></div>
		</li>
		{/foreach}
		</ol>
		</td>
		{/if}
		</tr>
		</table>
		{/if}
	</div>
</div>