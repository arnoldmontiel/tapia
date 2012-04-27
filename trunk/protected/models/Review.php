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
 * @property integer $Id_review_type
 * The followings are the available model relations:
 * @property Album[] $albums
 * @property Multimedia[] $multimedias
 * @property Note[] $notes
 * @property Priority $idPriority
 * @property ReviewType $idReviewType
 * @property Customer $idCustomer
 *  @property Tag[] $tags
 * @property Wall[] $walls
 */
class Review extends CActiveRecord
{
	public $maxReview;

	
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
			array('Id_customer, Id_priority,Id_review_type', 'required'),
			array('review, Id_customer, Id_priority, read, Id_review_type', 'numerical', 'integerOnly'=>true),
			array('description, creation_date, change_date', 'safe'),		
			array('change_date','default',
				              'value'=>new CDbExpression('NOW()'),
				              'setOnEmpty'=>false,'on'=>'insert,update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, review, Id_customer, description,creation_date, change_date, Id_priority, read, Id_review_type', 'safe', 'on'=>'search'),
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
			'reviewType' => array(self::BELONGS_TO, 'ReviewType', 'Id_review_type'),
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
			'Id_review_type' => 'Id Review Type',
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
		$criteria->compare('Id_review_type',$this->Id_review_type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function searchSummary($arrFilters)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
	
		$criteria=new CDbCriteria;
		
		if($arrFilters['tagFilter'])
			$criteria->addCondition('t.Id IN(select Id_review from tag_review where Id_tag IN ('. $arrFilters['tagFilter'].'))');
		
		if($arrFilters['typeFilter'])
		{
			$criteria->join =  	"LEFT OUTER JOIN multimedia m ON m.Id_review=t.Id";
			$criteria->addCondition('m.Id IN(select mj.Id from multimedia mj where mj.Id_multimedia_type IN ('. $arrFilters['typeFilter'].') group by Id_review)');	
		}
		
		if($arrFilters['reviewTypeFilter'])
		{
			$criteria->addCondition('t.Id_review_type IN ('. $arrFilters['reviewTypeFilter'].')');
		}
		
		if($arrFilters['dateFromFilter'])
		{
			$criteria->addCondition('t.creation_date >= "'. date("Y-m-d H:i:s",strtotime($arrFilters['dateFromFilter'])) . '"');
		}

		if($arrFilters['dateToFilter'])
		{
			$criteria->addCondition('t.creation_date <= "'. date("Y-m-d H:i:s",strtotime($arrFilters['dateToFilter'] . " + 1 day")) . '"');
		}
		
		$criteria->addCondition('t.Id_customer = '. $this->Id_customer);
		$criteria->with[]='priority';
		
		$criteria->order = 't.change_date DESC, priority.level DESC , t.review DESC';
		
			
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
}