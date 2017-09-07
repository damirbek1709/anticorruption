<?php

namespace app\models;

use Yii;

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
            [['title', 'date', 'views', 'author', 'user_id', 'authority_id', 'category_id', 'lon', 'lat', 'city_id', 'text', 'anonymous', 'email', 'contact'], 'required'],
            [['date'], 'safe'],
            [['views', 'user_id', 'authority_id', 'category_id', 'city_id', 'anonymous'], 'integer'],
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
            'title' => Yii::t('app', 'Title'),
            'date' => Yii::t('app', 'Date'),
            'views' => Yii::t('app', 'Views'),
            'author' => Yii::t('app', 'Author'),
            'user_id' => Yii::t('app', 'User ID'),
            'authority_id' => Yii::t('app', 'Authority ID'),
            'category_id' => Yii::t('app', 'Category ID'),
            'lon' => Yii::t('app', 'Lon'),
            'lat' => Yii::t('app', 'Lat'),
            'city_id' => Yii::t('app', 'City ID'),
            'text' => Yii::t('app', 'Text'),
            'anonymous' => Yii::t('app', 'Anonymous'),
            'email' => Yii::t('app', 'Email'),
            'contact' => Yii::t('app', 'Contact'),
        ];
    }
}
