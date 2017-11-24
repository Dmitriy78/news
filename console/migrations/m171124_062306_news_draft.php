<?php

use yii\db\Migration;

/**
 * Class m171124_062306_news_draft
 */
class m171124_062306_news_draft extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('news', 'title', $this->string()->null());
        $this->addColumn('news', 'draft', $this->boolean()->after('active')->defaultValue(1));
        
        $this->update('news', ['draft' => 0]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m171124_062306_news_draft cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171124_062306_news_draft cannot be reverted.\n";

        return false;
    }
    */
}
