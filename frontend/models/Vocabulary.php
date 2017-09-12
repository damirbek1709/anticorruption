<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "vocabulary".
 *
 * @property integer $id
 * @property string $key
 * @property string $value
 * @property integer $ordered_id
 * @property integer $parent
 */
class Vocabulary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vocabulary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'value', 'ordered_id', 'parent'], 'required'],
            [['ordered_id', 'parent'], 'integer'],
            [['key', 'value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'key' => Yii::t('app', 'Key'),
            'value' => Yii::t('app', 'Value'),
            'ordered_id' => Yii::t('app', 'Ordered ID'),
            'parent' => Yii::t('app', 'Parent'),
        ];
    }
}
