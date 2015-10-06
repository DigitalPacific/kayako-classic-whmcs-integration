<link rel="stylesheet" type="text/css" href="{$_jscssURL}/css/kayako.css"/>
<script type="text/javascript" src="{$_jscssURL}/js/kayako.js"></script>

<div class="boxcontainer">
	<div class="boxcontainerlabel">
		Knowledgebase {if $_parentCategoryList != false}: {foreach key=_categoryID name=_parentCategory item=_categoryItem from=$_parentCategoryList}{if $smarty.foreach._parentCategory.last}{$_categoryItem}{else}
			<a href="{$_baseURL}knowledgebase.php?catid={$_categoryID}">{$_categoryItem}</a> &gt; {/if} {/foreach} {/if} </div>

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
				</tr>
				<tr>
					{/if}

					{if $_knowledgebaseCategory.title == false}
						<td>&nbsp;</td>
					{else}
						<td width="{$_knowledgebaseCategory.tdwidth}%" align="left" valign="top">
							<div class="kbcategorytitlecontainer">
								<div class="kbcategorytitle" onclick="javascript: window.location.href='{$_baseURL}knowledgebase.php?catid={$_knowledgebaseCategory.id}';"><a
											href="{$_baseURL}knowledgebase.php?catid={$_knowledgebaseCategory.id}">{$_knowledgebaseCategory.title}</a><span
											class="kbcategorycount">{if $_knowledgebaseCategory.totalarticles > 0} {$_knowledgebaseCategory.totalarticles} {/if}</span></div>
								{foreach key=_kbArticleID item=_knowledgebaseArticle from=$_knowledgebaseCategory.articles}
									<div class="kbarticlecategorylistitem"><a
												href="{$_baseURL}knowledgebase.php?articleid={$_knowledgebaseArticle.kbarticleid}&catid={$_knowledgebaseCategory.id}">{$_knowledgebaseArticle.subject}</a>
									</div>
								{/foreach}
							</div>
						</td>
					{/if}
					{/foreach}
				</tr>
			</table>
		{/if}

		{if $_knowledgebaseArticleCount > 0}

			{foreach key=_kbArticleID item=_knowledgebaseArticle from=$_knowledgebaseArticleContainer}
				<div class="kbarticlecontainer{if $_knowledgebaseArticle.isfeatured == '1'} kbarticlefeatured{/if}">
					<div class="kbarticle"><a href="{$_baseURL}knowledgebase.php?articleid={$_knowledgebaseArticle.kbarticleid}&catid={$_knowledgebaseCategoryID}">{$_knowledgebaseArticle.subject}</a>
					</div>
					<div class="kbarticletext">{$_knowledgebaseArticle.contentstext}</div>
				</div>
			{/foreach}

			{if $_totalArticleCount > $_recordsPerPage}
				<div style="height:25px; font-weight: bold;">
					<div style="float: left;">
						{if $_pageOffset > 1}
							<a href="{$_knowledgebaseURL}&page={$_pageOffset-1}">&laquo; Previous</a>
						{/if}
					</div>
					<div style="float: right;">
						{if $_pageOffset < $_lastPage}
							<a href="{$_knowledgebaseURL}&page={$_pageOffset+1}">Next &raquo;</a>
						{/if}
					</div>
				</div>
			{/if}

		{/if}
	</div>
</div>