<?php
namespace Vspace\Comments\Entities;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\StringField,
	Bitrix\Main\ORM\Fields\Validators\LengthValidator;

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
 * <li> SOCIAL_PROVIDER string(50) mandatory
 * <li> SOCIAL_PROFILE_URL string(50) mandatory
 * </ul>
 *
 * @package Bitrix\Comments
 **/

class UsersTable extends DataManager
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
		return [
			new IntegerField(
				'ID',
				[
					'primary' => true,
					'autocomplete' => true,
					'title' => Loc::getMessage('USERS_ENTITY_ID_FIELD')
				]
			),
			new StringField(
				'FIRSTNAME',
				[
					'required' => true,
					'validation' => [__CLASS__, 'validateFirstname'],
					'title' => Loc::getMessage('USERS_ENTITY_FIRSTNAME_FIELD')
				]
			),
			new StringField(
				'LASTNAME',
				[
					'required' => true,
					'validation' => [__CLASS__, 'validateLastname'],
					'title' => Loc::getMessage('USERS_ENTITY_LASTNAME_FIELD')
				]
			),
			new StringField(
				'EMAIL',
				[
					'required' => true,
					'validation' => [__CLASS__, 'validateEmail'],
					'title' => Loc::getMessage('USERS_ENTITY_EMAIL_FIELD')
				]
			),
			new IntegerField(
				'PHOTO',
				[
					'required' => true,
					'title' => Loc::getMessage('USERS_ENTITY_PHOTO_FIELD')
				]
			),
			new IntegerField(
				'SOCIAL_ID',
				[
					'required' => true,
					'title' => Loc::getMessage('USERS_ENTITY_SOCIAL_ID_FIELD')
				]
			),
			new StringField(
				'SOCIAL_PROVIDER',
				[
					'required' => true,
					'validation' => [__CLASS__, 'validateSocialProvider'],
					'title' => Loc::getMessage('USERS_ENTITY_SOCIAL_PROVIDER_FIELD')
				]
			),
			new StringField(
				'SOCIAL_PROFILE_URL',
				[
					'required' => true,
					'validation' => [__CLASS__, 'validateSocialProfileUrl'],
					'title' => Loc::getMessage('USERS_ENTITY_SOCIAL_PROFILE_URL_FIELD')
				]
			),
		];
	}

	/**
	 * Returns validators for FIRSTNAME field.
	 *
	 * @return array
	 */
	public static function validateFirstname()
	{
		return [
			new LengthValidator(null, 50),
		];
	}

	/**
	 * Returns validators for LASTNAME field.
	 *
	 * @return array
	 */
	public static function validateLastname()
	{
		return [
			new LengthValidator(null, 50),
		];
	}

	/**
	 * Returns validators for EMAIL field.
	 *
	 * @return array
	 */
	public static function validateEmail()
	{
		return [
			new LengthValidator(null, 50),
		];
	}

	/**
	 * Returns validators for SOCIAL_PROVIDER field.
	 *
	 * @return array
	 */
	public static function validateSocialProvider()
	{
		return [
			new LengthValidator(null, 50),
		];
	}

	/**
	 * Returns validators for SOCIAL_PROFILE_URL field.
	 *
	 * @return array
	 */
	public static function validateSocialProfileUrl()
	{
		return [
			new LengthValidator(null, 50),
		];
	}
}