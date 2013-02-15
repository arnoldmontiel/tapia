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
 * @property integer $width
 * @property integer $height
 * @property integer $width_small
 * @property integer $height_small
 * @property integer $Id_review
 * @property string $username
 * @property integer $Id_user_group
 * 
 * The followings are the available model relations:
 * @property Album $idAlbum
 * @property User $user
 * @property UserGroup $idUserGroup
 * @property Customer $idCustomer
 * @property MultimediaType $idMultimediaType
 * @property Review $idReview
 * @property Note[] $notes
 * @property Wall[] $walls
 */
class Multimedia extends TapiaActiveRecord
{
	
	public $uploadedFile;
	public $last;
	
	public function beforeSave()
	{
		$this->username = User::getCurrentUser()->username;
		$this->Id_user_group = User::getCurrentUserGroup()->Id;
		if(isset($this->uploadedFile))
		{
			$ext = end(explode(".", $this->uploadedFile["name"]));
			$ext = strtolower($ext);
			if($ext=="jpg"||$ext=="png"||$ext=="bmp"||$ext=="gif")
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
				move_uploaded_file($this->uploadedFile["tmp_name"],"images/hola2");
				if ($content !== false) {
				move_uploaded_file($this->uploadedFile["tmp_name"],"images/hola3");
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
				$this->Id_multimedia_type = 1; //image
	
			}
			else 
			{
				switch ( $ext) {
					case "pdf":
						$this->Id_multimedia_type = 3; //pdf
						break;
					case "dwg":
						$this->Id_multimedia_type = 4; //autocad
						break;
					case "avi":
						$this->Id_multimedia_type = 2; //video
						break;
					case "doc":
						$this->Id_multimedia_type = 5; //word
						break;
					case "docx":
						$this->Id_multimedia_type = 5; //word
						break;
					case "xls":
						$this->Id_multimedia_type = 6; //excel
						break;
					case "xlsx":
						$this->Id_multimedia_type = 6; //excel
						break;
				}
				
				
				$uniqueId = uniqid();	
				
				
				$folder = "docs/";
				$fileName = $this->customer->last_name . '_' . $this->customer->name . '_' . $uniqueId.'.'.$ext;
				$filePath = $folder . $fileName;
				
				//save doc
				move_uploaded_file($this->uploadedFile["tmp_name"],$filePath);
				
				$this->file_name = $fileName;
				$this->size =$this->uploadedFile["size"];
				
//***************************************** To save pdf preview ****************************************************************				
// 				$template = new Imagick();
// 				//$template->setResolution(100, 100); //Skip this to generate PDF, results in poor quality though
// 				$template->readImage($filePath.'[0]');
// 				$template->setImageFormat("pdf");
// 				//Doing some manipulation here but it doesn�t have any effect on the problem.
// 				$template->setImageCompressionQuality(100);
				
// 				$folder = "images/";
// 				$fileName = $uniqueId.'_tmp.jpg';
// 				$filePath = $folder . $fileName;
				
// 				//save small image
// 				$template->writeImages($filePath, true); //Works fine, high quality png
				
// 				$newFile = $this->resizeFile(320,320,$filePath);
// 				$content = $newFile['content'];
// 				if ($content !== false) {
// 					$fileName = $uniqueId.'_small.jpg';
// 					$file = fopen($folder.$fileName, 'w');
// 					fwrite($file,$content);
// 					fclose($file);
// 					$this->file_name_small = $fileName;
// 					$this->size_small = $newFile['size'];
// 					$this->width_small = $newFile['width'];
// 					$this->height_small = $newFile['height'];
// 				}

//				unlink($filePath);
//***************************************************************************************************************************										
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
			array('Id_customer', 'required'),
			array('Id_multimedia_type, Id_customer, Id_album, width, height, width_small, height_small, Id_review, Id_user_group, Id_document_type', 'numerical', 'integerOnly'=>true),
			array('size, size_small', 'numerical'),
			array('username', 'length', 'max'=>128),
			array('file_name, description, file_name_small', 'length', 'max'=>255),
			array('creation_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, file_name, size, description, file_name_small, size_small, Id_multimedia_type, Id_customer, creation_date, Id_album, Id_review, Id_document_type', 'safe', 'on'=>'search'),
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
			'album' => array(self::BELONGS_TO, 'Album', 'Id_album'),
			'user' => array(self::BELONGS_TO, 'User', 'username'),
			'idUserGroup' => array(self::BELONGS_TO, 'UserGroup', 'Id_user_group'),
			'customer' => array(self::BELONGS_TO, 'Customer', 'Id_customer'),
			'multimediaType' => array(self::BELONGS_TO, 'MultimediaType', 'Id_multimedia_type'),
			'documentType' => array(self::BELONGS_TO, 'DocumentType', 'Id_document_type'),
			'review' => array(self::BELONGS_TO, 'Review', 'Id_review'),
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
			'width' => 'Width',
			'height' => 'Height',
			'width_small' => 'Width Small',
			'height_small' => 'Height Small',
			'Id_review' => 'Id Review',
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
		$criteria->compare('Id_review',$this->Id_review);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('Id_user_group',$this->Id_user_group);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}