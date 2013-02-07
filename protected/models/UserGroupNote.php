<?php

/**
 * This is the model class for table "user_group_note".
 *
 * The followings are the available columns in table 'user_group_note':
 * @property integer $Id_user_group
 * @property integer $Id_note
 * @property integer $Id_customer
 * @property integer $can_read
 * @property integer $can_feedback
 * @property integer $addressed
 * @property integer $need_confirmation
 * @property integer $confirmed
 * @property integer $declined
 * @property string $confirmation_date
 * @property string $request_confirmation_date
 */
class UserGroupNote extends CActiveRecord
{
	
	protected function beforeSave()
	{
		if($this->need_confirmation)
			$this->request_confirmation_date = new CDbExpression('NOW()');
		else
			$this->request_confirmation_date = null;
		
		return parent::beforeSave();
	}
	
	protected function afterSave()
	{
		parent::afterSave();
		
		//just to update change_date field
		$note = $this->note;
		$note->save();
	
		if($this->note->review)
		{
			//recorro todos los usuarios asignados al cliente
			$modelReview = Review::model()->findByPk($this->note->review->Id);
			$modelUserCustomer = UserCustomer::model()->findAllByAttributes(array('Id_customer'=>$this->Id_customer));
			foreach($modelUserCustomer as $item)
			{
				if($this->Id_user_group == $item->user->Id_user_group && $item->username != User::getCurrentUser()->username )
				{
					$modelReviewUserDb = ReviewUser::model()->findByPk(array('Id_review'=>$modelReview->Id,'username'=>$item->username));
					if($modelReviewUserDb)
					{
						$modelReviewUserDb->read = 0;
						$modelReviewUserDb->save();
					}
					else
					{
						$modelReviewUser = new ReviewUser;
						$modelReviewUser->Id_review = $modelReview->Id;
						$modelReviewUser->username = $item->username;
						$modelReviewUser->save();
					}
				}
			}
			
			
			//client
			if($this->customer->user->Id_user_group == $this->Id_user_group && $this->customer->user->username != User::getCurrentUser()->username)
			{
				$modelReviewUserDb = ReviewUser::model()->findByPk(array('Id_review'=>$modelReview->Id,'username'=>$this->customer->user->username));
				if($modelReviewUserDb)
				{
					$modelReviewUserDb->read = 0;
					$modelReviewUserDb->save();
				}
				else
				{
					$modelReviewUser = new ReviewUser;
					$modelReviewUser->Id_review = $modelReview->Id;
					$modelReviewUser->username = $this->customer->user->username;
					$modelReviewUser->save();
				}
			}
			
			if($modelReview)
			{
				$modelReview->save();
			}
		}
	}
	
	public function getDueDate()
	{
		$date = new DateTime($this->request_confirmation_date);
		$date->modify('+'.Setting::getDueDays().' day');
		return $date->format('Y-m-d');
	}
	
	public function getCloseDate()
	{
		$date = new DateTime($this->note->review->closing_date);		
		return $date->format('Y-m-d');
	}
	
	public function getConfirmDate()
	{
		$date = new DateTime($this->confirmation_date);
		return $date->format('Y-m-d');
	}
	
	public function isOutOfDate()
	{
		$outOfDate = false;
		if(isset($this->request_confirmation_date))
		{
			$todayDate = new DateTime();
				
			$date = new DateTime($this->request_confirmation_date);
			$date->modify('+'.Setting::getDueDays().' day');
				
			if($todayDate > $date)
				$outOfDate = true;
		}
		
		return $outOfDate;
	}
	
	public function isForceClose()
	{
		$forceClose = false;
		if(isset($this->request_confirmation_date)  && isset($this->note->review->closing_date))
		{			
			$closingDate = new DateTime($this->note->review->closing_date);
			
			$dueDate = new DateTime($this->request_confirmation_date);
			$dueDate->modify('+'.Setting::getDueDays().' day');
			
			if($dueDate >$closingDate)
				$forceClose = true;
		}
		
		return $forceClose;
	}
	
	public $Id_review = null;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserGroupNote the static model class
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
		return 'user_group_note';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_user_group, Id_note, Id_customer', 'required'),
			array('confirmation_date, request_confirmation_date', 'safe'),
			array('Id_user_group, Id_note, Id_customer, can_read, can_feedback,addressed, need_confirmation, confirmed, declined', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id_user_group, Id_note, Id_customer, can_read, can_feedback, addressed, need_confirmation, confirmed, declined, confirmation_date, request_confirmation_date', 'safe', 'on'=>'search'),
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
			'note' => array(self::BELONGS_TO, 'Note', 'Id_note'),
			'userGroup' => array(self::BELONGS_TO, 'UserGroup', 'Id_user_group'),
			'customer' => array(self::BELONGS_TO, 'Customer', 'Id_customer'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_user_group' => 'Id User Group',
			'Id_note' => 'Id Note',
			'Id_customer' => 'Id Customer',
			'can_read' => 'Can Read',
			'can_feedback' => 'Can Feedback',
			'addressed' => 'Addressed',
			'need_confirmation' => 'Need Confirmation',
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

		$criteria->compare('Id_user_group',$this->Id_user_group);
		$criteria->compare('Id_note',$this->Id_note);
		$criteria->compare('Id_customer',$this->Id_customer);
		$criteria->with[]='note';
		$criteria->compare('note.Id_review',$this->Id_review);	

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}