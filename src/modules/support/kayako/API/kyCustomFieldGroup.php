<?php
/**
 * Kayako Custom Field Group object.
 *
 * @author Ruchi Kothari
 *
 * @since Kayako version 4.51.1891
 * @package Object\CustomFieldGroup
 */
class kyCustomFieldGroup extends kyObjectBase
{
	const GROUP_USER = 1; // User Registration
	const GROUP_USERORGANIZATION = 2; // User Organization
	const GROUP_LIVECHATPRE = 10; // Live Chat (Pre Chat)
	const GROUP_LIVECHATPOST = 11; // Live Chat (Post Chat)
	const GROUP_STAFFTICKET = 3; // Staff Ticket Creation
	const GROUP_USERTICKET = 4; // User Ticket Creation
	const GROUP_STAFFUSERTICKET = 9; // Staff & User Ticket Creation
	const GROUP_TIMETRACK = 5; // Ticket Time Tracking
	const GROUP_KNOWLEDGEBASE = 12; // Knowledgebase Articles
	const GROUP_NEWS = 13; // News Items
	const GROUP_TROUBLESHOOTER = 14; // Troubleshooter Items
	const GROUP_DOWNLOADS = 15; // Downloads Items

	static protected $controller = '/Base/CustomFieldGroup';
	static protected $object_xml_name = 'customfieldgroup';

	private $grouptype;
	private $title;
	private $id;
	private $visibilitytype;
	private $displayorder;

	protected function parseData($data)
	{
		$this->id             = intval($data['_attributes']['customfieldgroupid']);
		$this->visibilitytype = intval($data['_attributes']['visibilitytype']);
		$this->displayorder   = intval($data['_attributes']['displayorder']);
		$this->title          = $data['_attributes']['title'];
		$this->grouptype      = $data['_attributes']['grouptype'];
	}

	public function getId($complete = false)
	{
		return $complete ? array($this->id) : $this->id;
	}

	/**
	 * Returns title of the custom field group.
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Returns group type of the custom field group.
	 *
	 * @return string
	 * @filterBy
	 * @orderBy
	 */
	public function getGroupType()
	{
		return $this->grouptype;
	}

	/**
	 * Returns visibility type of the custom field group.
	 *
	 * @return string
	 * @filterBy
	 * @orderBy
	 */
	public function getVisibilityType()
	{
		return $this->visibilitytype;
	}

	/**
	 * Returns display order of the custom field group.
	 *
	 * @return string
	 */
	public function getDisplayOrder()
	{
		return $this->displayorder;
	}


	public function toString()
	{
		return sprintf("%s (type: %s, module: %s)", $this->getTitle(), $this->getType(), $this->getModule());
	}
}