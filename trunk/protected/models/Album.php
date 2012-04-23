<?php

/**
 * This is the model class for table "album".
 *
 * The followings are the available columns in table 'album':
 * @property integer $Id
 * @property string $title
 * @property string $description
 * @property string $creation_date
 * @property integer $Id_customer
 * @property integer $Id_review
 * @property string $username
 * @property integer $Id_user_group_owner
 * 
 *
 * The followings are the available model relations:
 * @property User $username0
 * @property UserGroup $idUserGroupOwner
 * @property Review $idReview
 * @property Customer $idCustomer
 * @property Note[] $notes
 * @property Multimedia[] $multimedias
 */
class Album extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Album the static model class
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
		return 'album';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_customer, Id_review, username, Id_user_group_owner', 'required'),
			array('Id_customer, Id_review', 'numerical', 'integerOnly'=>true),
			array('title, username', 'length', 'max'=>45),
			array('creation_date, description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, title, description, creation_date, Id_customer, Id_review, username, Id_user_group_owner', 'safe', 'on'=>'search'),
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
			'review' => array(self::BELONGS_TO, 'Review', 'Id_review'),
			'customer' => array(self::BELONGS_TO, 'Customer', 'Id_customer'),
			'notes' => array(self::MANY_MANY, 'Note', 'album_note(Id_album, Id_note)'),
			'multimedias' => array(self::HAS_MANY, 'Multimedia', 'Id_album'),
			'userGroupOwner' => array(self::BELONGS_TO, 'UserGroup', 'Id_user_group_owner'),
			'username0' => array(self::BELONGS_TO, 'User', 'username'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'title' => 'Title',
			'description' => 'Description',
			'creation_date' => 'Creation Date',
			'Id_customer' => 'Id Customer',
			'Id_review' => 'Id Review',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('creation_date',$this->creation_date,true);
		$criteria->compare('Id_customer',$this->Id_customer);
		$criteria->compare('Id_review',$this->Id_review);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('Id_user_group_owner',$this->Id_user_group_owner);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}