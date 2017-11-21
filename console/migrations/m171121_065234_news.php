<?php

use yii\db\Migration;

/**
 * Class m171121_065234_news
 */
class m171121_065234_news extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('news', [
            'id'            => $this->primaryKey() ,
            'title'         => $this->string()->notNull(),
            'text'          => $this->text(),
            'image'         => $this->string(),
            'active'        => $this->boolean()->defaultValue(true),
            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m171121_065234_news cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171121_065234_news cannot be reverted.\n";

        return false;
    }
    */
}
