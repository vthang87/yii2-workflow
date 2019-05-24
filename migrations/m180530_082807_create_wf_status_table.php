<?php

use yii\db\Migration;

/**
 * Handles the creation of table `wf_status`.
 */
class m180530_082807_create_wf_status_table extends Migration
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
        $this->createTable('{{%wf_status}}', [
            'id_status' => $this->primaryKey(),
            'id_workflow' => $this->integer(),
            'position' => $this->integer(),
            'label' => $this->string(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%wf_status}}');
    }
}
