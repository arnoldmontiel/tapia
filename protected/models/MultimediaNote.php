<?php

/**
 * This is the model class for table "multimedia_note".
 *
 * The followings are the available columns in table 'multimedia_note':
 * @property integer $Id_note
 * @property integer $Id_multimedia
 */
class MultimediaNote extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MultimediaNote the static model class
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
		return 'multimedia_note';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_note, Id_multimedia', 'required'),
			array('Id_note, Id_multimedia', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id_note, Id_multimedia', 'safe', 'on'=>'search'),
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
				'multimedia' => array(self::BELONGS_TO, 'Multimedia', 'Id_multimedia'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_note' => 'Id Note',
			'Id_multimedia' => 'Id Multimedia',
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

		$criteria->compare('Id_note',$this->Id_note);
		$criteria->compare('Id_multimedia',$this->Id_multimedia);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}