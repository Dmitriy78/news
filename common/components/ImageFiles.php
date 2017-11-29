<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Point;
use Imagine\Image\Box;

/**
 * Description of Files
 *
 * @author dima
 */
class ImageFiles extends Component
{
    const TYPE_OLDNAME = 1; // старое название файла
    const TYPE_MD5     = 2; // старое название файла в MD5
    const TYPE_TIME    = 3; // текущее время

    public $attribute = 'image';
    public $alias = '@frontend/';
    public $folder_upload = 'upload';
    public $folder_thumbs = 'thumbs';
    public $sep_postfix = 'x';
    public $extension = 'png';
    
    // создавать отдельную папку для сущности
    public $entity_folder = true;
    public $type_name;
    
    private $formName;

    /**
     * @brief Загрузка файла
     * @param Model $model
     * @param string $attribute
     */
    public function upload($model, $attribute = ''){
        $this->formName = strtolower($model->formName());
        $this->attribute = $attribute ? : $this->attribute;
        
        if(!$model->isNewRecord){
            $model->{$this->attribute} = $model->oldAttributes[$this->attribute];
        }
        
        $file = UploadedFile::getInstance($model, $this->attribute);
            
        if ($file && $file->tempName) {
            
            if(!$model->isNewRecord){
                $this->delete($model, $this->attribute, true);
            }
            
            if ($model->validate([$this->attribute])) {
                         
                // новое имя файла
                switch ($this->type_name) {
                    case self::TYPE_MD5     : $fileName = md5(uniqid(rand(),true)); break;
                    case self::TYPE_TIME    : $fileName = time(); break;
                    default                 : $fileName = $file->baseName;
                }
                
                // новое имя файла с расширением
                $fileName = implode('.', [$fileName, $file->extension]);
                
                if ($file->saveAs($this->getPathFile($fileName))) {
                    $model->{$this->attribute} = $fileName;
                } else {
                    $model->addError($this->attribute, 'Файл не загружен.');
                }
                
            }
        }
    }
    
    /**
     * @brief Путь к файлу
     * @param string $file
     * @return string
     */
    private function getPathFile($file) {
        $basePath = Yii::getAlias($this->alias . $this->folder_upload);
                
        // upload/ или upload/post/
        $dir = !$this->entity_folder ? $basePath . '/' : $basePath . "/{$this->formName}/";
        
        if(!file_exists($dir)) {
            mkdir($dir,0755,TRUE);
            chmod($dir, 0777);
        }
        
        return $dir . $file;
    }
    
    /**
     * @brief Путь к файлу thumb
     * @param string $file
     * @return type
     */
    private function getPathThumbs($file = false) {
        $dir =  Yii::getAlias($this->alias . $this->folder_upload) . "/{$this->folder_thumbs}/";
        
        if(!file_exists($dir)) {
            mkdir($dir,0755,TRUE);
            chmod($dir, 0777);
        }
        
        return $file ? $dir . $file : $dir;
    }

    /**
     * @brief Удаление файла
     * @param Model $model
     * @param string $attribute
     * @param bool $isChange
     */
    public function delete($model, $attribute = '', $isChange = false){
       
        $this->formName = strtolower($model->formName());
        
        $this->attribute = $attribute ? : $this->attribute;
        
        $fileName = !$isChange ?
                    $model->{$this->attribute} : $model->oldAttributes[$this->attribute];
        
        if (!$fileName) {
            return;
        }
        
        $file = $this->getPathFile($fileName);
        
        if(file_exists($file)){
            unlink($file);
            
            $arr = explode('.', $fileName);
            
            foreach(glob($this->getPathThumbs() . $arr[0].'_*.*') as $file) {
                if(file_exists($file)){
                    unlink($file);
                }
            }
           
            //$model->updateAttributes([$this->attribute => NULL]);
        }  
    }
    
