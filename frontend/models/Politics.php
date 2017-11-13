<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "politics".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $title_ky
 * @property string $text_ky
 * @property string $title_en
 * @property string $text_en
 * @property integer $category_id
 * @property string $date
 */
class Politics extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'politics';
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

    function afterFind()
    {
        if ($this->scenario == 'search') {
            $this->translate(Yii::$app->language);
        }
    }

    function translate($language)
    {
        switch ($language) {
            case "en":
                if ($this->title_en && $this->description_en) {
                    $this->text = $this->{"text_en"};
                    $this->title = $this->{"title_en"};
                } else {
                    $this->title = $this->{"title"} . "<span style=color:black> (This material is not availabe in English yet)</span>";
                    $this->text = $this->{"text"};
                }
                break;
            case "ky":
                if ($this->title_ky != null && $this->description_ky) {
                    $this->text = $this->{"text_ky"};
                    $this->title = $this->{"title_ky"};
                } else {
                    $this->title = $this->{"title"} . "<span style=color:black> (Кыргыз тилинде бул материал азырынча жеткиликтүү эмес)</span>";
                    $this->text = $this->{"text"};
                }
                break;
            default:
                $this->text = $this->{"text"};
                $this->title = $this->{"title"};
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text'], 'required'],
            [[ 'date','category_id','title_ky','text_ky','title_en','text_en'], 'safe'],
            [['text'], 'string']
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
