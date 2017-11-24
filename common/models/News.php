<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $image
 * @property integer $active
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $count_attach
 */
class News extends ActiveRecord
{
    public $attach;
    public $count_attach;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }
    
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
//                'value' => new Expression('NOW()'),
                'attributes' => array(
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at'
                ),
            ],
        ];
    }    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['text'], 'string'],
            [['active', 'created_at', 'updated_at'], 'integer'],
            [['title', 'image'], 'string', 'max' => 255],
            [['attach', 'count_attach'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'text' => 'Основной текст',
            'image' => 'Изображение',
            'active' => 'Активно',
            'attach' => 'Файлы',
            'count_attach' => 'Кол-во файлов',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    /**
     * @inheritdoc
     * @return \common\models\Query\NewsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\Query\NewsQuery(get_called_class());
    }
    
//    public function afterFind() {
//        parent::afterFind();
//        
//        $this->count_attach = $this->filesCount;
//    }

        /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(Files::className(), ['owner_id' => 'id']);
    }
    
}
