<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/whmcs-integration
 */

/**
 * Render the Static Options for Ticket system
 *
 * @author Ruchi Kothari
 *
 * @param array $_optionsContainer The Options Container
 *
 * @return string $_staticOptions Static options
 */
function GetStaticOptions($_optionsContainer)
{
	// Return emplty string if optioncontainer is not array or empty
	if (!is_array($_optionsContainer) || empty($_optionsContainer)) {
		return '';
	}

	$_staticOptions     = '';
	$_staticOptionsList = array();
	foreach ($_optionsContainer as $_option) {
		// If option value is not saved continue with other option
		if ($_option['stored'] != true) {
			continue;
		}

		if ((isset($_option['checked']) && $_option['checked'] == true) || (isset($_option['selected']) && $_option['selected'] == true)) {
			$_optionPrefix = '';
			if ($_option['parent'] != '0') {
				$_optionPrefix = '<img src="' . IMAGE_URL . 'linkdownarrow_blue.gif" align="absmiddle" border="0" /> ';
			}

			$_staticOptionsList[] = $_optionPrefix . htmlspecialchars($_option['title']);
		}
	}

	$_staticOptions = implode('<br/>', $_staticOptionsList);

	return $_staticOptions;
}

/**
 * Escape the Option title and values for Ticket system
 *
 * @author Ruchi Kothari
 *
 * @param array $_optionsContainer The Options Container
 *
 * @return array $_optionsContainer The Processed Container
 */
function EscapeOptions($_optionsContainer)
{
	foreach ($_optionsContainer as $_key => $_val) {
		$_optionsContainer[$_key]['title'] = htmlspecialchars($_val['title']);
		$_optionsContainer[$_key]['value'] = htmlspecialchars($_val['value']);
	}

	return $_optionsContainer;
}

/**
 * Retrieve the options container array for a given custom field for Ticket system
 *
 * @author Ruchi Kothari
 *
 * @param array $_customField The Custom Field Container
 * @param array $_valueList   List of saved values
 *
 * @return array $_optionsContainer The Options Container Array
 */
function GetOptions($_customField, $_valueList)
{
	$_optionsContainer = array();

	if (isset($_customField['_options'])) {
		$_index = 0;

		foreach ($_customField['_options'] as $_customFieldOptionID => $_customFieldOption) {
			$_optionsContainer[$_index]['title'] = $_customFieldOption['optionvalue'];
			$_optionsContainer[$_index]['value'] = $_customFieldOptionID;

			if (isset($_customFieldOption['parentcustomfieldoptionid'])) {
				$_optionsContainer[$_index]['parent'] = $_customFieldOption['parentcustomfieldoptionid'];
			}

			$_optionsContainer[$_index]['stored'] = false;

			// Do we have stored values?
			if (!empty($_valueList)) {
				// By default we will mark it as not selected
				$_customFieldOption['isselected']     = false;
				$_optionsContainer[$_index]['stored'] = true;

				if (in_array($_customFieldOptionID, $_valueList)) {
					$_customFieldOption['isselected'] = true;
				}
			}

			if ($_customFieldOption['isselected'] == true && ($_customField['type'] == kyCustomFieldDefinition::TYPE_CHECKBOX
				|| $_customField['type'] == kyCustomFieldDefinition::TYPE_RADIO)
			) {
				$_optionsContainer[$_index]['checked'] = true;
			} else if ($_customFieldOption['isselected'] == true) {
				$_optionsContainer[$_index]['selected'] = true;
			}

			$_index++;
		}
	}

	return $_optionsContainer;
}

/**
 * Get list of values from Option Objects for Ticket system
 *
 * @author Ruchi Kothari
 *
 * @param array $_valueObjectContainer List of kyCustomFieldOption objects
 *
 * @return array $_valueList List of values
 */
