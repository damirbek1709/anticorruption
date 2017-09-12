<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "comments".
 *
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $date
 * @property string $email
 * @property string $text
 */
class Comments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'text'], 'required'],
            [['date','news_id'], 'safe'],
            [['text'], 'string'],
            [['name', 'email'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'date' => Yii::t('app', 'Date'),
            'email' => Yii::t('app', 'Email'),
            'text' => Yii::t('app', 'Text'),
        ];
    }
}
