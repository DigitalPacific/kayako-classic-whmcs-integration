<script type="text/javascript" src="{$_jscssURL}/js/jQueryUI/js/jquery-ui-1.7.2.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="{$_jscssURL}/js/jQueryUI/css/custom-theme/jquery-ui-1.7.2.custom.css"/>

{literal}
<script type="text/javascript" language="Javascript">
var datePickerDefaults = {showOn: "both", buttonImage: "{/literal}{$_imageURL}{literal}/icon_calendar.gif", changeMonth: true, changeYear: true, buttonImageOnly: true, dateFormat: "{/literal}{$_dateFormat}{literal}"};
</script>
{/literal}

{foreach key=key item=_customFieldGroup from=$_customFieldGroupContainer}

	<table class="hlineheader">
		<tr>
			<th rowspan="2" nowrap>{$_customFieldGroup.title}</th>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="hlinelower">&nbsp;</td>
		</tr>
	</table>

	<table width="100%" border="0" cellspacing="1" cellpadding="4">

		{foreach key=key item=_customField from=$_customFieldGroup._fields}

			{if $_customField.fieldtype == 'custom' && $_customField.valuetype != 'static'}

				<!-- PLACE CUSTOM FIELD TYPE HTML CODE HERE -->
				{else}

				<tr>
					<td width="125" align="left" valign="middle" class="zebraodd">{$_customField.title}:{if $_customField.isrequired == '1'}<span
							class="customfieldrequired">*</span>{/if}</td>
					<td>
						{if $_customField.valuetype == 'static'}
							{$_customField.fieldvalue}
						{else}

							{if $_customField.fieldtype == 'text'}

								<input name="{$_customField.fieldname}" type="text" autocomplete="off" size="20" class="swifttextlarge" value="{$_customField.fieldvalue}">

							{elseif $_customField.fieldtype == 'textarea'}

								<textarea name="{$_customField.fieldname}" class="swifttextarea" rows="5" cols="100">{$_customField.fieldvalue}</textarea>

							{elseif $_customField.fieldtype == 'password'}

								<input name="{$_customField.fieldname}" type="password" autocomplete="off" size="20" class="swifttextlarge swiftpassword" value="{$_customField.fieldvalue}">

							{elseif $_customField.fieldtype == 'checkbox'}

								{foreach key=optionkey item=_customFieldOption from=$_customField.fieldvalue}
									<label for="{$_customField.fieldname}[{$optionkey}]">
										<input type="checkbox" id="{$_customField.fieldname}[{$optionkey}]" name="{$_customField.fieldname}[{$optionkey}]" value="{$_customFieldOption.value}"
																								{if $_customFieldOption.checked == 1}checked{/if} /> {$_customFieldOption.title}
									</label>
									<br/>
								{/foreach}

							{elseif $_customField.fieldtype == 'radio'}

								{foreach key=optionkey item=_customFieldOption from=$_customField.fieldvalue}
									<label for="{$_customField.fieldname}[{$optionkey}]">
										<input type="radio" id="{$_customField.fieldname}[{$optionkey}]" name="{$_customField.fieldname}"
																								value="{$_customFieldOption.value}"{if $_customFieldOption.checked == 1}
																								checked{/if}/>
										{$_customFieldOption.title}
									</label>
									<br/>

								{/foreach}

							{elseif $_customField.fieldtype == 'select'}

								<select name="{$_customField.fieldname}" class="swiftselect">

									{foreach key=optionkey item=_customFieldOption from=$_customField.fieldvalue}
										<option value="{$_customFieldOption.value}"{if $_customFieldOption.selected == 1} selected{/if}>{$_customFieldOption.title} </option>
									{/foreach}

								</select>

							{elseif $_customField.fieldtype == 'selectmultiple'}

								<select name="{$_customField.fieldname}[]" class="swiftselect" multiple>
									{foreach key=optionkey item=_customFieldOption from=$_customField.fieldvalue}
										<option value="{$_customFieldOption.value}"{if $_customFieldOption.selected == 1} selected{/if}>{$_customFieldOption.title}</option>
									{/foreach}
								</select>

							{elseif $_customField.fieldtype == 'selectlinked'}

								<select name="{$_customField.fieldname}[0]" class="swiftselect" onChange="javascript: LinkedSelectChanged(this, '{$_customField.fieldname}');">
									{foreach key=optionkey item=_customFieldOption from=$_customField.fieldvalue}
										<option value="{$_customFieldOption.value}"{if $_customFieldOption.selected == 1} selected{/if}>{$_customFieldOption.title}</option>
									{/foreach}
								</select>

								{if $_customField.fieldvaluelinked != false}
									{foreach key=linkedoptionkey item=_customFieldOptionContainer from=$_customField.fieldvaluelinked}
										<div id="selectsuboptioncontainer_{$linkedoptionkey}" class="linkedselectcontainer linkedselectcontainer_{$_customField.fieldname}"
											 style="display: {if $_customFieldOptionContainer.display == true}block{else}none{/if};">
											<select name="{$_customField.fieldname}[1][{$linkedoptionkey}]" class="swiftselect">
												{foreach key=optionkey item=_customFieldOption from=$_customFieldOptionContainer._options}
													<option value="{$_customFieldOption.value}"{if $_customFieldOption.selected == 1} selected{/if}>{$_customFieldOption.title}</option>
												{/foreach}
											</select>
										</div>
									{/foreach}
								{/if}

							{elseif $_customField.fieldtype == 'file'}

								<input name="{$_customField.fieldname}" type="file" size="20" class="swifttextlarge"/>
								{if $_customField.fieldvalue != ''}
									<br/>
									{$_customField.fieldvalue}
								{/if}

							{elseif $_customField.fieldtype == 'date'}

								<table border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td><input type="text" name="{$_customField.fieldname}" id="{$_customField.fieldname}" size="12" value="{$_customField.fieldvalue}"
												   class="swifttextsmall"/></td>
										<td width="2"><img src="{$_imageURL}/space.gif" width="2" border="0"/></td>
										<td align="left" valign="top">
											<div style="padding: 4px 0 0 4px;"><a href="javascript: void(0);" onclick="javascript: ClearDateField('{$_customField.fieldname}');"><img
													src="{$_imageURL}/icon_calendarcross.gif" border="0"/></a></div>
										</td>
									</tr>
								</table>

								{literal}
								<script type="text/javascript" language="Javascript">
										$(document).ready(function() {
										$("#{/literal}{$_customField.fieldname}{literal}").datepicker(datePickerDefaults);
								});
								</script>
								{/literal}
							{/if}

						{/if}
						{if $_customField.description != '' && $_customField.valuetype == 'field'}<br/><span class="smalltext">{$_customField.description}</span>{/if}</td>
				</tr>

			{/if}

		{/foreach}

	</table>
	<br/>
{/foreach}