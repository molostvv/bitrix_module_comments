<?php
namespace Vspace\Comments\Entities;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\EnumField,
	Bitrix\Main\ORM\Fields\IntegerField;

Loc::loadMessages(__FILE__);

/**
 * Class VoteTable
 * 
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> VOTE unknown mandatory
 * <li> MESSAGE_ID int mandatory
 * <li> USER_ID int mandatory
 * </ul>
 *
 * @package Bitrix\Comments
 **/

class VoteTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'a_comments_vote';
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
					'title' => Loc::getMessage('VOTE_ENTITY_ID_FIELD')
				]
			),
			new EnumField(
				'VOTE',
				[
					'required' => true,
					'values' => array('LIKE', 'DISLIKE'),
					'title' => Loc::getMessage('VOTE_ENTITY_VOTE_FIELD')
				]
			),
			new IntegerField(
				'MESSAGE_ID',
				[
					'required' => true,
					'title' => Loc::getMessage('VOTE_ENTITY_MESSAGE_ID_FIELD')
				]
			),
			new IntegerField(
				'USER_ID',
				[
					'required' => true,
					'title' => Loc::getMessage('VOTE_ENTITY_USER_ID_FIELD')
				]
			),
		];
	}
}