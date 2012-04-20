<?php

/**
 * This is the model class for table "user_group".
 *
 * The followings are the available columns in table 'user_group':
 * @property integer $Id
 * @property string $description
 * @property integer $can_create
 * @property integer $is_administrator
 *
 * The followings are the available model relations:
 * @property Note[] $notes
 * @property User[] $users
 */
class UserGroup extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserGroup the static model class
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
		return 'user_group';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('can_create, is_administrator', 'numerical', 'integerOnly'=>true),
			array('description', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, description, can_create, is_administrator', 'safe', 'on'=>'search'),
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
			'notes' => array(self::MANY_MANY, 'Note', 'user_group_note(Id_user_group, Id_note)'),
			'users' => array(self::HAS_MANY, 'User', 'Id_user_group'),
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
			'can_create' => 'Can Create',
			'is_administrator' => 'Is Administrator',
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
		$criteria->compare('can_create',$this->can_create);
		$criteria->compare('is_administrator',$this->is_administrator);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}