<?php

/**
 * This is the model class for table "note_note".
 *
 * The followings are the available columns in table 'note_note':
 * @property integer $Id_parent
 * @property integer $Id_child
 *
 * The followings are the available model relations:
 * @property Note $idParent
 * @property Note $idChild
 */
class NoteNote extends CActiveRecord
{
	public function beforeSave()
	{	
		$modelReview = Review::model()->findByPk($this->idParent->Id_review);
		if($modelReview->read)
		{
			$modelReview->read = 0;
			$modelReview->save();
		}	
		
		return parent::beforeSave();
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return NoteNote the static model class
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
		return 'note_note';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_parent, Id_child', 'required'),
			array('Id_parent, Id_child', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id_parent, Id_child', 'safe', 'on'=>'search'),
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
			'idParent' => array(self::BELONGS_TO, 'Note', 'Id_parent'),//deprecated, use parent
			'idChild' => array(self::BELONGS_TO, 'Note', 'Id_child'),//deprecated, use child
			'parent' => array(self::BELONGS_TO, 'Note', 'Id_parent'),
			'child' => array(self::BELONGS_TO, 'Note', 'Id_child'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_parent' => 'Id Parent',
			'Id_child' => 'Id Child',
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

		$criteria->compare('Id_parent',$this->Id_parent);
		$criteria->compare('Id_child',$this->Id_child);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}