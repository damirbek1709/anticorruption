<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "resistance".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $title_ky
 * @property string $text_ky
 * @property string $title_en
 * @property string $text_en
 */
class Resistance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resistance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text','category_id'], 'required'],
            [['title_ky', 'text_ky', 'title_en', 'text_en'], 'safe'],
            [['text', 'text_ky', 'text_en'], 'string'],
            [['title', 'title_ky', 'title_en'], 'string', 'max' => 255],
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
            'title_ky' => Yii::t('app', 'Заголовок на кыргызском'),
            'text_ky' => Yii::t('app', 'Текст на кыргызском'),
            'title_en' => Yii::t('app', 'Заголовок на английском'),
            'text_en' => Yii::t('app', 'Текст на английском'),
            'date' => Yii::t('app', 'Дата'),
            'category_id' => Yii::t('app', 'Категория'),
        ];
    }
}
