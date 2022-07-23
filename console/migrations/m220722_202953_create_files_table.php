<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%files}}`.
 */
class m220722_202953_create_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%files}}', [
            'id' => $this->primaryKey(),
            'user_id'=>$this->integer()->notNull(),
            'title' => $this->string()->notNull()->unique(),
            'description' => $this->string(32)->notNull(),
            'name'=> $this->string()->notNull(),
            'status' => $this->string()->notNull()->defaultValue('Private'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%files}}');
    }
}
