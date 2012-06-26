<?php

/**
 * This is the model class for table "review_type".
 *
 * The followings are the available columns in table 'review_type':
 * @property integer $Id
 * @property string $description
 * @property integer $is_internal
 * @property integer $is_for_client
 *
 * The followings are the available model relations:
 * @property Review[] $reviews
 * @property Tag[] $tags
 */
class ReviewType extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ReviewType the static model class
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
		return 'review_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('description', 'required'),
			array('is_internal, is_for_client', 'numerical', 'integerOnly'=>true),
			array('description', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, description, is_internal, is_for_client', 'safe', 'on'=>'search'),
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
			'reviews' => array(self::HAS_MANY, 'Review', 'Id_review_type'),
			'tags' => array(self::MANY_MANY, 'Tag', 'tag_review_type(Id_review_type, Id_tag)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'description' => 'Descripci&oacute;n',
			'is_internal' => 'Es interno',
			'is_for_client'=> 'Es para cliente',
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
		$criteria->compare('is_internal',$this->is_internal);
		$criteria->compare('is_for_client',$this->is_for_client);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}