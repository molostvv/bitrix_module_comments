<?php
namespace Vspace\Comments\Entities;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\DatetimeField,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\TextField;

Loc::loadMessages(__FILE__);

/**
 * Class MessagesTable
 * 
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> USER_ID int mandatory
 * <li> ITEM_ID int mandatory
 * <li> DATE_CREATE datetime mandatory
 * <li> MESSAGE text mandatory
 * </ul>
 *
 * @package Bitrix\Comments
 **/

class MessagesTable extends DataManager
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
		return [
			new IntegerField(
				'ID',
				[
					'primary' => true,
					'autocomplete' => true,
					'title' => Loc::getMessage('MESSAGES_ENTITY_ID_FIELD')
				]
			),
			new IntegerField(
				'USER_ID',
				[
					'required' => true,
					'title' => Loc::getMessage('MESSAGES_ENTITY_USER_ID_FIELD')
				]
			),
			new IntegerField(
				'ITEM_ID',
				[
					'required' => true,
					'title' => Loc::getMessage('MESSAGES_ENTITY_ITEM_ID_FIELD')
				]
			),
			new DatetimeField(
				'DATE_CREATE',
				[
					'required' => true,
					'title' => Loc::getMessage('MESSAGES_ENTITY_DATE_CREATE_FIELD')
				]
			),
			new TextField(
				'MESSAGE',
				[
					'required' => true,
					'title' => Loc::getMessage('MESSAGES_ENTITY_MESSAGE_FIELD')
				]
			),
		];
	}
}