function GetValueList($_valueObjectContainer)
{
	$_valueList = array();

	if (!empty($_valueObjectContainer)) {
		if (is_array($_valueObjectContainer)) {
			foreach ($_valueObjectContainer as $_valueObject) {
				$_valueList[] = $_valueObject->getId();
				$_parentID    = $_valueObject->getParentOptionId();

				if (isset($_parentID) && intval($_parentID)) {
					$_valueList[] = $_parentID;
				}
			}
		} else {
			$_valueList[] = $_valueObjectContainer->getId();
			$_parentID    = $_valueObjectContainer->getParentOptionId();

			if (isset($_parentID) && intval($_parentID)) {
				$_valueList[] = $_parentID;
			}
		}
	}

	return $_valueList;
}

/**
 * Render custom field for Ticket system
 *
 * @author Ruchi Kothari
 *
 * @param        kyCustomField or kyCustomFieldDefinition $_customFieldObject
 * @param string $_mode
 *
 * @return array $_customField Custom field
 * @throws SWIFT_Exception If the Class is not Loaded
 */
function RenderCustomField($_customFieldObject, $_mode = MODE_INSERT)
{
	// If $_customFieldObject is not instance of kyCustomFieldDefinition get customfield definition
	if ($_customFieldObject instanceof kyCustomFieldDefinition) {
		$_customFieldDefinition = $_customFieldObject;
	} else {
		$_customFieldDefinition = $_customFieldObject->getDefinition();
	}

	$_customField                       = array();
	$_customField['customfieldid']      = $_customFieldObject->getId();
	$_customField['customfieldgroupid'] = $_customFieldDefinition->getGroupId();
	$_customField['title']              = $_customFieldObject->getTitle();
	$_customField['fieldname']          = $_customFieldObject->getName();
	$_customField['defaultvalue']       = $_customFieldDefinition->getDefaultValue();
	$_customField['isrequired']         = $_customFieldDefinition->getIsRequired();
	$_customField['usereditable']       = $_customFieldDefinition->getIsUserEditable();
	$_customField['staffeditable']      = $_customFieldDefinition->getIsStaffEditable();
	$_customField['regexpvalidate']     = $_customFieldDefinition->getRegexpValidate();
	$_customField['displayorder']       = $_customFieldDefinition->getDisplayOrder();
	$_customField['encryptindb']        = $_customFieldDefinition->getIsEncrypted();
	$_customField['description']        = $_customFieldDefinition->getDescription();
	$_customField['type']               = $_customFieldObject->getType();

	// Create options list
	$_optionObjectContainer = $_customFieldDefinition->getOptions()->orderByDisplayOrder();
	foreach ($_optionObjectContainer as $_optionObject) {
		$_option['customfieldoptionid']       = $_optionObject->getId();
		$_option['customfieldid']             = $_optionObject->getFieldId();
		$_option['optionvalue']               = $_optionObject->getValue();
		$_option['displayorder']              = $_optionObject->getDisplayOrder();
		$_option['isselected']                = $_optionObject->getIsSelected();
		$_option['parentcustomfieldoptionid'] = intval($_optionObject->getParentOptionId());

		$_customField['_options'][$_optionObject->getId()] = $_option;
	}

	// Get customfield value
	$_customfieldValue = $_customFieldDefinition->getDefaultValue();

	if ($_mode == MODE_EDIT) {
		if ($_customFieldObject->getValue() != "") {
			$_customfieldValue = $_customFieldObject->getValue();
		}
	}
	//echo "<pre>";
	//print_r($_customfieldValue);
	switch ($_customFieldObject->getType()) {
		case kyCustomFieldDefinition::TYPE_TEXT:
			$_customField['fieldtype']  = 'text';
			$_customField['fieldvalue'] = htmlspecialchars($_customfieldValue);

			if ($_customField['usereditable'] == 0 && $_mode == MODE_EDIT) {
				$_customField['valuetype'] = 'static';
			} else {
				$_customField['valuetype'] = 'field';
			}

			break;

		case kyCustomFieldDefinition::TYPE_TEXTAREA:
			$_customField['fieldtype']  = 'textarea';
			$_customField['fieldvalue'] = nl2br(htmlspecialchars($_customfieldValue));

			if ($_customField['usereditable'] == 0 && $_mode == MODE_EDIT) {
				$_customField['valuetype'] = 'static';
			} else {
				$_customField['valuetype'] = 'field';
			}

			break;

		case kyCustomFieldDefinition::TYPE_PASSWORD:
			$_customField['fieldtype']  = 'password';
			$_customField['fieldvalue'] = htmlspecialchars($_customfieldValue);

			if ($_customField['usereditable'] == 0 && $_mode == MODE_EDIT) {
				$_customField['valuetype'] = 'static';
			} else {
				$_customField['valuetype'] = 'field';
			}

			break;

		case kyCustomFieldDefinition::TYPE_CHECKBOX:
			$_customField['fieldtype'] = 'checkbox';
			$_valueList                = GetValueList($_customfieldValue);
			//echo "<pre>";
			//print_r($_customfieldValue);
			$_optionsContainer         = GetOptions($_customField, $_valueList);
//print_r($_optionsContainer);
			if ($_customField['usereditable'] == 0 && $_mode == MODE_EDIT) {
				$_customField['valuetype']  = 'static';
				$_customField['fieldvalue'] = GetStaticOptions($_optionsContainer);
			} else {
				$_customField['valuetype']  = 'field';
				$_customField['fieldvalue'] = EscapeOptions($_optionsContainer);
			}

			break;

		case kyCustomFieldDefinition::TYPE_RADIO:
			$_customField['fieldtype'] = 'radio';
			$_valueList                = GetValueList($_customfieldValue);
			$_optionsContainer         = GetOptions($_customField, $_valueList);

			if ($_customField['usereditable'] == 0 && $_mode == MODE_EDIT) {
				$_customField['valuetype']  = 'static';
				$_customField['fieldvalue'] = GetStaticOptions($_optionsContainer);
			} else {
				$_customField['valuetype']  = 'field';
				$_customField['fieldvalue'] = EscapeOptions($_optionsContainer);
			}

			break;

		case kyCustomFieldDefinition::TYPE_SELECT:
			$_customField['fieldtype'] = 'select';
			$_valueList                = GetValueList($_customfieldValue);
			$_optionsContainer         = GetOptions($_customField, $_valueList);

			if ($_customField['usereditable'] == 0 && $_mode == MODE_EDIT) {
				$_customField['valuetype']  = 'static';
				$_customField['fieldvalue'] = GetStaticOptions($_optionsContainer);
			} else {
				$_customField['valuetype']  = 'field';
				$_customField['fieldvalue'] = EscapeOptions($_optionsContainer);
			}

			break;

		case kyCustomFieldDefinition::TYPE_LINKED_SELECT:
			$_customField['fieldtype'] = 'selectlinked';
			$_valueList                = GetValueList($_customfieldValue);
			$_optionsContainer         = GetOptions($_customField, $_valueList);
			$_parentOptionContainer    = array();

			foreach ($_optionsContainer as $_optionIndex => $_option) {
				if (!empty($_option['parent'])) {
					if (!isset($_parentOptionContainer[$_option['parent']])) {
						$_parentOptionContainer[$_option['parent']] = array();
					}

					$_parentOptionContainer[$_option['parent']]['_options'][$_optionIndex] = $_option;
					$_parentOptionContainer[$_option['parent']]['display']                 = false;
				}
			}

			// Itterate again and see if parent is selected
			$_finalFieldValue = array();
			foreach ($_optionsContainer as $_optionIndex => $_option) {
				if (empty($_option['parent'])) {
					$_finalFieldValue[$_optionIndex] = $_option;
				}

				if (empty($_option['parent']) && isset($_parentOptionContainer[$_option['value']]) && isset($_option['selected']) && $_option['selected'] == true) {
					$_parentOptionContainer[$_option['value']]['display'] = true;
				}
			}

			$_customField['fieldvaluelinked'] = $_parentOptionContainer;

			if ($_customField['usereditable'] == 0 && $_mode == MODE_EDIT) {
				$_customField['valuetype']  = 'static';
				$_customField['fieldvalue'] = GetStaticOptions($_optionsContainer);
			} else {
				$_customField['valuetype']  = 'field';
				$_customField['fieldvalue'] = $_finalFieldValue;
			}

			break;

		case kyCustomFieldDefinition::TYPE_MULTI_SELECT:
			$_customField['fieldtype'] = 'selectmultiple';
			$_valueList                = GetValueList($_customfieldValue);
			$_optionsContainer         = GetOptions($_customField, $_valueList);

			if ($_customField['usereditable'] == 0 && $_mode == MODE_EDIT) {
				$_customField['valuetype']  = 'static';
				$_customField['fieldvalue'] = GetStaticOptions($_optionsContainer);
			} else {
				$_customField['valuetype']  = 'field';
				$_customField['fieldvalue'] = EscapeOptions($_optionsContainer);
			}

			break;

		case kyCustomFieldDefinition::TYPE_DATE:
			$_customField['fieldtype']  = 'date';
			$_customField['fieldvalue'] = $_customfieldValue;

			if ($_customField['usereditable'] == 0 && $_mode == MODE_EDIT) {
				$_customField['valuetype'] = 'static';
			} else {
				$_customField['valuetype'] = 'field';
			}

			break;

		case kyCustomFieldDefinition::TYPE_FILE:
			$_customField['fieldtype'] = 'file';
			$_fileLink                 = '';

			if (!empty($_customfieldValue[0])) {
				$_fileLink = '<div><img src="' . IMAGE_URL . 'icon_file.gif" align="absmiddle" border="0" /> <a href="' . WHMCS_URL . '/modules/support/kayako/getcustomfiles.php?tid=' . $_GET['ticketid'] . '&field=' . $_customField['fieldname'] . '" target="_blank">' . $_customfieldValue[0] . '</a></div>';
			}

			if ($_customField['usereditable'] == 0 && $_mode == MODE_EDIT) {
				$_customField['valuetype']  = 'static';
				$_customField['fieldvalue'] = $_fileLink;
			} else {
				$_customField['valuetype']  = 'field';
				$_customField['fieldvalue'] = $_fileLink;
			}

			break;

		case kyCustomFieldDefinition::TYPE_CUSTOM:
			$_customField['fieldtype'] = 'custom';

			if ($_customField['usereditable'] == 0 && $_mode == MODE_EDIT) {
				$_customField['valuetype'] = 'static';
			} else {
				$_customField['valuetype'] = 'field';
			}

			break;
	}

	return $_customField;
}

