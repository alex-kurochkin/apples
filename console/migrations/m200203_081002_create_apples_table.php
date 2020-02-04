<?php

use yii\db\Migration;

class m200203_081002_create_apples_table extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%apple_color}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'color' => $this->string()->notNull(),
        ]);

        $this->createTable('{{%apple}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'color_id' => $this->integer()->notNull(),
            'eaten_percent' => $this->float()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->null(),
            'fallen_at' => $this->dateTime()->null(),
        ]);

        $this->addForeignKey(
            'fk-apple-user_id',
            'apple',
            'user_id',
            'user',
            'id'
        );

        $this->addForeignKey(
            'fk-apple_color-user_id',
            'apple_color',
            'user_id',
            'user',
            'id'
        );

        $this->addForeignKey(
            'fk-apple-color_id',
            'apple',
            'color_id',
            'apple_color',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-apple-user_id',
            'apple'
        );

        $this->dropForeignKey(
            'fk-apple_color-user_id',
            'apple_color'
        );

        $this->dropForeignKey(
            'fk-apple-color_id',
            'apple'
        );

        $this->dropTable('{{%apple}}');
        $this->dropTable('{{%apple_color}}');
    }
}
