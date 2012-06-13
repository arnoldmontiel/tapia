<?php

/**
 * This is the model class for table "user_group_customer".
 *
 * The followings are the available columns in table 'user_group_customer':
 * @property integer $Id_user_group
 * @property integer $Id_customer
 * @property integer $Id_interest_power
 */
class UserGroupCustomer extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserGroupCustomer the static model class
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
		return 'user_group_customer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_user_group, Id_customer, Id_interest_power', 'required'),
			array('Id_user_group, Id_customer, Id_interest_power', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id_user_group, Id_customer, Id_interest_power', 'safe', 'on'=>'search'),
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
			'userGroup' => array(self::BELONGS_TO, 'UserGroup', 'Id_user_group'),
			'interestPower' => array(self::BELONGS_TO, 'InterestPower', 'Id_interest_power'),
			'customer' => array(self::BELONGS_TO, 'Customer', 'Id_customer'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_user_group' => 'Id User Group',
			'Id_customer' => 'Id Customer',
			'Id_interest_power' => 'Id Interest Power',
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
		$criteria->compare('Id_customer',$this->Id_customer);
		$criteria->compare('Id_interest_power',$this->Id_interest_power);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}