<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $username
 * @property string $password
 * @property string $email
 * @property integer $Id_user_group
 *
 * The followings are the available model relations:
 * @property Customer[] $customers
 * @property Note[] $notes
 * @property UserGroup $idUserGroup
 */
class User extends CActiveRecord
{
	private $_customer = null;
	private $_userGroup = null;
	private $_user = null;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function getCustomer()
	{
		if(!isset($_customer))
		{
			$user = User::model()->findByPk(Yii::app()->user->Id);
			if(isset($user)&&isset($user->customers[0]))
				$_customer = $user->customers[0];				
		}
		return $_customer;		
	}
	public static function getCurrentUserGroup()
	{
		if(!isset($this->_userGroup))
		{
			$user = User::model()->findByPk(Yii::app()->user->Id);
			if(isset($user)&&isset($user->userGroup))
				$this->_userGroup = $user->userGroup;				
		}
		return $this->_userGroup;		
	}
	public static function getCurrentUser()
	{
		if(!isset($this->_user))
		{
			$this->_user = User::model()->findByPk(Yii::app()->user->Id);
		}
		return $this->_user;		
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, email, Id_user_group', 'required'),
			array('Id_user_group', 'numerical', 'integerOnly'=>true),
			array('username, password, email', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('username, password, email, Id_user_group', 'safe', 'on'=>'search'),
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
			'customers' => array(self::HAS_MANY, 'Customer', 'username'),
			'notes' => array(self::HAS_MANY, 'Note', 'username'),
			'userGroup' => array(self::BELONGS_TO, 'UserGroup', 'Id_user_group'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'username' => 'Usuario',
			'password' => 'Contraseña',
			'email' => 'Email',
			'Id_user_group' => 'Id User Group',
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

		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('Id_user_group',$this->Id_user_group);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}