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
 * @property integer $read
 * @property integer $Id_review_type
 * @property string $username
 * @property integer $Id_user_group
 * @property string $closing_description
 * @property integer $is_open
 * 
 * The followings are the available model relations:
 * @property Album[] $albums
 * @property Multimedia[] $multimedias
 * @property Note[] $notes
 * @property ReviewType $idReviewType
 * @property Customer $idCustomer
 *  @property Tag[] $tags
 * @property Wall[] $walls
 */
class Review extends CActiveRecord
{
	public $maxReview;

	public function beforeSave()
	{
		if($this->isNewRecord)
		{
			$this->Id_user_group = User::getCurrentUserGroup()->Id;
			$this->username = User::getCurrentUser()->username;
		}
		
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
			array('Id_customer,Id_review_type', 'required'),
			array('review, Id_customer, read, Id_review_type, is_open', 'numerical', 'integerOnly'=>true),
			array('description, creation_date, change_date, closing_description', 'safe'),		
			array('username', 'length', 'max'=>128),
			array('change_date','default',
				              'value'=>new CDbExpression('NOW()'),
				              'setOnEmpty'=>false,'on'=>'insert,update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, review, Id_customer, description,creation_date, change_date, read, Id_review_type, closing_description, is_open', 'safe', 'on'=>'search'),
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
			'reviewType' => array(self::BELONGS_TO, 'ReviewType', 'Id_review_type'),
			'customer' => array(self::BELONGS_TO, 'Customer', 'Id_customer'),
			'tags' => array(self::MANY_MANY, 'Tag', 'tag_review(Id_review, Id_tag)'),
			'walls' => array(self::HAS_MANY, 'Wall', 'Id_review'),
			'reviewUsers' => array(self::HAS_MANY, 'ReviewUser', 'Id_review'),
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
			'read' => 'Read',
			'Id_review_type' => 'Id Review Type',
			'closing_description' => 'Descripción de Cierre',			
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
		$criteria->compare('read',$this->read);
		$criteria->compare('Id_review_type',$this->Id_review_type);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('Id_user_group',$this->Id_user_group);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function hasResource($userGroupId, $multimediaType)
	{
		$sql = 'select * from multimedia_note mn inner join multimedia m on (mn.Id_multimedia = m.Id)';
		$sql .= ' where mn.Id_note IN (';
		$sql .= ' select ugn.Id_note from user_group_note ugn';
		$sql .= ' where ugn.Id_note IN (select n.Id from note n where n.Id_review = '.$this->Id.')';
		$sql .= ' and ugn.Id_user_group = '.$userGroupId.')';
		$sql .= ' and m.Id_multimedia_type = '.$multimediaType;
		
		$connection = Yii::app()->db;
		$command = $connection->createCommand($sql);
		$results = $command->queryAll();
		
		return sizeof($results) >0;
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

			$criteria->join =  	"LEFT OUTER JOIN multimedia m ON (m.Id_review=t.Id)
								inner join multimedia_note mn ON (mn.Id_multimedia = m.Id)";
			$criteria->addCondition("mn.Id_note IN(
									select ugn.Id_note from user_group_note ugn
									where ugn.Id_note IN (select n.Id from note n where n.Id_review = t.Id
									and ugn.Id_user_group = ". User::getCurrentUserGroup()->Id .")
									and m.Id_multimedia_type IN ( ".$arrFilters['typeFilter'] . "))");
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
		
		
		
		
		
		if(!User::getCurrentUserGroup()->is_administrator )
		{
			//$criteria->join .= ' INNER JOIN `review_user` `reviewUsers` ON (`reviewUsers`.`Id_review`=`t`.`Id`)';
			//$criteria->addCondition('reviewUsers.username = "'.User::getCurrentUser()->username.'"');				
			$criteria->join .= ' LEFT OUTER JOIN `note` `n` ON (`n`.`Id_review`=`t`.`Id`) 
								LEFT OUTER JOIN `user_group_note` `ugn` ON (`ugn`.`Id_note`=`n`.`Id`)';
			$criteria->addCondition('ugn.Id_user_group = '.User::getCurrentUserGroup()->Id);				
			$criteria->addCondition('t.username = "'. User::getCurrentUser()->username . '"','OR');
		}
		
		$criteria->addCondition('t.Id_customer = '. $this->Id_customer);
		$criteria->distinct = true;
		
		$criteria->order = 't.change_date DESC, t.review DESC';
			
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
	
	public function searchQuickView($arrFilters)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
	
		$criteria=new CDbCriteria;
	
		if($arrFilters['tagFilter'])
		$criteria->addCondition('t.Id IN(select Id_review from tag_review where Id_tag IN ('. $arrFilters['tagFilter'].'))');
	
		if($arrFilters['typeFilter'])
		{
	
			$criteria->join =  	"LEFT OUTER JOIN multimedia m ON (m.Id_review=t.Id)
									inner join multimedia_note mn ON (mn.Id_multimedia = m.Id)";
			$criteria->addCondition("mn.Id_note IN(
										select ugn.Id_note from user_group_note ugn
										where ugn.Id_note IN (select n.Id from note n where n.Id_review = t.Id
										and ugn.Id_user_group = ". User::getCurrentUserGroup()->Id .")
										and m.Id_multimedia_type IN ( ".$arrFilters['typeFilter'] . "))");
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
	
		
			
		if(!User::getCurrentUserGroup()->is_administrator )
		{
			//$criteria->join .= ' INNER JOIN `review_user` `reviewUsers` ON (`reviewUsers`.`Id_review`=`t`.`Id`)';
			//$criteria->addCondition('reviewUsers.username = "'.User::getCurrentUser()->username.'"');
			$criteria->join .= ' LEFT OUTER JOIN `note` `n` ON (`n`.`Id_review`=`t`.`Id`)
									LEFT OUTER JOIN `user_group_note` `ugn` ON (`ugn`.`Id_note`=`n`.`Id`)';
			$criteria->addCondition('ugn.Id_user_group = '.User::getCurrentUserGroup()->Id);
			$criteria->addCondition('t.username = "'. User::getCurrentUser()->username . '"','OR');
		}
	
		$criteria->addCondition('t.Id_customer = '. $this->Id_customer);
		$criteria->distinct = true;

		$criteria->order = ' t.Id_customer, t.change_date DESC, t.review DESC';
		$criteria->limit = 4;
			
		return new CActiveDataProvider($this, array(
					'criteria'=>$criteria,
		));
	}
}