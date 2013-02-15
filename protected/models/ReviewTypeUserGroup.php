<?php

/**
 * This is the model class for table "review_type_user_group".
 *
 * The followings are the available columns in table 'review_type_user_group':
 * @property integer $Id_review_type
 * @property integer $Id_user_group
 */
class ReviewTypeUserGroup extends TapiaActiveRecord
{
	
	public $review_type_desc;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ReviewTypeUserGroup the static model class
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
		return 'review_type_user_group';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_review_type, Id_user_group', 'required'),
			array('Id_review_type, Id_user_group', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id_review_type, Id_user_group, review_type_desc', 'safe', 'on'=>'search'),
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
			'reviewType' => array(self::BELONGS_TO, 'ReviewType', 'Id_review_type'),
			'userGroup' => array(self::BELONGS_TO, 'UserGroup', 'Id_user_group'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_review_type' => 'Id Review Type',
			'Id_user_group' => 'Id User Group',
			'review_type_desc'=>'Agrupadores asignados',
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

		$criteria->compare('Id_review_type',$this->Id_review_type);
		$criteria->compare('Id_user_group',$this->Id_user_group);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}