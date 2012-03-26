<?php

/**
 * This is the model class for table "wall".
 *
 * The followings are the available columns in table 'wall':
 * @property integer $Id
 * @property integer $Id_note
 * @property integer $Id_multimedia
 * @property integer $index_order
 * @property integer $Id_album
 * @property integer $Id_customer
 *
 * The followings are the available model relations:
 * @property Customer $idCustomer
 * @property Album $album
 * @property Multimedia $idMultimedia
 * @property Note $idNote
 */
class Wall extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Wall the static model class
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
		return 'wall';
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
			array('Id_note, Id_multimedia, index_order, Id_album, Id_customer', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, Id_note, Id_multimedia, index_order, Id_album, Id_customer', 'safe', 'on'=>'search'),
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
			'album' => array(self::BELONGS_TO, 'Album', 'Id_album'),
			'multimedia' => array(self::BELONGS_TO, 'Multimedia', 'Id_multimedia'),
			'note' => array(self::BELONGS_TO, 'Note', 'Id_note'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'Id_note' => 'Id Note',
			'Id_multimedia' => 'Id Multimedia',
			'index_order' => 'Index Order',
			'Id_album' => 'Album',
			'Id_customer' => 'Id Customer',
		);
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function searchOrderedByIndex()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('Id',$this->Id);
		$criteria->compare('Id_note',$this->Id_note);
		$criteria->compare('Id_multimedia',$this->Id_multimedia);
		$criteria->compare('index_order',$this->index_order);
		$criteria->compare('Id_album',$this->Id_album);
		$criteria->compare('Id_customer',$this->Id_customer);

		$criteria->order = 'index_order DESC';
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function searchOrderedByIndexSince($IdSince)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->addCondition('Id<'.$IdSince);
		$criteria->compare('Id_note',$this->Id_note);
		$criteria->compare('Id_multimedia',$this->Id_multimedia);
		$criteria->compare('index_order',$this->index_order);
		$criteria->compare('Id_album',$this->Id_album);
		$criteria->compare('Id_customer',$this->Id_customer);

		$criteria->order = 'index_order DESC';
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
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
		$criteria->compare('Id_note',$this->Id_note);
		$criteria->compare('Id_multimedia',$this->Id_multimedia);
		$criteria->compare('index_order',$this->index_order);
		$criteria->compare('Id_album',$this->Id_album);
		$criteria->compare('Id_customer',$this->Id_customer);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}