    /**
     * @brief Путь к файлу
     * @param Model $model
     * @param integer $width
     * @param integer $height
     * @return string
     */
    public function src($model, $width = null, $height = null) {
        $this->formName = strtolower($model->formName());
        
        $image = $model->{$this->attribute};
        
        if (!$image) {
            return;
        }
        
        if (!$width && !$height) {
            return $this->getUrlFile($image);
        }
        
        $fileNameThumb = $this->makeFileNameThumb($image, $width, $height, $this->extension);
        
        if (!file_exists($this->getPathThumbs($fileNameThumb))) {
            
            if ($width && $height) {
                $cropimage = $this->CropImage($this->getPathFile($image), $width, $height);
            } else {
                $cropimage = $this->ResizeImage($this->getPathFile($image), $width); 
            }

            $cropimage = $cropimage->save($this->getPathThumbs($fileNameThumb), ['quality' => 90]);

        }
        
        return $this->getUrlFileThumb($fileNameThumb);
    }
    
    /**
     * @brief Ссылка на файл
     * @param type $formName
     * @return type
     * /upload/ || /upload/post
     */
    private function getUrlFile($file) {
        $path =  !$this->entity_folder ? 
                $this->folder_upload : $this->folder_upload . '/' . $this->formName;
        
        return '/' . $path . '/' . $file;
    }
    
    /**
     * @brief Ссылка на файл thumb
     * @return type
     * /upload/thumbs/
     */
    private function getUrlFileThumb($file) {
        return  '/' . $this->folder_upload . '/' . $this->folder_thumbs . '/' . $file;
    }

    /**
     * @brief Формирует имя файла thumb
     * @param type $image
     * @param type $width
     * @param type $height
     * @param type $ext
     * @return string
     * 1495379146_100x150.png || 1495379146_100.png
     */
    private function makeFileNameThumb($image, $width, $height, $ext = false) {
        $fileName = explode('.', $image);
        
        $arr = [
            $fileName[0],
            $this->getPostfix($width, $height)
        ];
        
        $newExt = $ext ? : $fileName[1];
        
        $file = implode('_', $arr) . '.' . $newExt;
        
        return $file;
    } 

    /**
     * @brief Постфикс для файла. 
     * @param integer $width
     * @param integer $height
     * @return string
     * 100X100
     */
    private function getPostfix($width, $height) {
        $postfix = $width ? : '';
        $postfix .= $height ? $this->sep_postfix . $height : '';
        
        return $postfix;
    }
    
    /**
     * @brief Изменение размера изображения
     * @param type $image
     * @param type $width
     * @param type $height
     * @return file
     */
    private function ResizeImage($image, $width, $height=null) {
        
        $cropimage =  Image::getImagine()->open($image);
        
        if ($width || $height) {
            
            $cropimage =  Image::getImagine()->open($image);
            $size = getimagesize($image);
            
            $width = ($size[0] > $width)? $width : $size[0];
        
            $x=0;
            $y=0;
            $w = $size[0];
            $h = $size[1];
//        print_r($width);
//        print_r($height);
        
            if ($width) {
                $wx = $width;
                $hx = round($wx*$h/$w);                
            }
            
            if ($height) {
                $hx = $height;
                $wx = round($hx*$w/$h);
                
            }
  
            $cropimage = $cropimage->resize(new Box($wx,$hx));            
        } 
        
        return $cropimage;
    }
    
    /**
     *  
     * @brief Возвращает объект Image кропнутой картинки 
     * @param object $image путь до оригинальной картинки   
     * @return Image object
     */      
    private function CropImage($image, $width, $height)            
    {
        $cropimage =  Image::getImagine()->open($image);
        
        $size = getimagesize($image);
  
        $width = ($size[0] > $width)? $width : $size[0];
        $height = ($size[1] > $height)? $height : $size[1];
//        $Wimage =  new Image();
//        $box = new Box($width, $height);
        
        $x=0;
        $y=0;
        $w = $size[0];
        $h = $size[1];
        $hx = $height;
        $wx = round($hx*$w/$h);
        
        if ($wx<$width) {
            $wx = $width; 
            $hx = round($wx*$h/$w);                 
            $y = round($hx/2-$height/2);                  
        } else {
            $x = round($wx/2-$width/2);                  
        }
        
        $point =  new Point($x, $y);
        $cropimage = $cropimage->resize(new Box($wx,$hx));
        $cropimage = $cropimage->crop($point, new Box($width, $height));        
      
        return $cropimage;
    }
    
}