/**
 * Download file
 *
 * @author Ruchi Kothari
 *
 * @param string $_fileName    File name
 * @param string $_fileContent File contents
 *
 * @return true
 */
function Download($_fileName, $_fileContent)
{
	if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) {
		// IE Bug in download name workaround
		@ini_set('zlib.output_compression', 'Off');
	}

	$_fileNameSplitted = explode('.', $_fileName);
	$_fileExtension    = $_fileNameSplitted[1];
	$_mimeContainer    = GetMimeData($_fileExtension);

	// Get the file extension
	if ($_mimeContainer && isset($_mimeContainer[0])) {
		header('Content-Type: ' . $_mimeContainer[0] . SWIFT_CRLF);
	} else {
		header('Content-Type: application/force-download');
	}

	header("Content-Disposition: attachment; filename=\"" . $_fileName . "\"");
	header("Content-Transfer-Encoding: binary");

	echo $_fileContent;

	return true;
}

/**
 * Formats a data size
 * Expects bytes, returns bytes/KiB/MiB/GiB
 *
 * @author Ryan M. Lederman
 *
 * @param int  $_size         The Size to Format
 * @param bool $_displayBytes Whether to Display Result as Bytes
 * @param int  $_decimal      The Decimal Pointer Location
 *
 * @return int The formatted size
 */
