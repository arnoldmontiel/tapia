<?php

/**
 * This is the model class for table "tag_review_type".
 *
 * The followings are the available columns in table 'tag_review_type':
 * @property integer $Id_tag
 * @property integer $Id_review_type
 *
 * The followings are the available model relations:
 * @property Tag $tag
 * @property ReviwType $reviewType
 */
class TagReviewType extends TapiaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TagReviewType the static model class
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
		return 'tag_review_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_tag, Id_review_type', 'required'),
			array('Id_tag, Id_review_type', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id_tag, Id_review_type', 'safe', 'on'=>'search'),
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
			'tag' => array(self::BELONGS_TO, 'Tag', 'Id_tag'),
			'reviewType' => array(self::BELONGS_TO, 'ReviewType', 'Id_review_type'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_tag' => 'Id Tag',
			'Id_review_type' => 'Id Review Type',
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

		$criteria->compare('Id_tag',$this->Id_tag);
		$criteria->compare('Id_review_type',$this->Id_review_type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}