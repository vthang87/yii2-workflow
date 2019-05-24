<?php

use yii\db\Migration;

/**
 * Handles the creation of table `wf_transition`.
 */
class m180530_082827_create_wf_transition_table extends Migration
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

        $this->createTable('{{%wf_transition}}', [
            'id_workflow' => $this->integer(),
            'id_status_start' => $this->integer(),
            'id_status_end' => $this->integer(),
        ], $tableOptions);

        $this->addPrimaryKey(
            'wf_transition_pk',
            '{{%wf_transition}}',
            ['id_workflow', 'id_status_start', 'id_status_end']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%wf_transition}}');
    }
}
