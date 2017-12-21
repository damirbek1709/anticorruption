<?php
namespace frontend\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\imagine\Image;
use Imagine\Image\Box;
use Imagine\Image\Point;
use yii\db\ActiveRecord;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "education".
 *
 * @property integer $id
 * @property string $title
 * @property string $date
 * @property string $text
 * @property string $img
 */
class Education extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'education';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text'], 'required'],
            [['date', 'img', 'title_ky', 'text_ky','title_en', 'text_en'], 'safe'],
            [['date'], 'safe'],
            [['text'], 'string'],
            [['title', 'img'], 'string', 'max' => 255],
        ];
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
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Заголовок'),
            'date' => Yii::t('app', 'Дата'),
            'text' => Yii::t('app', 'Текст'),
            'file'=> Yii::t('app', 'Изображения'),
            'title_ky'=>Yii::t('app', 'Заголовок на кыргызском'),
            'text_ky'=>Yii::t('app', 'Текст на кыргызском'),
            'title_en' => Yii::t('app', 'Заголовок на английском'),
            'text_en' => Yii::t('app', 'Текст на английском'),
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

    public function beforeSave($insert)
    {
        if($this->isNewRecord && !$this->date){
            $this->date = date("Y-m-d H:i:s");
        }
        $this->file = UploadedFile::getInstances($this, 'file');
        return parent::beforeSave($insert);
    }

    function getMainThumb()
    {
        if ($this->img) {
            return Html::img(Url::base() . "/images/education/{$this->id}/thumbs/{$this->img}");
        } else if (is_dir(Yii::getAlias("@webroot/images/education/{$this->id}"))) {
            $images = FileHelper::findFiles(Yii::getAlias("@webroot/images/education/{$this->id}/thumbs"), [
                'recursive' => false,
                'except' => ['.gitignore']
            ]);
            return Html::img(str_replace(Yii::getAlias('@webroot'), Yii::getAlias('@web'), $images[0]));
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (count($this->file)) {
            $dir = Yii::getAlias("@frontend/web/images/education/{$this->id}");
            $thumbDir = Yii::getAlias("@frontend/web/images/education/{$this->id}/thumbs");
            FileHelper::createDirectory($dir);
            FileHelper::createDirectory($thumbDir);

            $imagine = Image::getImagine();
            foreach ($this->file as $file) {
                $file->saveAs("{$dir}" . DIRECTORY_SEPARATOR . "{$file->baseName}.{$file->extension}");
                $image = $imagine->open(
                    Yii::getAlias('@frontend/web/images/education')
                    . "/{$this->id}/{$file->baseName}.{$file->extension}", ['quality' => 100]);

                $image->resize(new Box(440, 270))->save(Yii::getAlias('@frontend/web/images/education/')
                    ."{$this->id}/". $file->baseName.".".$file->extension, ['quality' => 100]);

                Image::thumbnail($dir . '/' . "{$file->baseName}.{$file->extension}", 440, 270)->save($dir . '/' . "{$file->baseName}.{$file->extension}", ['quality' => 100]);

                $image->resize(new Box(135, 100))->save(Yii::getAlias('@frontend/web/images/education/')
                    .
                    "{$this->id}/thumbs/{$file->baseName}.{$file->extension}", ['quality' => 100]);

               Image::thumbnail($dir . '/' . "{$file->baseName}.{$file->extension}", 135, 100)->save($dir . '/thumbs/' . "{$file->baseName}.{$file->extension}", ['quality' => 100]);
            }
        }
    }

    function getThumbs()
    {
        $result = [];
        if (is_dir(Yii::getAlias("@frontend/web/images/education/{$this->id}"))) {
            $images = FileHelper::findFiles(Yii::getAlias("@frontend/web/images/education/{$this->id}/thumbs"), [
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
        if (is_dir(Yii::getAlias("@frontend/web/images/education/{$this->id}"))) {
            $images = FileHelper::findFiles(Yii::getAlias("@frontend/web/images/education/{$this->id}/thumbs"), [
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

    function getImages()
    {
        $result = [];
        if (is_dir(Yii::getAlias("@frontend/web/images/education/{$this->id}"))) {
            $images = FileHelper::findFiles(Yii::getAlias("@frontend/web/images/education/{$this->id}/"), [
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
            'img',
            'date',
        ];
        return $fields;
    }
}
