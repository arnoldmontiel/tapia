<?php

/**
 * This is the model class for table "note".
 *
 * The followings are the available columns in table 'note':
 * @property integer $Id
 * @property string $note
 * @property string $creation_date
 * @property integer $Id_customer
 * @property integer $Id_review
 * @property integer $in_progress
 * @property integer $need_confirmation
 * @property integer $confirmed
 *
 * The followings are the available model relations:
 * @property Review $idReview
 * @property Album[] $albums
 * @property Multimedia[] $multimedias
 * @property Customer $idCustomer
 * @property Wall[] $walls
 */
class Note extends CActiveRecord
{
	public function beforeSave()
	{
		if($this->review)
		{
			$modelReview = Review::model()->findByPk($this->review->Id);
			if($modelReview->read)
			{
				$modelReview->read = 0;
				$modelReview->save();
			}
		}
		
		return parent::beforeSave();
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Note the static model class
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
		return 'note';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_customer', 'required'),
			array('Id_customer, Id_review, in_progress, need_confirmation, confirmed', 'numerical', 'integerOnly'=>true),
			array('note, creation_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, note, creation_date, Id_customer, Id_review, in_progress, need_confirmation, confirmed', 'safe', 'on'=>'search'),
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
			'albums' => array(self::MANY_MANY, 'Album', 'album_note(Id_note, Id_album)'),
			'multimedias' => array(self::MANY_MANY, 'Multimedia', 'multimedia_note(Id_note, Id_multimedia)'),
			'idCustomer' => array(self::BELONGS_TO, 'Customer', 'Id_customer'),
			'walls' => array(self::HAS_MANY, 'Wall', 'Id_note'),
			'notes' => array(self::MANY_MANY, 'Note', 'note_note(Id_parent, Id_child)'),
			'review' => array(self::BELONGS_TO, 'Review', 'Id_review'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'note' => 'Note',
			'creation_date' => 'Creation Date',
			'Id_customer' => 'Id Customer',
			'Id_review' => 'Id Review',
			'in_progress' => 'In Progress',
			'need_confirmation' => 'Con confirmacion necesaria',
			'confirmed' => 'Confirmed',
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
		$criteria->compare('note',$this->note,true);
		$criteria->compare('creation_date',$this->creation_date,true);
		$criteria->compare('Id_customer',$this->Id_customer);
		$criteria->compare('Id_review',$this->Id_review);
		$criteria->compare('in_progress',0);
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}