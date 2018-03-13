<?php

namespace frontend\models;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use Yii;
use frontend\models\Report;

/**
 * This is the model class for table "reply".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property integer $report_id
 * @property string $date
 */
class Reply extends \yii\db\ActiveRecord
{

    public $email;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reply';
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
                    return date('Y-m-d', strtotime($this->date));
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
            [['title', 'text', 'report_id', 'date'], 'safe'],
            [['text'], 'string'],
            [['report_id'], 'integer'],
            [['date'], 'safe'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    public function getReport()
    {
        return $this->hasOne(Report::className(), ['id' => 'report_id']);
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
            'report_id' => Yii::t('app', 'Обращение'),
            'date' => Yii::t('app', 'Дата'),
        ];
    }
}
