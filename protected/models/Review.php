<?php

/**
 * This is the model class for table "review".
 *
 * The followings are the available columns in table 'review':
 * @property integer $Id
 * @property integer $review
 * @property integer $Id_customer
 * @property string $description
 * @property string $creation_date
 * @property string $change_date
 * @property integer $Id_priority
 * @property integer $read
 * The followings are the available model relations:
 * @property Album[] $albums
 * @property Multimedia[] $multimedias
 * @property Note[] $notes
 * @property Priority $idPriority
 * @property Customer $idCustomer
 * @property Tag[] $tags
 * @property Wall[] $walls
 */
class Review extends CActiveRecord
{
	public $maxReview;
	
	public function beforeSave()
	{
		$customer = User::getCustomer();
		if(!isset($customer))
			$this->read = 0;
		
		return parent::beforeSave();
	}
	
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
			array('Id_customer, Id_priority', 'required'),
			array('review, Id_customer, Id_priority, read', 'numerical', 'integerOnly'=>true),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, review, Id_customer, description, Id_priority', 'safe', 'on'=>'search'),
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
			'priority' => array(self::BELONGS_TO, 'Priority', 'Id_priority'),
			'customer' => array(self::BELONGS_TO, 'Customer', 'Id_customer'),
			'tags' => array(self::MANY_MANY, 'Tag', 'tag_review(Id_review, Id_tag)'),
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
			'review' => 'Revisión',
			'Id_customer' => 'Id Customer',
			'description' => 'Descripción',
			'creation_date' => 'Creation Date',
			'change_date' => 'Change Date',
			'Id_priority' => 'Id Priority',
			'read' => 'Read',
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
		$criteria->compare('description',$this->description,true);
		$criteria->compare('creation_date',$this->creation_date,true);
		$criteria->compare('change_date',$this->change_date,true);
		$criteria->compare('Id_priority',$this->Id_priority);
		$criteria->compare('read',$this->read);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function searchSummary($tagFilter=null, $typeFilter=null)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
	
		$criteria=new CDbCriteria;
		
		if($tagFilter)
			$criteria->addCondition('t.Id IN(select Id_review from tag_review where Id_tag IN ('. $tagFilter.'))');
		
		if($typeFilter)
		{
			$criteria->join =  	"LEFT OUTER JOIN multimedia m ON m.Id_review=t.Id";
			$criteria->addCondition('m.Id IN(select mj.Id from multimedia mj where mj.Id_multimedia_type IN ('. $typeFilter.') group by Id_review)');	
		}
		
		$criteria->addCondition('t.Id_customer = '. $this->Id_customer);
		$criteria->with[]='priority';
		
		$criteria->order = 't.read ASC, priority.level DESC , t.review DESC';
		
			
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
}