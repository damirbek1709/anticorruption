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
    public $file;
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
        return $this->hasMany(Comments::className(), ['news_id' => 'id'])->where(['status'=>1])->orderBy(['date' => SORT_ASC]);
    }

    public function getCommentsCount()
    {
        return $this->hasMany(Comments::className(), ['news_id' => 'id'])->where(['status'=>1])->count();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'text'], 'required'],
            [['category_id', 'views', 'date', 'main_news', 'img', 'text_ky', 'title_ky', 'description_ky', 'text_en', 'title_en', 'description_en'], 'safe'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 600],
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
            'file' => Yii::t('app', 'Фото'),
            'title_ky'=>Yii::t('app', 'Заголовок на кыргызском'),
            'text_ky'=>Yii::t('app', 'Текст на кыргызском'),
            'description_ky' => Yii::t('app', 'Короткое описание на кыргызском'),
            'description_en' => Yii::t('app', 'Короткое описание на английском'),
            'title_en' => Yii::t('app', 'Заголовок на английском'),
            'text_en' => Yii::t('app', 'Текст на английском'),
        ];
    }

    function getThumbs()
    {
        $result = [];
        if (is_dir(Yii::getAlias("@frontend/web/images/news/{$this->id}"))) {
            $images = FileHelper::findFiles(Yii::getAlias("@frontend/web/images/news/{$this->id}/thumbs"), [
                'recursive' => false,
                'except' => ['.gitignore']
            ]);
            $index = 0;
            foreach ($images as $image) {
                $result[] = $image;
                if (basename($image) == $this->img) {
                    $new_value = $image;
                    unset($result[$index]);
                    array_unshift($result, $new_value);
                }
                $index++;
            }
            return $result;
        }
    }

    function getThumbImages()
    {
        $result = [];
        if (is_dir(Yii::getAlias("@frontend/web/images/news/{$this->id}"))) {
            $images = FileHelper::findFiles(Yii::getAlias("@frontend/web/images/news/{$this->id}/thumbs"), [
                'recursive' => false,
                'except' => ['.gitignore']
            ]);
            $index = 0;
            foreach ($images as $image) {
                $result[] = Html::img(str_replace([Yii::getAlias('@frontend/web'), DIRECTORY_SEPARATOR], ['', '/'], $image));

                if (basename($image) == $this->img) {
                    $new_value = Html::img(str_replace([Yii::getAlias('@frontend/web'), DIRECTORY_SEPARATOR], ['', '/'], $image));
                    unset($result[$index]);
                    array_unshift($result, $new_value);
                }
                $index++;
            }
        }
        return $result;
    }

    public function getThumb()
    {
        if ($this->img)
            return Html::img(Url::base() . "/images/news/{$this->id}/thumbs/{$this->img}");
        else
            return "";
    }

    public function getMainImg()
    {
        if ($this->img) {
            return Html::img(Url::base() . "/images/news/{$this->id}/{$this->img}");
        } else {
            return Html::img(Url::base() . "/images/site/template.png");
        }
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord && !$this->date) {
            $this->date = date("Y-m-d H:i:s");
        }
        $this->file = UploadedFile::getInstances($this, 'file');
        //depend table holds timestamp of last table modification. it's for api
        $dao = Yii::$app->db;
        $voc = $dao->createCommand("SELECT * FROM `depend` WHERE `table_name`='news'")->queryOne();
        if (!$voc) {
            $dao->createCommand()->insert('depend', [
                'table_name' => 'news',
                'last_update' => time(),
            ])->execute();
        } else {
            $dao->createCommand()->update('depend', ['last_update' => time()], 'table_name="news"')->execute();
        }
        return parent::beforeSave($insert);
    }

    public function getCategory()
    {
        return $this->hasOne(Vocabulary::className(), ['id' => 'category_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_favorited']);
    }

    function getImages()
    {
        $result = [];
        if (is_dir(Yii::getAlias("@frontend/web/images/news/{$this->id}"))) {
            $images = FileHelper::findFiles(Yii::getAlias("@frontend/web/images/news/{$this->id}/"), [
                'recursive' => false,
                'except' => ['.gitignore']
            ]);

            $index = 0;
            foreach ($images as $image) {
                $result[] = Html::img(str_replace([Yii::getAlias('@frontend/web'), DIRECTORY_SEPARATOR], ['', '/'], $image));
                if (basename($image) == $this->img) {
                    $new_value = Html::img(str_replace([Yii::getAlias('@frontend/web'), DIRECTORY_SEPARATOR], ['', '/'], $image));
                    unset($result[$index]);
                    array_unshift($result, $new_value);
                }
                $index++;
            }
        }
        return $result;
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
                    $this->description = $this->{"description_en"};
                } else {
                    $this->title = $this->{"title"} . "<span style=color:black> (This material is not availabe in English yet)</span>";
                    $this->text = $this->{"text"};
                    $this->description = $this->{"description"};
                }
                break;
            case "ky":
                if ($this->title_ky != null && $this->description_ky) {
                    $this->text = $this->{"text_ky"};
                    $this->title = $this->{"title_ky"};
                    $this->description = $this->{"description_ky"};
                } else {
                    $this->title = $this->{"title"} . "<span style=color:black> (Кыргыз тилинде бул материал азырынча жеткиликтүү эмес)</span>";
                    $this->text = "";
                    $this->description = $this->{"description"};
                }
                break;
            default:
                $this->text = $this->{"text"};
                $this->title = $this->{"title"};
                $this->description = $this->{"description"};
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (count($this->file)) {
            $dir = Yii::getAlias("@frontend/web/images/news/{$this->id}");
            $thumbDir = Yii::getAlias("@frontend/web/images/news/{$this->id}/thumbs");
            FileHelper::createDirectory($dir);
            FileHelper::createDirectory($thumbDir);
            $imagine = Image::getImagine();
            $now = time();
            $counter = 1;
            foreach ($this->file as $file) {
                $filename = $counter . $now . "." . $file->extension;
                if ($this->img == null && $counter == 1) {
                    Yii::$app->db->createCommand()->update('news', ['img' => $filename], ['id' => $this->id])->execute();
                }
                $file->saveAs("{$dir}" . DIRECTORY_SEPARATOR . "{$filename}");
                $image = $imagine->open(
                    Yii::getAlias('@frontend/web/images/news')
                    . "/{$this->id}/{$filename}", ['quality' => 100]);
                $image->resize(new Box(440, 270))->save(Yii::getAlias('@frontend/web/images/news/')
                    . "{$this->id}/" . $filename, ['quality' => 100]);
                Image::thumbnail($dir . '/' . "{$filename}", 440, 270)->save($dir . '/' . "{$filename}", ['quality' => 100]);
                $image->resize(new Box(135, 100))->save(Yii::getAlias('@frontend/web//images/news/')
                    .
                    "{$this->id}/thumbs/{$filename}", ['quality' => 100]);
                Image::thumbnail($dir . '/' . "{$filename}", 135, 100)->save($dir . '/thumbs/' . "{$filename}", ['quality' => 100]);
                $counter++;
            }
        }
    }
}