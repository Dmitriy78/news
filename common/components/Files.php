<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\web\UploadedFile;
use common\models\Files as Attach;

/**
 * Description of Files
 *
 * @author dima
 */
class Files extends Component
{
    const TYPE_OLDNAME = 1; // старое название файла
    const TYPE_MD5     = 2; // старое название файла в MD5
    const TYPE_TIME    = 3; // текущее время
    
    public $attribute = 'attach';
    public $alias = '@frontend/';
    public $folder_upload = 'upload';
    
    // создавать отдельную папку для сущности
    public $entity_folder = false;
    public $type_name;
    
    private $formName;
    
    /**
     * Загрузка файла
     * @param Model $model
     * @param string $attribute
     */
    public function upload($model, $attribute, $name){
        $this->formName = strtolower($model->formName());
        $this->attribute = $attribute ? : $this->attribute;
        
        $files = UploadedFile::getInstances($model, $this->attribute);
        
        foreach ($files as $file) {
            
            if ($file && $file->tempName) {
            
                // новое имя файла
                switch ($this->type_name) {
                    case self::TYPE_MD5     : $fileName = md5(uniqid(rand(),true)); break;
                    case self::TYPE_TIME    : $fileName = time(); break;
                    default                 : $fileName = $this->getUniqueName($file->baseName, $file->extension);
                }
                
                // новое имя файла с расширением
                $fileName = implode('.', [$fileName, $file->extension]);

                if($file->saveAs($this->getPathFile($fileName))) {
                    $attach = new Attach();
                    $attach->title = $fileName;
                    $attach->ext = $file->extension;
                    $attach->size = $file->size;
                    $attach->link($name, $model);
                }
            }
        }
        
        return true;
    }
    
    /**
     * Уникальное имя файла
     * @param string $fileName
     * @param string $extension
     * @return string
     */
    public function getUniqueName($fileName, $extension) {
        
        if ( $this->checkName($fileName, $extension) ) {
            return $fileName;
        } else {
            for ($suffix = 2; !$this->checkName($newFileName = $fileName . '-' . $suffix, $extension); $suffix++) {}
            return $newFileName;
        }
    }
    
    /**
     * Наличие файла в базе
     * @param string $fileName
     * @param string $extension
     * @return boolean
     */
    public function checkName($fileName, $extension) {
        
        $model = Attach::find()
                ->select('id')
                ->where(['title' => implode('.', [$fileName, $extension])])
                ->one(); 
                
        return  !$model;
    }

        /**
     * Путь к файлу
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
     * Ссылка на прикрепленный файл
     * @param type $file
     * @return string
     */
    public function getUrlFile($file) {
        return '/' . $this->folder_upload . '/' . $file;
    }
    
    /**
     * 
     * @param type $model
     * @return boolean
     */
    public function delete($model) {
        $result = false;
        
        $file = $this->getPathFile($model->title);
        
        if(file_exists($file)){
            unlink($file);
            $result = true;
        }
        
        return $result;
    }
    
    /**
     * 
     * @param File $model
     */
    public function deleteAttaches($model) {
        foreach ($model->files as $file) {
            $this->delete($file);
        }
    }
}