function FormattedSize($_size, $_displayBytes = false, $_decimal = 2)
{
	// Assumes input is bytes!
	if ($_size < 1024 && $_displayBytes == true) {
		// Bytes
		return number_format($_size, $_decimal) . " Bytes";
	} else {
		if ($_size < 1024 && $_displayBytes == false) {
			// KiB
			return number_format($_size / 1024, $_decimal) . " KB";
		} else if ($_size >= 1024 && $_size < 1048576) {
			// KiB
			return number_format($_size / 1024, $_decimal) . " KB";
		} else if ($_size >= 1048576 && $_size < 1073741824) {
			// MiB
			return number_format($_size / 1048576, $_decimal) . " MB";
		} else if ($_size >= 1073741824) {
			// GiB
			return number_format($_size / 1073741824, $_decimal) . " GB";
		}
	}

	return false;
}

/**
 * Get MIME data
 *
 * @author Ruchi Kothari
 *
 * @param string $_fileExtension File Extension
 *
 * @return array Mime data
 */
function GetMimeData($_fileExtension)
{
	$_mimeList = array(
		'pdf'  => array('application/pdf', 'mimeico_pdf.gif', 'PDF Document'),
		'png'  => array('image/x-png', 'mimeico_pic.gif', 'PNG Image'),
		'htm'  => array('text/html', 'mimeico_html.gif', 'HTML Page'),
		'html' => array('text/html', 'mimeico_html.gif', 'HTML Page'),
		'rtf'  => array('text/richtext', 'mimeico_text.gif', 'Rich Text'),
		'gz'   => array('application/x-gzip', 'mimeico_compressed.gif', 'GZIP File'),
		'tar'  => array('application/x-gzip', 'mimeico_compressed.gif', 'TAR File'),
		'zip'  => array('application/zip', 'mimeico_compressed.gif', 'ZIP File'),
		'rar'  => array('application/rar', 'mimeico_compressed.gif', 'RAR File'),
		'bz2'  => array('application/bz2', 'mimeico_compressed.gif', 'BZ2 File'),
		'bz'   => array('application/bz', 'mimeico_compressed.gif', 'BZ File'),
		'jar'  => array('application/java-archive', 'mimeico_compressed.gif', 'JAR File'),
		'doc'  => array('application/msword', 'mimeico_word.gif', 'MS Word Doc'),
		'docx' => array('application/msword', 'mimeico_word.gif', 'MS Word Doc'),
		'jpg'  => array('image/jpeg', 'mimeico_pic.gif', 'JPEG Image'),
		'jpeg' => array('image/jpeg', 'mimeico_pic.gif', 'JPEG Image'),
		'txt'  => array('text/plain', 'mimeico_text.gif', 'Text File'),
		'wav'  => array('audio/x-wav', 'mimeico_sound.gif', 'WAV File'),
		'mov'  => array('video/quicktime', 'mimeico_flick.gif', 'QuickTime Movie'),
		'ppt'  => array('application/powerpoint', 'mimeico_powerpoint.gif', 'PowerPoint Doc'),
		'pptx' => array('application/powerpoint', 'mimeico_powerpoint.gif', 'PowerPoint Doc'),
		'xls'  => array('application/vnd.ms-excel', 'mimeico_excel.gif', 'Excel File'),
		'xlsx' => array('application/vnd.ms-excel', 'mimeico_excel.gif', 'Excel File'),
		'ram'  => array('audio/x-realaudio', 'mimeico_flick.gif', 'Real Audio'),
		'ico'  => array('image/ico', 'mimeico_pic.gif', 'Icon File'),
		'gif'  => array('image/gif', 'mimeico_pic.gif', 'GIF Image'),
		'mpg'  => array('video/mpeg', 'mimeico_flick.gif', 'MPEG Video'),
		'mpeg' => array('video/mpeg', 'mimeico_flick.gif', 'MPEG Video'),
		'mp3'  => array('audio/x-mpeg', 'mimeico_sound.gif', 'MPEG Audio'),
		'php'  => array('text/plain', 'mimeico_script.gif', 'PHP Script File'),
		'swf'  => array('application/x-shockwave-flash', 'mimeico_flick.gif', 'Macromedia Flash File'),
	);

	return $_mimeList[$_fileExtension];
}

