<?php

/**
 * This is the model class for table "user_customer".
 *
 * The followings are the available columns in table 'user_customer':
 * @property string $username
 * @property integer $Id_customer
 */
class UserCustomer extends CActiveRecord
{
	protected function afterSave()
	{
		parent::afterSave();
		
		$criteria=new CDbCriteria;
	

 		$criteria->condition= ' t.Id_customer = '.$this->Id_customer ;
 		$criteria->join =  	"LEFT OUTER JOIN note n ON n.Id=t.Id_note";
		$criteria->addSearchCondition("t.Id_user_group",$this->user->userGroup->Id);
 		$criteria->order = " n.Id_review";
		
		
		
		$modelUserGroupNote = UserGroupNote::model()->findAll($criteria);
		
		foreach($modelUserGroupNote as $item)
		{
			
			$modelReviewUserDb = ReviewUser::model()->findByPk(array('Id_review'=>$item->note->Id_review,'username'=>$this->username));
			if($modelReviewUserDb == null)
			{
				$modelReviewUser = new ReviewUser;
				$modelReviewUser->Id_review = $item->note->Id_review;
				$modelReviewUser->username = $this->username;
				$modelReviewUser->save();
			}
		}
		
	}
	
	public $user_group_desc;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserCustomer the static model class
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
		return 'user_customer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, Id_customer', 'required'),
			array('Id_customer', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('username, Id_customer, user_group_desc', 'safe', 'on'=>'search'),
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
			'customer' => array(self::BELONGS_TO, 'Customer', 'Id_customer'),
			'user' => array(self::BELONGS_TO, 'User', 'username'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'username' => 'Username',
			'Id_customer' => 'Id Customer',
			'user_group_desc'=> 'Grupo',
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
		$criteria->compare('Id_customer',$this->Id_customer);
		
		$criteria->join =  	"LEFT OUTER JOIN user u ON u.username=t.username
							LEFT OUTER JOIN user_group ug ON u.Id_user_group=ug.Id";
		$criteria->addSearchCondition("ug.description",$this->user_group_desc);
		
		$sort=new CSort;
		$sort->attributes=array(
				      'username',
				      'user_group_desc' => array(
				        'asc' => 'ug.description',
				        'desc' => 'ug.description DESC',
						),	
						'*',
		);
		
		return new CActiveDataProvider($this, array(
								'criteria'=>$criteria,
								'sort'=>$sort,
		));
	}
}