<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Offers".
 *
 * @property int $id
 * @property string $offerName
 * @property string $email
 * @property int|null $phoneNumber
 * @property int $createdAt
 */
class Offers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Offers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['offerName', 'email'], 'required'],
            [['phoneNumber', 'createdAt'], 'integer'],
            [['offerName', 'email'], 'string', 'max' => 255],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'offerName' => 'Offer Name',
            'email' => 'Email',
            'phoneNumber' => 'Phone Number',
            'createdAt' => 'Created At',
        ];
    }
}
