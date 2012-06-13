<?php

/**
 * This is the model class for table "interest_power".
 *
 * The followings are the available columns in table 'interest_power':
 * @property integer $Id
 * @property string $description
 * @property integer $can_read
 * @property integer $addressed
 * @property integer $need_confirmation
 * @property integer $can_feedback
 */
class InterestPower extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return InterestPower the static model class
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
		return 'interest_power';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('can_read, addressed, need_confirmation, can_feedback', 'numerical', 'integerOnly'=>true),
			array('description', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, description, can_read, addressed, need_confirmation, can_feedback', 'safe', 'on'=>'search'),
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
			'Id' => 'ID',
			'description' => 'Description',
			'can_read' => 'Can Read',
			'addressed' => 'Addressed',
			'need_confirmation' => 'Need Confirmation',
			'can_feedback' => 'Can Feedback',
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

		$criteria->compare('Id',$this->Id);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('can_read',$this->can_read);
		$criteria->compare('addressed',$this->addressed);
		$criteria->compare('need_confirmation',$this->need_confirmation);
		$criteria->compare('can_feedback',$this->can_feedback);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}