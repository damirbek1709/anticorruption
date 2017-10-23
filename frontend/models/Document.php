<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "document".
 *
 * @property integer $id
 * @property integer $title
 * @property string $text
 * @property string $date
 */
class Document extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document';
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'date', // update 1 attribute 'created' OR multiple attribute ['created','updated']
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'date', // update 1 attribute 'created' OR multiple attribute ['created','updated']
                ],
                'value' => function ($event) {
                    return date('Y-m-d H:i:s', strtotime($this->date));
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text', 'date','category_id'], 'required'],
            [['text'], 'string'],
            [['date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Заголовок'),
            'text' => Yii::t('app', 'Текст'),
            'date' => Yii::t('app', 'Дата'),
            'category_id' => Yii::t('app', 'Категория'),
        ];
    }
}
