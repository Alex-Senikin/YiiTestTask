<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%offers}}`.
 */
class m241031_171007_create_offers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%offers}}', [
            'id' => $this->primaryKey(),
            'offerName' => $this->string()->notNull(),
            'email' => $this->string()->notNull()->unique(),
            'phoneNumber' => $this->bigInteger(),
            'createdAt' => $this->integer()->notNull()
        ]);

        $this->batchInsert('{{%offers}}',
        ['offerName', 'email', 'phoneNumber', 'createdAt'], [
            ['Первый оффер', 'offer1@mail.ru', '79999999999', strtotime('now')],
            ['Второй оффер', 'offer2@mail.ru', '78888888888', strtotime('now')],
            ['Третий оффер', 'offer3@mail.ru', '77777777777', strtotime('now')],
            ['Четвертый оффер', 'offer4@mail.ru', '76666666666', strtotime('now')],
            ['Пятый оффер', 'offer5@mail.ru', '75555555555', strtotime('now')],
            ['Шестой оффер', 'offer6@mail.ru', '74444444444', strtotime('now')],
            ['Седьмой оффер', 'offer7@mail.ru', '73333333333', strtotime('now')],
            ['Восьмой оффер', 'offer8@mail.ru', '72222222222', strtotime('now')],
            ['Девятый оффер', 'offer9@mail.ru', '71111111111', strtotime('now')],
            ['Десятый оффер', 'offer10@mail.ru', '71212121212', strtotime('now')],
            ['Одиннадцатый оффер', 'offer11@mail.ru', '72323232323', strtotime('now')],
            ['Двенадцатый оффер', 'offer12@mail.ru', '73434343434', strtotime('now')],
        ] );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%offers}}');
        $this->dropTable('{{%offers}}');
    }
}
