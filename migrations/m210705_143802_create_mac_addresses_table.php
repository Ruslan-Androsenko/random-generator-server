<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%mac_addresses}}`.
 */
class m210705_143802_create_mac_addresses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%mac_addresses}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'ip_address_id' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull()->defaultValue(1),
            'attempts' => $this->integer()->notNull()->defaultValue(0),
        ]);

        $this->addForeignKey(
            'fk-mac_address-ip_address_id',
            '{{%mac_addresses}}',
            'ip_address_id',
            '{{%ip_addresses}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey('fk-mac_address-ip_address_id', '{{%mac_addresses}}');
        $this->dropTable('{{%mac_addresses}}');
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
