<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ip_addresses}}`.
 */
class m210705_143252_create_ip_addresses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%ip_addresses}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%ip_addresses}}');
    }

    /*
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
