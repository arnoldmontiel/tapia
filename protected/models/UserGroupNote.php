<?php

/**
 * This is the model class for table "user_group_note".
 *
 * The followings are the available columns in table 'user_group_note':
 * @property integer $Id_user_group
 * @property integer $Id_note
 * @property integer $Id_customer
 * @property integer $can_read
 * @property integer $can_feedback
 * @property integer $addressed
 */
class UserGroupNote extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserGroupNote the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_group_note';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_user_group, Id_note, Id_customer', 'required'),
			array('Id_user_group, Id_note, Id_customer, can_read, can_feedback, addressed', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id_user_group, Id_note, Id_customer, can_read, can_feedback, addressed', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_user_group' => 'Id User Group',
			'Id_note' => 'Id Note',
			'Id_customer' => 'Id Customer',
			'can_read' => 'Can Read',
			'can_feedback' => 'Can Feedback',
			'addressed' => 'Addressed',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('Id_user_group',$this->Id_user_group);
		$criteria->compare('Id_note',$this->Id_note);
		$criteria->compare('Id_customer',$this->Id_customer);
		$criteria->compare('can_read',$this->can_read);
		$criteria->compare('can_feedback',$this->can_feedback);
		$criteria->compare('addressed',$this->addressed);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}