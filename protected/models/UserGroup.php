<?php

/**
 * This is the model class for table "user_group".
 *
 * The followings are the available columns in table 'user_group':
 * @property integer $Id
 * @property string $description
 * @property integer $is_internal
 * @property integer $is_administrator
 * @property integer $can_read
 * @property integer $addressed
 * @property integer $need_confirmation
 * @property integer $can_feedback
 * @property integer $use_technical_docs
 *
 * The followings are the available model relations:
 * @property Album[] $albums
 * @property Multimedia[] $multimedias
 * @property Note[] $notes
 * @property User[] $users
 */
class UserGroup extends TapiaActiveRecord
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
			array('description', 'required'),
			array('is_internal, is_administrator, can_read, addressed, need_confirmation, can_feedback, use_technical_docs', 'numerical', 'integerOnly'=>true),
			array('description', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, description, is_internal, is_administrator, use_technical_docs', 'safe', 'on'=>'search'),
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
			'albums' => array(self::HAS_MANY, 'Album', 'Id_user_group_owner'),
			'multimedias' => array(self::HAS_MANY, 'Multimedia', 'Id_user_group'),
			'notes' => array(self::MANY_MANY, 'Note', 'user_group_note(Id_user_group, Id_note)'),
			'users' => array(self::HAS_MANY, 'User', 'Id_user_group'),
			'reviewTypes' => array(self::MANY_MANY, 'ReviewType', 'review_type_user_group(Id_user_group, Id_review_type)'),
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
			'is_administrator' => 'Es administrador',
			'can_read' => 'Puede leer por defecto',
			'addressed' => 'Direcci&oacute;n',
			'need_confirmation' => 'Necesita confirmaci&oacute;n por defecto',
			'can_feedback' => 'Puede dar feedback por defecto',
			'use_technical_docs' => 'Puede ver Documentos T&eacute;cnicos',
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
		$criteria->compare('is_administrator',$this->is_administrator);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}