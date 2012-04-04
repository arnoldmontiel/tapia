<?php

/**
 * This is the model class for table "review".
 *
 * The followings are the available columns in table 'review':
 * @property integer $Id
 * @property integer $review
 * @property integer $Id_customer
 *
 * The followings are the available model relations:
 * @property Album[] $albums
 * @property Multimedia[] $multimedias
 * @property Note[] $notes
 * @property Customer $idCustomer
 * @property Wall[] $walls
 */
class Review extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Review the static model class
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
		return 'review';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_customer', 'required'),
			array('review, Id_customer', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, review, Id_customer', 'safe', 'on'=>'search'),
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
			'albums' => array(self::HAS_MANY, 'Album', 'Id_review'),
			'multimedias' => array(self::HAS_MANY, 'Multimedia', 'Id_review'),
			'notes' => array(self::HAS_MANY, 'Note', 'Id_review'),
			'idCustomer' => array(self::BELONGS_TO, 'Customer', 'Id_customer'),
			'walls' => array(self::HAS_MANY, 'Wall', 'Id_review'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'review' => 'Review',
			'Id_customer' => 'Id Customer',
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
		$criteria->compare('review',$this->review);
		$criteria->compare('Id_customer',$this->Id_customer);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}