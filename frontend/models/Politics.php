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
                if ($this->title_en && $this->title_en) {
                    $this->text = $this->{"text_en"};
                    $this->title = $this->{"title_en"};
                } else {
                    $this->title = $this->{"title"} . "<span style=color:black> (This material is not availabe in English yet)</span>";
                    $this->text = $this->{"text"};
                }
                break;
            case "ky":
                if ($this->title_ky != null && $this->title_ky) {
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

    public function getCategory()
    {
        return $this->hasOne(Vocabulary::className(), ['id' => 'category_id']);
    }

    //for api
    public function fields()
    {
        $lang=Yii::$app->language;
        $fields = [
            'id',
            'title' => function ($model) use($lang){
                if($lang=='ky'){if($model->title_ky){$model->title=$model->title_ky;}}
                else if($lang=='en'){if($model->title_en){$model->title=$model->title_en;}}
                return $model->title;
            },
            'text' => function ($model) use($lang){
                if($lang=='ky'){if($model->text_ky){$model->text=$model->text_ky;}}
                else if($lang=='en'){if($model->text_en){$model->text=$model->text_en;}}
                return $model->text;
            },
            'category_id',
            'category_title' => function ($model) use($lang){
                $title="";
                if(!empty($model->category)){
                    if(!empty($model->category->value)){$title=$model->category->value;}
                    if($lang=='ky'){if(!empty($model->category->value_ky)){$title=$model->category->value_ky;}}
                    else if($lang=='en'){if(!empty($model->category->value_en)){$title=$model->category->value_en;}}
                }
                return $title;
            },
            'date',
        ];
        return $fields;
    }
}
