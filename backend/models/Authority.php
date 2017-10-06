<?php

namespace frontend\models;

use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\imagine\Image;
use yii\helpers\Json;
use Imagine\Image\Box;
use Imagine\Image\Point;
use yii\web\UploadedFile;
use yii\db\ActiveRecord;
use yii\behaviors\AttributeBehavior;
use yii\db\Query;
use frontend\models\Comments;


/**
 * This is the model class for table "authority".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property double $rating
 * @property integer $votes
 * @property string $img
 */
class Authority extends \yii\db\ActiveRecord
{

    public $image;
    public $crop_info;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'authority';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text'], 'required'],
            [['text'], 'string'],
            [['votes','category_id'], 'integer'],
            [['title', 'img'], 'string', 'max' => 255],
            [
                'image',
                'image',
                'extensions' => ['jpg', 'jpeg', 'png', 'gif'],
                'mimeTypes' => ['image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'],
            ],
            ['crop_info', 'safe'],
        ];
    }

    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['category_id' => 'id'])->orderBy(['date' => SORT_ASC]);
    }

    public function getReports()
    {
        return $this->hasMany(Report::className(), ['authority_id' => 'id'])->andWhere(['type_id' => 134])->orderBy(['date' => SORT_ASC]);
    }

    public function getBrief()
    {
        return $this->hasMany(Report::className(), ['authority_id' => 'id'])->andWhere(['type_id' => 135])->orderBy(['date' => SORT_ASC]);
    }

    public function getCommentsCount()
    {
        return $this->hasMany(Comments::className(), ['category_id' => 'id'])->count();
    }


    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['rating','votes'], // update 1 attribute 'created' OR multiple attribute ['created','updated']
                    //ActiveRecord::EVENT_BEFORE_UPDATE => '', // update 1 attribute 'created' OR multiple attribute ['created','updated']
                ],
                'value' => function ($event) {
                    return 0;
                },
            ],
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
            'text' => Yii::t('app', 'Text'),
            'rating' => Yii::t('app', 'Rating'),
            'votes' => Yii::t('app', 'Votes'),
            'img' => Yii::t('app', 'Img'),
            'image' => Yii::t('app', 'Рисунок'),
        ];
    }

    public function beforeValidate()
    {
        $this->image = UploadedFile::getInstance($this, 'image');
        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        if ($this->image)
            $this->img = $this->image->name;
        return parent::beforeSave($insert);
    }

    public static function getRating($id){
        $query = (new Query())->from('rating')->where(['authority_id'=>$id]);
        if($query->count()==0)
            $rating = 0;
        else
            $rating = round($query->sum('rating') / $query->count());
        return $rating;
    }

    public function getRatingCount()
    {
        return $this->hasMany(Rating::className(), ['authority_id' => 'id'])->count();
    }

    public function getReportCount()
    {
        return $this->hasMany(Rating::className(), ['authority_id' => 'id'])->count();
    }

    public function afterSave($insert, $changedAttributes)
    {
        // open image
        if ($this->image) {
            $cropInfo = Json::decode(Yii::$app->request->post()['image_data']);

            $width = (int)$cropInfo['width'];
            $height = (int)$cropInfo['height'];

            $x = $cropInfo["x"];
            $y = $cropInfo["y"];

            $imagine = Image::getImagine();
            $this->image->saveAs(
                Yii::getAlias('@frontend/web/images/authority')
                . "/{$this->id}-"
                . $this->image->name, ['quality' => 100]);

            $image = $imagine->open(
                Yii::getAlias('@frontend/web/images/authority')
                . "/{$this->id}-"
                . $this->image->name, ['quality' => 100]);

            $image->resize(new Box($image->getSize()->getWidth(), $image->getSize()->getHeight()))
                ->crop(new Point($x, $y), new Box($width, $height))
                ->save(Yii::getAlias('@frontend/web/images/authority')
                    . "/{$this->id}-"
                    . $this->image->name, ['quality' => 100]);

            $image->resize(new Box(400, 400))->save(Yii::getAlias('@frontend/web/images/authority')
                . "/{$this->id}-"
                . $this->image->name, ['quality' => 100]);

            $image->resize(new Box(150, 150))->save(Yii::getAlias('@frontend/web/images/authority')
                . "/s-{$this->id}-".
                $this->image->name, ['quality' => 100]);
        }
    }
}
