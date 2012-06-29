<?php

/**
 * This is the model class for table "customer".
 *
 * The followings are the available columns in table 'customer':
 * @property integer $Id
 * @property string $name
 * @property string $last_name
 * @property string $username
 * @property string $building_address
 * 
 * The followings are the available model relations:
 * @property User $user
 * @property Multimedia[] $multimedias
 * @property Note[] $notes
 */
class Customer extends CActiveRecord
{
	public $tag_description;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Customer the static model class
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
		return 'customer';
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
			array('name, last_name', 'length', 'max'=>45),
			array('username', 'length', 'max'=>128),
			array('building_address', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, name, last_name, username, building_address, tag_description', 'safe', 'on'=>'search'),
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
			'multimedias' => array(self::HAS_MANY, 'Multimedia', 'customer_Id'),
			'notes' => array(self::HAS_MANY, 'Note', 'Id_customer'),
		);
	}

	public function getCustomerDesc()
	{
		return $this->last_name .' - '. $this->name;
	}
	
	public function getTagDesc()
	{
		$criteria=new CDbCriteria;
		$criteria->select = "ta.description tag_description";
		$criteria->join =	" INNER JOIN review r on (t.Id = r.Id_customer)
										INNER JOIN (select max(Id) Id from review group by Id_customer) r2 on (r2.Id = r.Id)
												INNER JOIN review_type rt on (r.Id_review_type = rt.Id)
												INNER JOIN tag_review_type trt on (trt.Id_review_type = rt.Id)
												INNER JOIN tag_review tr on (tr.Id_tag = trt.Id_tag AND tr.Id_review = r.Id)
												INNER JOIN tag ta on (tr.Id_tag = ta.Id)";
		$criteria->addSearchCondition("t.Id",$this->Id);
		
		
		$model = Customer::model()->find($criteria);
		return $model->tag_description;
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'name' => 'Nombre',
			'last_name' => 'Apellido',
			'password' => 'Contrase&ntilde;a',
			'username' => 'Usuario',
			'address'=>'Direcci&oacute;n',
			'email'=>'Correo',
			'building_address' => 'Direcci&oacute;n de obra',
			'phone_house' => 'Tel&eacute;fono Casa',
			'phone_mobile' => 'Tel&eacute;fono M&oacute;vil',
			'description'=>'Observaciones',
			'tag_description'=>'Etapa',
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

		$criteria->compare('Id',$this->Id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('building_address',$this->building_address,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function searchInternal()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
	
		$criteria=new CDbCriteria;
	
		$criteria->compare('Id',$this->Id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('t.username',$this->username,true);
		$criteria->compare('building_address',$this->building_address,true);
	
		$criteria->join =	" LEFT JOIN review r on (t.Id = r.Id_customer)
								LEFT JOIN (select max(Id) Id from review group by Id_customer) r2 on (r2.Id = r.Id)
										LEFT JOIN review_type rt on (r.Id_review_type = rt.Id)
										LEFT JOIN tag_review_type trt on (trt.Id_review_type = rt.Id)
										LEFT JOIN tag_review tr on (tr.Id_tag = trt.Id_tag AND tr.Id_review = r.Id)
										LEFT JOIN tag ta on (tr.Id_tag = ta.Id)";
		$criteria->addSearchCondition("ta.description",$this->tag_description);		
		$criteria->distinct = true;
		// Create a custom sort
		$sort=new CSort;
		$sort->attributes=array(
							      'name',
							      'last_name',
							      'username' => array(
							        'asc' => 't.username',
							        'desc' => 't.username DESC',
								),
							      'building_address',
							      'tag_description' => array(
							        'asc' => 'ta.description',
							        'desc' => 'ta.description DESC',
		),
			'*',
		);
	
		return new CActiveDataProvider($this, array(
									'criteria'=>$criteria,
									'sort'=>$sort,
		));
	}
	
	public function searchNotInternal()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
	
		$criteria=new CDbCriteria;
	
		$criteria->compare('Id',$this->Id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('building_address',$this->building_address,true);
	
		$criteria->addCondition('t.Id IN(select Id_customer from user_customer where username = "'. User::getCurrentUser()->username.'")');
		
		$criteria->join =	" LEFT JOIN review r on (t.Id = r.Id_customer)
										LEFT JOIN (select max(Id) Id from review group by Id_customer) r2 on (r2.Id = r.Id)
												LEFT JOIN review_type rt on (r.Id_review_type = rt.Id)
												LEFT JOIN tag_review_type trt on (trt.Id_review_type = rt.Id)
												LEFT JOIN tag_review tr on (tr.Id_tag = trt.Id_tag AND tr.Id_review = r.Id)
												LEFT JOIN tag ta on (tr.Id_tag = ta.Id)";
		$criteria->addSearchCondition("ta.description",$this->tag_description);
		
		$criteria->distinct = true;
		
		// Create a custom sort
		$sort=new CSort;
		$sort->attributes=array(
									      'name',
									      'last_name',
									      'username' => array(
									        'asc' => 't.username',
									        'desc' => 't.username DESC',
		),
									      'building_address',
									      'tag_description' => array(
									        'asc' => 'ta.description',
									        'desc' => 'ta.description DESC',
		),
					'*',
		);
		
		return new CActiveDataProvider($this, array(
											'criteria'=>$criteria,
											'sort'=>$sort,
		));
		
	}
}