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
            'user_id'=>$this->integer(10)->notNull(),
            'title' => $this->string(50)->notNull(),
            'description' => $this->string()->notNull(),
            'name'=> $this->string(50)->notNull(),
            'status' => $this->string(10)->notNull(),
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
