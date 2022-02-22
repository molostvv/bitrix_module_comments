<?php
namespace Demis\Comments\DataProviders;

use Bitrix\Main,
	Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

/**
 * Class UsersTable
 * 
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> FIRSTNAME string(50) mandatory
 * <li> LASTNAME string(50) mandatory
 * <li> EMAIL string(50) mandatory
 * <li> PHOTO int mandatory
 * <li> SOCIAL_ID int mandatory
 * <li> SOCIAL_TYPE string(50) mandatory
 * </ul>
 *
 * @package Bitrix\Comments
 **/

class UsersTable extends Main\Entity\DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'a_comments_users';
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
				'title' => Loc::getMessage('USERS_ENTITY_ID_FIELD'),
			),
			'FIRSTNAME' => array(
				'data_type' => 'string',
				'required' => true,
				'validation' => array(__CLASS__, 'validateFirstname'),
				'title' => Loc::getMessage('USERS_ENTITY_FIRSTNAME_FIELD'),
			),
			'LASTNAME' => array(
				'data_type' => 'string',
				'required' => true,
				'validation' => array(__CLASS__, 'validateLastname'),
				'title' => Loc::getMessage('USERS_ENTITY_LASTNAME_FIELD'),
			),
			'EMAIL' => array(
				'data_type' => 'string',
				'required' => true,
				'validation' => array(__CLASS__, 'validateEmail'),
				'title' => Loc::getMessage('USERS_ENTITY_EMAIL_FIELD'),
			),
			'PHOTO' => array(
				'data_type' => 'integer',
				'required' => true,
				'title' => Loc::getMessage('USERS_ENTITY_PHOTO_FIELD'),
			),
			'SOCIAL_ID' => array(
				'data_type' => 'integer',
				'required' => true,
				'title' => Loc::getMessage('USERS_ENTITY_SOCIAL_ID_FIELD'),
			),
			'SOCIAL_TYPE' => array(
				'data_type' => 'string',
				'required' => true,
				'validation' => array(__CLASS__, 'validateSocialType'),
				'title' => Loc::getMessage('USERS_ENTITY_SOCIAL_TYPE_FIELD'),
			),
		);
	}
	/**
	 * Returns validators for FIRSTNAME field.
	 *
	 * @return array
	 */
	public static function validateFirstname()
	{
		return array(
			new Main\Entity\Validator\Length(null, 50),
		);
	}
	/**
	 * Returns validators for LASTNAME field.
	 *
	 * @return array
	 */
	public static function validateLastname()
	{
		return array(
			new Main\Entity\Validator\Length(null, 50),
		);
	}
	/**
	 * Returns validators for EMAIL field.
	 *
	 * @return array
	 */
	public static function validateEmail()
	{
		return array(
			new Main\Entity\Validator\Length(null, 50),
		);
	}
	/**
	 * Returns validators for SOCIAL_TYPE field.
	 *
	 * @return array
	 */
	public static function validateSocialType()
	{
		return array(
			new Main\Entity\Validator\Length(null, 50),
		);
	}
}