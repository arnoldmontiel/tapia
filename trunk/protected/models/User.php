<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $username
 * @property string $password
 * @property string $email
 * @property integer $Id_user_group
 * @property string $name
 * @property string $last_name
 * @property string $address
 * @property string $phone_house
 * @property string $phone_mobile
 * @property string $description
 * @property integer $send_mail
 *
 * The followings are the available model relations:
 * @property Album[] $albums
 * @property Customer[] $customers
 * @property Multimedia[] $multimedias
 * @property Note[] $notes
 * @property Review[] $reviews
 */
class User extends CActiveRecord
{
	public $userGroupDescription;
	public $building_address;

	protected function afterSave()
	{
		parent::afterSave();

		if($this->Id_user_group == 3)
		{
			$modelCustomerDb = Customer::model()->findByAttributes(array('username'=>$this->username));
			if($modelCustomerDb)
			{
				$modelCustomerDb->name = $this->name;
				$modelCustomerDb->last_name = $this->last_name;
				$modelCustomerDb->building_address = $this->building_address;
				$modelCustomerDb->save();
			}
			else
			{
				$modelCustomer = new Customer;
				$modelCustomer->name = $this->name;
				$modelCustomer->last_name = $this->last_name;
				$modelCustomer->username = $this->username;
				$modelCustomer->building_address = $this->building_address;
				$modelCustomer->save();
			}
		}
		
		//Asigno permisos de SRBAC si es nuevo el registro
		if($this->isNewRecord)
		{
			if($this->userGroup->is_administrator)
			{
				$modelAssignment = new Assignments();
				$modelAssignment->userid = $this->username;
				$modelAssignment->data = 's:0:""';
				$modelAssignment->itemname = "Administrator";
				$modelAssignment->save();
				
				$modelAssignment = new Assignments();
				$modelAssignment->userid = $this->username;
				$modelAssignment->data = 's:0:""';
				$modelAssignment->itemname = "Authority";
				$modelAssignment->save();
			}
			else
			{
				$modelAssignment = new Assignments();
				$modelAssignment->userid = $this->username;
				$modelAssignment->data = 's:0:""';
				$modelAssignment->itemname = "User";
				$modelAssignment->save();
			}
		}
	}

	static private $_customer = null;
	static private $_userGroup = null;
	static private $_user = null;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public static function isAdministartor()
	{
		return self::getCurrentUserGroup()->is_administrator;
	}
	public static function isInternal()
	{
		return self::getCurrentUserGroup()->is_internal;
	}
	public static function useTechnicalDocs()
	{
		return self::getCurrentUserGroup()->use_technical_docs;
	}
	public static function isOwnerOf($modelNote)
	{
		return self::getCurrentUserGroup()->Id==$modelNote->Id_user_group_owner;
	}

	public static function canCreate()
	{
		return (ReviewTypeUserGroup::model()->countByAttributes(
				array('Id_user_group'=>self::getCurrentUserGroup()->Id))> 0);
	}

	public static function getCustomer()
	{
		if(!isset(self::$_customer))
		{
			$user = User::model()->findByPk(Yii::app()->user->Id);
			if(isset($user)&&isset($user->customers[0]))
				self::$_customer = $user->customers[0];
		}
		return self::$_customer;
	}
	public static function getCurrentUserGroup()
	{
		if(!isset(self::$_userGroup))
		{
			$user = User::model()->findByPk(Yii::app()->user->Id);
			if(isset($user)&&isset($user->userGroup))
				self::$_userGroup = $user->userGroup;
		}
		return self::$_userGroup;
	}

	public static function getAdminUserGroupId()
	{
		$model = UserGroup::model()->findByAttributes(array('is_administrator'=>1));
		return $model->Id;
	}

	public static function getAdminUsername()
	{
		$model = User::model()->findByAttributes(array('Id_user_group'=>User::getAdminUserGroupId()));
		return $model->username;
	}

	public static function getCurrentUser()
	{
		if(!isset(self::$_user))
		{
			self::$_user = User::model()->findByPk(Yii::app()->user->Id);
		}
		return self::$_user;
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
				array('Id_user_group, send_mail', 'numerical', 'integerOnly'=>true),
				array('username, password, email', 'length', 'max'=>128),
				array('name, last_name, address', 'length', 'max'=>100),
				array('phone_house, phone_mobile', 'length', 'max'=>45),
				array('description', 'length', 'max'=>255),
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('username, password, email, Id_user_group, userGroupDescription, phone_house, phone_mobile, building_address, description, send_mail', 'safe', 'on'=>'search'),
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
				'userCustomers' => array(self::HAS_MANY, 'UserCustomer', 'username'),
				'customers' => array(self::HAS_MANY, 'Customer', 'username'),
				'notes' => array(self::HAS_MANY, 'Note', 'username'),
				'multimedias' => array(self::HAS_MANY, 'Multimedia', 'username'),
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
				'password' => 'Contrase&ntilde;a',
				'email' => 'Correo',
				'Id_user_group' => 'Grupo',
				'name' => 'Nombre',
				'last_name' => 'Apellido',
				'address' => 'Direcci&oacute;n',
				'userGroupDescription' => 'Grupo de usuario',
				'phone_house' => 'Tel&eacute;fono Casa',
				'phone_mobile' => 'Tel&eacute;fono M&oacute;vil',
				'description'=>'Observaciones',
				'send_mail'=>'Recive Correo',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone_house',$this->phone_house,true);
		$criteria->compare('phone_mobile',$this->phone_mobile,true);
		$criteria->compare('description',$this->description,true);		
		
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
	
	public function searchUnassigned($id_customer)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->distinct = true;
		
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('Id_user_group',$this->Id_user_group);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone_house',$this->phone_house,true);
		$criteria->compare('phone_mobile',$this->phone_mobile,true);
		$criteria->compare('description',$this->description,true);
		$criteria->join = 'LEFT OUTER JOIN `user_customer` `uc` ON (`t`.`username`=`uc`.`username`)
							INNER JOIN user_group ug ON (t.Id_user_group = ug.Id)';
		$criteria->addCondition('Id_user_group not in(1,3)');//clients (1) and administrators (3)
		//$criteria->addCondition('ug.is_internal = 0');
		
 		$criteria->addCondition('t.username not in (
 				select u.username from user u LEFT OUTER JOIN user_customer uc on (u.username = uc.username)
 				where uc.Id_customer = '.$id_customer.')');
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}

	public function searchAdmin()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);

		$criteria->compare('name',$this->name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone_house',$this->phone_house,true);
		$criteria->compare('phone_mobile',$this->phone_mobile,true);
		$criteria->compare('description',$this->description,true);
		
		$criteria->with[]='userGroup';
		$criteria->addSearchCondition("userGroup.description",$this->userGroupDescription);

		$criteria->condition = 'Id_user_group <> 3'; //client


		$sort=new CSort;
		$sort->attributes=array(
				// For each relational attribute, create a 'virtual attribute' using the public variable name
				'username',
				'password',
				'email',
				'name',
				'last_name',
				'address',
				'userGroupDescription' => array(
						'asc' => 'userGroup.description',
						'desc' => 'userGroup.description DESC',
				),
				'*',
		);

		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
				'sort'=>$sort,
		));
	}
}