<?php

/**
 * This is the model class for table "multimedia".
 *
 * The followings are the available columns in table 'multimedia':
 * @property integer $Id
 * @property string $file_name
 * @property double $size
 * @property string $description
 * @property string $file_name_small
 * @property double $size_small
 * @property integer $Id_multimedia_type
 * @property integer $Id_customer
 * @property string $creation_date
 * @property integer $Id_album
 *
 * The followings are the available model relations:
 * @property Album $idAlbum
 * @property Customer $idCustomer
 * @property MultimediaType $idMultimediaType
 * @property Note[] $notes
 * @property Wall[] $walls
 */
class Multimedia extends CActiveRecord
{
	
	public $uploadedFile;
	
	
	public function beforeSave()
	{
	
		if(isset($this->uploadedFile))
		{
			if(strstr($this->uploadedFile["type"],'image'))
			{
				//save original
				move_uploaded_file($this->uploadedFile["tmp_name"],"images/" . $this->uploadedFile["name"]);
				
				$folder = "images/";
				$filePath = $folder . $this->uploadedFile["name"];
				
				$uniqueId = uniqid();
				
				//generate medium file version
				$newFile = $this->resizeFile(800,800,$filePath);
				$content = $newFile['content'];
				if ($content !== false) {
					$fileName = $uniqueId.'.jpg';
					$file = fopen($folder.$fileName, 'w');
					fwrite($file,$content);
					fclose($file);
					$this->file_name = $fileName;
					$this->size = $newFile['size'];
					$this->width= $newFile['width'];
					$this->height= $newFile['height'];
				}

				//generate small file version
				$newFile = $this->resizeFile(320,320,$filePath);
				$content = $newFile['content'];
				if ($content !== false) {
					$fileName = $uniqueId.'_small.jpg';
					$file = fopen($folder.$fileName, 'w');
					fwrite($file,$content);
					fclose($file);
					$this->file_name_small = $fileName;
					$this->size_small = $newFile['size'];
					$this->width_small = $newFile['width'];
					$this->height_small = $newFile['height'];
				}
				unlink($filePath);
				$this->Id_multimedia_type = 1;
	
			}
			elseif(strstr($this->uploadedFile["type"],'video')||$this->uploadedFile["type"]=="application/octet-stream")//flash
			{
				$this->Id_multimedia_type = 2;
			}
			else {
				$uniqueId = uniqid();
				
				$folder = "docs/";
				$fileName = $uniqueId.'.pdf';
				$filePath = $folder . $fileName;
				
				//save pdf
				move_uploaded_file($this->uploadedFile["tmp_name"],$filePath);
				
				$this->file_name = $fileName;
				$this->size =$this->uploadedFile["size"];
				
				
				$template = new Imagick();
				//$template->setResolution(100, 100); //Skip this to generate PDF, results in poor quality though
				$template->readImage($filePath.'[0]');
				$template->setImageFormat("pdf");
				//Doing some manipulation here but it doesn’t have any effect on the problem.
				$template->setImageCompressionQuality(100);
				
				$folder = "images/";
				$fileName = $uniqueId.'_tmp.jpg';
				$filePath = $folder . $fileName;
				
				//save small image
				$template->writeImages($filePath, true); //Works fine, high quality png
				
				$newFile = $this->resizeFile(320,320,$filePath);
				$content = $newFile['content'];
				if ($content !== false) {
					$fileName = $uniqueId.'_small.jpg';
					$file = fopen($folder.$fileName, 'w');
					fwrite($file,$content);
					fclose($file);
					$this->file_name_small = $fileName;
					$this->size_small = $newFile['size'];
					$this->width_small = $newFile['width'];
					$this->height_small = $newFile['height'];
				}
				unlink($filePath);
			
				
			}
		}
	
		return parent::beforeSave();
	}
	private function resizeFile($w=null,$h=null,$filePath)
	{
		$im = imagecreatefromstring(file_get_contents($filePath));
	
		$size[0] = imagesx($im);
		$size[1] = imagesy($im);
		$newwidth = $size[0];
		$newheight = $size[1];
		
		//calculate the new dimensions respecting the original sizes
		if( isset($w) && $newwidth > $w ){
			$newheight = ($w / $newwidth) * $newheight;
			$newwidth = $w;
		}
		if( isset($h) &&  $newheight > $h ){
			$newwidth = ($h / $newheight) * $newwidth;
			$newheight = $h;
		}
		// create the new image
		$new = imagecreatetruecolor($newwidth, $newheight);
		// copy the image with new sizes
		imagecopyresampled($new, $im, 0, 0, 0, 0, $newwidth, $newheight, $size[0], $size[1]);
		ob_start();
		ob_implicit_flush(false);
	
		imagejpeg($new, '', 100);
	
	
		return array('size'=>$newwidth*$newheight, 'content'=> ob_get_clean(),'width'=>$newwidth,'height'=>$newheight);
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
			array('file_name, description, file_name_small', 'length', 'max'=>255),
			array('creation_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, file_name, size, description, file_name_small, size_small, Id_multimedia_type, Id_customer, creation_date, Id_album', 'safe', 'on'=>'search'),
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
			'idAlbum' => array(self::BELONGS_TO, 'Album', 'Id_album'),
			'idCustomer' => array(self::BELONGS_TO, 'Customer', 'Id_customer'),
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
			'file_name' => 'File Name',
			'size' => 'Size',
			'description' => 'Description',
			'file_name_small' => 'File Name Small',
			'size_small' => 'Size Small',
			'Id_multimedia_type' => 'Id Multimedia Type',
			'Id_customer' => 'Id Customer',
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
		$criteria->compare('file_name',$this->file_name,true);
		$criteria->compare('size',$this->size);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('file_name_small',$this->file_name_small,true);
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