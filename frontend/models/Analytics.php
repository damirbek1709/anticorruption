<?php

namespace frontend\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "analytics".
 *
 * @property integer $id
 * @property string $title
 * @property string $date
 * @property integer $status
 * @property string $text
 * @property integer $author_id
 */
class Analytics extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'analytics';
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
            [['title', 'date', 'text', 'author_id'], 'safe'],
            [['date','status'], 'safe'],
            [['status', 'author_id'], 'integer'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 255],
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
            'date' => Yii::t('app', 'Дата'),
            'text' => Yii::t('app', 'Текст'),
            'author_id' => Yii::t('app', 'Автор'),
        ];
    }

    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    public function fields()
    {
        return [
            'id','title','text','date','author_id',
            'author_name' => function($model) {
                if(!empty($model->author) && !empty($model->author->username)){$username=$model->author->username;}
                else{$username="";}
                return $username;
            }
        ];
    }
}
