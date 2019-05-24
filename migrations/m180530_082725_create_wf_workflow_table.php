<?php

use yii\db\Migration;

/**
 * Handles the creation of table `workflow`.
 */
class m180530_082725_create_wf_workflow_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%wf_workflow}}', [
            'id_workflow' => $this->primaryKey(),
            'name' => $this->string(),
            'model' => $this->string(),
            'column' => $this->string(),
            'init_status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%wf_workflow}}');
    }
}
