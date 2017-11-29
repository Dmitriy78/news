<?php

namespace common\models\Query;

/**
 * This is the ActiveQuery class for [[\common\models\News]].
 *
 * @see \common\models\News
 */
class NewsQuery extends \yii\db\ActiveQuery
{
    /**
     * @brief Активные записи
     * @return type
     */
    public function active()
    {
        return $this->andWhere(['active' => true]);
    }
    
    /**
     * @brief Не черновик
     * @return type
     */
    public function notDraft()
    {
        return $this->andWhere(['draft' => \common\models\News::NOT_DRAFT]);
    }
    
    /**
     * @brief Черновик
     * @return type
     */
    public function draft()
    {
        return $this->andWhere(['draft' => \common\models\News::IS_DRAFT]);
    } 

    /**
     * @inheritdoc
     * @return \common\models\News[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\News|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
