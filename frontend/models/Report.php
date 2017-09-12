<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\Vocabulary;

/**
 * This is the model class for table "report".
 *
 * @property integer $id
 * @property string $title
 * @property string $date
 * @property integer $views
 * @property string $author
 * @property integer $user_id
 * @property integer $authority_id
 * @property integer $category_id
 * @property double $lon
 * @property double $lat
 * @property integer $city_id
 * @property string $text
 * @property integer $anonymous
 * @property string $email
 * @property string $contact
 */
class Report extends \yii\db\ActiveRecord
{
    public $data = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'report';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'date', 'views', 'author', 'authority_id', 'category_id', 'lon', 'lat', 'city_id', 'text', 'anonymous', 'email', 'contact'], 'safe'],
            [['date'], 'safe'],
            [['views','authority_id', 'category_id', 'city_id', 'anonymous'], 'integer'],
            [['lon', 'lat'], 'number'],
            [['text'], 'string'],
            [['title', 'author', 'email', 'contact'], 'string', 'max' => 255],
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
            'date' => Yii::t('app', 'Дата и время'),
            'views' => Yii::t('app', 'Просмотры'),
            'author' => Yii::t('app', 'Автор'),
            'authority_id' => Yii::t('app', 'гос.структура'),
            'category_id' => Yii::t('app', 'Сектор коррупции'),
            'lon' => Yii::t('app', 'Lon'),
            'lat' => Yii::t('app', 'Lat'),
            'city_id' => Yii::t('app', 'Город'),
            'text' => Yii::t('app', 'Текст'),
            'anonymous' => Yii::t('app', 'Анонимное сообщение'),
            'email' => Yii::t('app', 'Электронная почта'),
            'contact' => Yii::t('app', 'Контакты'),
        ];
    }

    public function getDropdownItems($data_key="")
    {
        $items = ArrayHelper::map(Vocabulary::find()->where(['parent'=>0,'key'=>$data_key])->all(), 'id', 'value');
        $new_arr = [];
        foreach ($items as $key=>$val) {
            $new_arr[$val]= ArrayHelper::map(Vocabulary::find()->where(['parent'=>$key])->all(),'id','value');
        }
        return $new_arr;
    }

    public function getAuthorities()
    {
        $items = ArrayHelper::map(Authority::find()->where(['category_id'=>0])->all(), 'id', 'title');
        $new_arr = [];
        foreach ($items as $key=>$val) {
            $new_arr[$val]= ArrayHelper::map(Authority::find()->where(['category_id'=>$key])->all(),'id','title');
        }
        return $new_arr;
    }

}
