<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%apples}}`.
 */
class m200203_081002_create_apples_table extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%apple_color}}', [
            'id' => $this->primaryKey(),
            'color' => $this->string()->notNull(),
        ]);

        $this->batchInsert('apple_color', ['id', 'color'], [
            [1, 'red'],
            [2, 'green'],
            [3, 'yellow'],
            [4, 'orange'],
            [5, 'blue'],
        ]);

        $this->createTable('{{%apples}}', [
            'id' => $this->primaryKey(),
            'color_id' => $this->integer()->notNull(),
            'created' => $this->dateTime()->null(),
            'fallen' => $this->dateTime()->null(),
            'eaten_percent' => $this->float()->notNull()->defaultValue(0),
        ]);

        $this->addForeignKey(
            'fk-apples-color_id',
            'apples',
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
            'fk-apples-color_id',
            'apples'
        );

        $this->dropTable('{{%apples}}');
        $this->dropTable('{{%apple_color}}');
    }
}
