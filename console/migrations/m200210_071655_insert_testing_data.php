<?php

use yii\db\Migration;

/**
 * Class m200210_071655_add_testing_data
 */
class m200210_071655_insert_testing_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%user}}', [
            'id' => '1',
            'username' => 'Tester',
            'auth_key' => 'zKdALokOyLTphXdmNQiSOMM4VFva1RZ3',
            'access_token' => 'zKdALokOyLTphXdmNQiSOMM4VFva1RZ3',
            'password_hash' => '$2y$13$50CErppxAWg5S7P0EZKhS.EIvX2ryUjMA/9rxBA1LRCHCDFicMnS6', // password is 111111
            'password_reset_token' => null,
            'email' => 'tester@example.com',
            'status' => 10,
            'created_at' => '1580750242',
            'updated_at' => '1580750242',
            'verification_token' => null,
        ]);

        $this->batchInsert('{{%apple_color}}', ['id', 'user_id', 'color'],
            [
                [1, 1, 'red'],
                [2, 1, 'green'],
                [3, 1, 'yellow'],
                [4, 1, 'orange'],
                [5, 1, 'blue'],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%apple}}');
        $this->delete('{{%apple_color}}');
        $this->delete('{{%user}}');

        return false;
    }
}
