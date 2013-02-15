<?php

/**
 * This is the model class for table "audit_login".
 *
 * The followings are the available columns in table 'audit_login':
 * @property integer $Id
 * @property string $username
 * @property string $date
 *
 * The followings are the available model relations:
 * @property User $username0
 */
class AuditLogin extends TapiaActiveRecord
{
	public $user_group_desc;
	public $user_name;
	public $user_last_name;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AuditLogin the static model class
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
		return 'audit_login';
	}

	public static function audit()
	{
		$model = new AuditLogin;
		$model->username = User::getCurrentUser()->username;
		$model->save();
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username', 'required'),
			array('username', 'length', 'max'=>128),
			array('date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, username, date, user_group_desc, user_name, user_last_name', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'username'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'username' => 'Usuario',
			'date' => 'Fecha',
			'user_group_desc'=>'Group de Usuario',
			'user_name'=>'Nombre', 
			'user_last_name'=>'Apellido',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('date',$this->date,true);
		
		$criteria->join =	" INNER JOIN user u on (t.username = u.username)";
		
		$criteria->addSearchCondition("u.last_name",$this->user_last_name);
		$criteria->addSearchCondition("u.name",$this->user_name);
		$criteria->addSearchCondition("u.Id_user_group",$this->user_group_desc);
		
		// Create a custom sort
		$sort=new CSort;
		$sort->attributes=array(									      
				      'date',
				      'username' => array(
					        'asc' => 't.username',
					        'desc' => 't.username DESC',
						),
					  'user_last_name' => array(
					        'asc' => 'u.last_name',
					        'desc' => 'u.last_name DESC',
						),
					  'user_name' => array(
					        'asc' => 'u.name',
					        'desc' => 'u.name DESC',
						),
					  'user_group_desc' => array(
					        'asc' => 'u.Id_user_group',
					        'desc' => 'u.Id_user_group DESC',
						),
			'*',
		);
		
		return new CActiveDataProvider($this, array(
											'criteria'=>$criteria,
											'sort'=>$sort,
		));
		
	}
}