/**
 * User defined function for sorting
 *
 * @author Ruchi Kothari
 *
 * @param array $_element1 Record1 for comparision
 * @param array $_element2 Record2 for comparision
 *
 * @return int Comparision result
 */
function CompareRecords($_element1, $_element2, $_orderBy)
{
	if (is_numeric($_element1[$_orderBy]) && is_numeric($_element2[$_orderBy])) {
		if ($_element1[$_orderBy] == $_element2[$_orderBy]) return 0;

		return ($_element1[$_orderBy] > $_element2[$_orderBy]) ? -1 : 1;
	} else {
		return strcmp($_element1[$_orderBy], $_element2[$_orderBy]);
	}
}


/**
 * General sort function
 *
 * @author Ruchi Kothari
 *
 * @param array  $_records Array of records
 * @param string $_orderBy Order by field
 *
 * @return array $_records Array of sorted records
 */
function SortRecords($_records, $_orderBy)
{
	usort($_records, create_function('$_element1, $_element2', 'return CompareRecords($_element1, $_element2, ' . $_orderBy . ');'));

	return $_records;
}


/**
 * Retrieve substring
 *
 * @author Ruchi Kothari
 *
 * @param string $_string Base string
 * @param int    $_offset Starting point from where substring is required
 * @param int    $_limit  Number of characters for substring
 *
 * @return string $_string Substring
 */
