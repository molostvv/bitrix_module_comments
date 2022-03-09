<?php
namespace Vspace\Comments\DataProviders;

use Bitrix\Main,
	Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

/**
 * Class MessagesTable
 * 
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> USER_ID int mandatory
 * <li> DATE_CREATE datetime mandatory
 * <li> MESSAGE string mandatory
 * </ul>
 *
 * @package Bitrix\Comments
 **/

class MessagesTable extends Main\Entity\DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'a_comments_messages';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return array(
			'ID' => array(
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true,
				'title' => Loc::getMessage('MESSAGES_ENTITY_ID_FIELD'),
			),
			'USER_ID' => array(
				'data_type' => 'integer',
				'required' => true,
				'title' => Loc::getMessage('MESSAGES_ENTITY_USER_ID_FIELD'),
			),
			'ITEM_ID' => array(
				'data_type' => 'integer',
				'required' => true,
				'title' => Loc::getMessage('MESSAGES_ENTITY_ITEM_ID_FIELD'),
			),
			'DATE_CREATE' => array(
				'data_type' => 'datetime',
				'required' => true,
				'title' => Loc::getMessage('MESSAGES_ENTITY_DATE_CREATE_FIELD'),
			),
			'MESSAGE' => array(
				'data_type' => 'text',
				'required' => true,
				'title' => Loc::getMessage('MESSAGES_ENTITY_MESSAGE_FIELD'),
			),
		);
	}
}