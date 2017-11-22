<?php

use yii\db\Migration;

/**
 * Class m171121_143826_files
 */
class m171121_143826_files extends Migration
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
        
        $this->createTable('files', [
            'id'           => $this->primaryKey() ,
            'title'        => $this->string()->notNull(),
            'ext'          => $this->string()->notNull(),
            'size'         => $this->integer()->notNull(),
            'owner_id'     => $this->integer()->notNull(),
        ], $tableOptions);
        
        $this->addForeignKey('fk-news-files', '{{%files}}', 'owner_id', '{{%news}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m171121_143826_files cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171121_143826_files cannot be reverted.\n";

        return false;
    }
    */
}