function GetSubstr($_string, $_offset, $_limit)
{
	if (strlen($_string) > ($_limit - $_offset)) {
		$_string = substr($_string, $_offset, $_limit) . '...';
	}

	return $_string;
}

/**
 * Retrieve List of parent categories for Knowledgebase system
 *
 * @author Ruchi Kothari
 *
 * @param int   $_categoryID             Category ID
 * @param array $_knowledgebaseContainer Knowledgebase Container
 *
 * @return array $_parentCategoryList List of parent categories
 */
function RetreiveParentCategoryList($_categoryID, $_knowledgebaseContainer)
{
	$_knowledgebase = $_knowledgebaseContainer[$_categoryID];

	$_parentCategoryList = array();
	if (isset($_knowledgebase['parentkbcategoryid']) && $_knowledgebase['parentkbcategoryid'] != 0) {
		$_parentCategoryList = RetreiveParentCategoryList($_knowledgebase['parentkbcategoryid'], $_knowledgebaseContainer);
	}

	$_parentCategoryList[$_categoryID] = $_knowledgebase['title'];

	return $_parentCategoryList;
}

/**
 * Retrieve comments hierarchy
 *
 * @author Ruchi Kothari
 *
 * @param int   $_incomingParentCommentID Top most parent comment ID
 * @param array $_commentContainer        Array of comments
 * @param array $_finalCommentContainer   Array of comments in hierarchy
 * @param int   $_padding
 */
function RetrieveCommentHierarchy($_incomingParentCommentID, &$_commentContainer, &$_finalCommentContainer, $_padding = 0)
{
	foreach ($_commentContainer as $_comment) {
		$_parentCommentID = $_comment['parentcommentid'];

		if ($_parentCommentID != $_incomingParentCommentID || (!isset($_commentContainer[$_parentCommentID]) && $_parentCommentID != 0)) {
			continue;
		}

		$_finalCommentContainer[$_comment['id']]            = $_comment;
		$_finalCommentContainer[$_comment['id']]['padding'] = $_padding;

		RetrieveCommentHierarchy($_comment['id'], $_commentContainer, $_finalCommentContainer, ($_padding + 40));
	}
}

/**
 * Get the day without 0 in front
 *
 * @author Ruchi Kothari
 *
 * @param string $_day The Day
 *
 * @return int The Day
 */
function GetStrippedDay($_day)
{
	if (substr($_day, 0, 1) == '0') {
		return substr($_day, 1);
	}

	return $_day;
}