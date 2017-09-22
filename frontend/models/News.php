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
use frontend\models\Vocabulary;
/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $text
 */
class News extends \yii\db\ActiveRecord
{

    public $image;
    public $crop_info;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
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


    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['news_id' => 'id'])->orderBy(['date' => SORT_ASC]);
    }

    public function getCommentsCount()
    {
        return $this->hasMany(Comments::className(), ['news_id' => 'id'])->count();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'text'], 'required'],
            [['category_id', 'views', 'img','date','main_news'], 'safe'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 600],
            [
                'image',
                'image',
                'extensions' => ['jpg', 'jpeg', 'png', 'gif'],
                'mimeTypes' => ['image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'],
            ],
            ['crop_info', 'safe'],
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
            'description' => Yii::t('app', 'Короткое описание'),
            'text' => Yii::t('app', 'Текст'),
            'img' => Yii::t('app', 'Рисунок'),
            'category_id' => Yii::t('app', 'Категория'),
            'views' => Yii::t('app', 'Просмотры'),
            'date' => Yii::t('app', 'Дата'),
            'main_news' => Yii::t('app', 'Главная новость'),
        ];
    }

    public function getImages(){

    }

    public function getThumb(){
        return Html::img(Url::base().'/images/news/s_'.$this->img);
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

    public function getCategory(){
        return $this->hasOne(Vocabulary::className(), ['id' => 'category_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_favorited']);
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
                Yii::getAlias('@webroot/images/news')
                . '/'
                . $this->image->name, ['quality' => 100]);

            $image = $imagine->open(
                Yii::getAlias('@webroot/images/news')
                . '/'
                . $this->image->name, ['quality' => 100]);

            $image->resize(new Box($image->getSize()->getWidth(), $image->getSize()->getHeight()))
                ->crop(new Point($x, $y), new Box($width, $height))
                ->save(Yii::getAlias('@webroot/images/news')
                    . '/'
                    . $this->image->name, ['quality' => 100]);

            $image->resize(new Box(440, 270))->save(Yii::getAlias('@webroot/images/news')
                . '/'
                . $this->image->name, ['quality' => 100]);

            $image->resize(new Box(135, 100))->save(Yii::getAlias('@webroot/images/news')
                . '/s_'.
                $this->image->name, ['quality' => 100]);
        }
    }
}
