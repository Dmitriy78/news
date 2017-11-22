<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "files".
 *
 * @property integer $id
 * @property string $title
 * @property string $ext
 * @property integer $size
 * @property integer $owner_id
 *
 * @property News $owner
 */
class Files extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'ext', 'size', 'owner_id'], 'required'],
            [['size'], 'number'],
            [['owner_id'], 'integer'],
            [['title', 'ext'], 'string', 'max' => 255],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => News::className(), 'targetAttribute' => ['owner_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'ext' => 'Ext',
            'size' => 'Size',
            'owner_id' => 'Owner ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(News::className(), ['id' => 'owner_id']);
    }
}
