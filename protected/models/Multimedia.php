<?php

/**
 * This is the model class for table "multimedia".
 *
 * The followings are the available columns in table 'multimedia':
 * @property integer $Id
 * @property string $content
 * @property string $name
 * @property double $size
 * @property string $description
 * @property string $content_small
 * @property double $size_small
 * @property integer $Id_multimedia_type
 * @property integer $Id_customer
 * @property string $creation_date
 * @property integer $Id_album
 *
 * The followings are the available model relations:
 * @property Customer $customer
 * @property MultimediaType $idMultimediaType
 * @property Note[] $notes
 * @property Wall[] $walls
 */
class Multimedia extends CActiveRecord
{
	public $uploadedFile;
	
	public function beforeSave()
	{
	
		if($file=CUploadedFile::getInstance($this,'uploadedFile'))
		{
			if($this->name == null)
				$this->name=$file->name;
				
			if(strstr($file->type,'image'))
			{
				// set the new size
				// maximum dimension
				$newFile = $this->resizeFile(640,640,$file);
				$this->content = $newFile['content'];
				$this->size = $newFile['size'];
	
				$this->Id_multimedia_type = 1; // image
				
				//set the new small size
				$newFile = $this->resizeFile(150,150,$file);
				$this->content_small = $newFile['content'];
				$this->size_small = $newFile['size'];
	
				
			}
			elseif(strstr($file->type,'video')||$file->type=="application/octet-stream")//flash
			{
				$this->Id_multimedia_type = 2; // video
				$this->content = file_get_contents($file->tempName);
				$this->size = $file->size;
	
			}
		}
	
		return parent::beforeSave();
	}
	
	/**
	 * Returns an array with the new content and new size
	 * @param integer $w new width of file
	 * @param integer $d new width of file
	 * @param file $file the file to be modified
	 */
	private function resizeFile($w,$h,$file)
	{
		$im = imagecreatefromstring(file_get_contents($file->tempName));
	
		$size[0] = imagesx($im);
		$size[1] = imagesy($im);
		$newwidth = $size[0];
		$newheight = $size[1];
		//calculate the new dimensions respecting the original sizes
		if( $newwidth > $w ){
			$newheight = ($w / $newwidth) * $newheight;
			$newwidth = $w;
		}
		if( $newheight > $h ){
			$newwidth = ($h / $newheight) * $newwidth;
			$newheight = $h;
		}
		// create the new image
		$new = imagecreatetruecolor($newwidth, $newheight);
		// copy the image with new sizes
		imagecopyresampled($new, $im, 0, 0, 0, 0, $newwidth, $newheight, $size[0], $size[1]);
		ob_start();
		ob_implicit_flush(false);
	
		if( $file->type == 'image/jpeg' || $file->type == 'image/pjpeg' ) imagejpeg($new, '', 100);
		elseif ( $file->type == 'image/gif' ) imagegif($new);
		elseif (  $file->type == 'image/png' ) imagepng($new);
	
		return array('size'=>$newwidth*$newheight, 'content'=> ob_get_clean());
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Multimedia the static model class
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
		return 'multimedia';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_multimedia_type, Id_customer', 'required'),
			array('Id_multimedia_type, Id_customer, Id_album', 'numerical', 'integerOnly'=>true),
			array('size, size_small', 'numerical'),
			array('name', 'length', 'max'=>100),
			array('description', 'length', 'max'=>255),
			array('content, content_small, creation_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('content, name, size, description, content_small, size_small, Id_multimedia_type, Id_customer, creation_date, Id_album', 'safe', 'on'=>'search'),
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
			'idMultimediaType' => array(self::BELONGS_TO, 'MultimediaType', 'Id_multimedia_type'),
			'notes' => array(self::MANY_MANY, 'Note', 'multimedia_note(Id_multimedia, Id_note)'),
			'walls' => array(self::HAS_MANY, 'Wall', 'Id_multimedia'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'content' => 'Content',
			'name' => 'Name',
			'size' => 'Size',
			'description' => 'Description',
			'content_small' => 'Content Small',
			'size_small' => 'Size Small',
			'Id_multimedia_type' => 'Id Multimedia Type',
			'Id_customer' => 'Customer',
			'creation_date' => 'Creation Date',
			'Id_album' => 'Id Album',
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
		$criteria->compare('content',$this->content,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('size',$this->size);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('content_small',$this->content_small,true);
		$criteria->compare('size_small',$this->size_small);
		$criteria->compare('Id_multimedia_type',$this->Id_multimedia_type);
		$criteria->compare('Id_customer',$this->Id_customer);
		$criteria->compare('creation_date',$this->creation_date,true);
		$criteria->compare('Id_album',$this->Id_album);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}