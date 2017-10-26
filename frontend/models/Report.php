<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use frontend\models\Vocabulary;
use frontend\models\Comments;
use yii\db\ActiveRecord;
use yii\behaviors\AttributeBehavior;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Box;
use yii\helpers\FileHelper;

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
 * @property array $images
 */
class Report extends \yii\db\ActiveRecord
{
    public $file;
    public $images = []; //android, ios uploaded images

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
            [['title', 'authority_id', 'category_id', 'text', 'city_id', 'user_id', 'type_id'], 'required'],
            [['category_id', 'lon', 'author', 'lat', 'city_id', 'text', 'anonymous', 'email', 'contact', 'images'], 'safe'],
            //[['email'], 'email'],
            [['date', 'status'], 'safe'],
            [['views', 'authority_id', 'category_id', 'city_id', 'anonymous', 'type_id'], 'integer'],
            [['lon', 'lat'], 'number'],
            [['text'], 'string'],
            [['title', 'author', 'email', 'contact'], 'string', 'max' => 255],

            [['author'], 'required', 'when' => function ($model) {
                return $model->anonymous == '0';
            },
                'whenClient' => "function (attribute, value) {return $('#report-anonymous').val() === '0';}"],
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
            'date' => Yii::t('app', 'Дата и время'),
            'views' => Yii::t('app', 'Просмотры'),
            'author' => Yii::t('app', 'Автор'),
            'authority_id' => Yii::t('app', 'гос.структура'),
            'category_id' => Yii::t('app', 'Сектор коррупции'),
            'lon' => Yii::t('app', 'Lon'),
            'lat' => Yii::t('app', 'Lat'),
            'city_id' => Yii::t('app', 'Город'),
            'type_id' => Yii::t('app', 'Тип обращения'),
            'text' => Yii::t('app', 'Текст'),
            'anonymous' => Yii::t('app', 'Анонимное сообщение'),
            'email' => Yii::t('app', 'Электронная почта'),
            'contact' => Yii::t('app', 'Контакты'),
            'imageFile' => Yii::t('app', 'Image File'),
            'imageFiles' => Yii::t('app', 'Фотографии'),
            'file' => 'Фотографии',
            'status'=>'Статус',
        ];
    }

    public function getAuthority()
    {
        return $this->hasOne(Authority::className(), ['id' => 'authority_id']);
    }


    public static function getDropdownItems($data_key = "")
    {
        $items = ArrayHelper::map(Vocabulary::find()->where(['parent' => 0, 'key' => $data_key])->all(), 'id', 'value');
        $new_arr = [];
        foreach ($items as $key => $val) {
            $new_arr[$val] = ArrayHelper::map(Vocabulary::find()->where(['parent' => $key])->all(), 'id', 'value');
        }
        return $new_arr;
    }

    public static function getAuthorities()
    {
        $items = ArrayHelper::map(Authority::find()->where(['category_id' => 0])->all(), 'id', 'title');
        $new_arr = [];
        foreach ($items as $key => $val) {
            $new_arr[$val] = ArrayHelper::map(Authority::find()->where(['category_id' => $key])->all(), 'id', 'title');
        }
        return $new_arr;
    }

    public static function getSingleDrop($key)
    {
        return ArrayHelper::map(Vocabulary::find()->where(['key' => $key])->all(), 'id', 'value');
    }


    public function getDepartment()
    {
        return $this->hasOne(Vocabulary::className(), ['id' => 'category_id']);
    }

    public function getType()
    {
        return $this->hasOne(Vocabulary::className(), ['id' => 'type_id']);
    }

    public function getCity()
    {
        return $this->hasOne(Vocabulary::className(), ['id' => 'city_id']);
    }

    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['report_id' => 'id'])->orderBy(['date' => SORT_ASC]);
    }

    public function getCommentsCount()
    {
        return $this->hasMany(Comments::className(), ['report_id' => 'id'])->count();
    }

    function getImages()
    {
        $result = [];
        if (is_dir(Yii::getAlias("@frontend/web/images/report/{$this->id}"))) {
            $images = FileHelper::findFiles(Yii::getAlias("@frontend/web/images/report/{$this->id}"), [
                'recursive' => false,
                'except' => ['.gitignore'],
            ]);
            $thumbs = FileHelper::findFiles(Yii::getAlias("@frontend/web/images/report/{$this->id}/thumbs"), [
                'recursive' => false,
                'except' => ['.gitignore'],
            ]);
            $result = [];
            $thumbResult = [];

            foreach ($images as $image) {
                $result[] = str_replace([Yii::getAlias('@frontend/web'), DIRECTORY_SEPARATOR], ['', '/'], $image);
            }

            foreach ($thumbs as $thumb) {
                $thumbResult[] = str_replace([Yii::getAlias('@frontend/web'), DIRECTORY_SEPARATOR], ['', '/'], $thumb);
            }
            $result = array_combine($thumbResult, $result);
        }

        return $result;
    }


    /**
     * @inheritdoc
     */
    function getThumbs()
    {
        $result = [];
        if (is_dir(Yii::getAlias("@webroot/images/report/{$this->id}"))) {
            $images = FileHelper::findFiles(Yii::getAlias("@webroot/images/report/{$this->id}/thumbs"), [
                'recursive' => false,
                'except' => ['.gitignore']
            ]);

            $index = 0;
            foreach ($images as $image) {
                $result[] = $image;
                $index++;
            }
            return $result;
        }
    }

    public function beforeSave($insert)
    {
        //depend table holds timestamp of last table modification. it's for api
        if ($this->isNewRecord && !$this->date) {
            $this->date = date("Y-m-d H:i:s");
        }
        $dao = Yii::$app->db;
        $voc = $dao->createCommand("SELECT * FROM `depend` WHERE `table_name`='report'")->queryOne();
        if (!$voc) {
            $dao->createCommand()->insert('depend', [
                'table_name' => 'news',
                'last_update' => time(),
            ])->execute();
        } else {
            $dao->createCommand()->update('depend', ['last_update' => time()], 'table_name="report"')->execute();
        }

        $this->file = UploadedFile::getInstances($this, 'file');
        return parent::beforeSave($insert);
    }

    function getThumbImages()
    {
        $result = [];
        if (is_dir(Yii::getAlias("@webroot/images/report/{$this->id}"))) {
            $images = FileHelper::findFiles(Yii::getAlias("@webroot/images/report/{$this->id}/thumbs"), [
                'recursive' => false,
                'except' => ['.gitignore']
            ]);

            $index = 0;
            foreach ($images as $image) {
                $result[] = Html::img(str_replace([Yii::getAlias('@webroot'), DIRECTORY_SEPARATOR], [Yii::getAlias('@web'), '/'], $image));

                $index++;
            }
        }
        return $result;
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $webroot = Yii::getAlias('@webroot');
        $model_name = Yii::$app->controller->id;
        if (is_dir($dir = $webroot . "/images/report/" . $this->id)) {
            $scaned_images = scandir($dir, 1);
            foreach ($scaned_images as $file) {
                if (is_file($dir . '/' . $file)) @unlink($dir . '/' . $file);
            }
            @rmdir($dir);
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        $dir = Yii::getAlias("@webroot/images/report/{$this->id}");
        $thumbDir = $dir . "/thumbs";
        if (count($this->file)) {
            FileHelper::createDirectory($dir);
            FileHelper::createDirectory($thumbDir);

            $imagine = Image::getImagine();
            foreach ($this->file as $k => $file) {
                $ts = time();
                $rand = $this->id . "_" . $k . "_" . $ts;
                $imgName = $rand . "." . strtolower($file->extension);
                $file->saveAs($dir . DIRECTORY_SEPARATOR . $imgName);
                $image = $imagine->open($dir . DIRECTORY_SEPARATOR . $imgName);
                $image->thumbnail(new Box(1200, 1200))->save($dir . "/" . $imgName);
                $image->thumbnail(new Box(120, 120))->save($dir . "/thumbs/" . $imgName, ['quality' => 95]);

                //Image::thumbnail($dir.'/'.$imgName, 135, 100)->save($dir.'/thumbs/'.$imgName, ['quality' => 90]);
            }
        }

        //api
        if ($this->images) {
            $dir = Yii::getAlias("@frontend/web/images/report/{$this->id}");
            $thumbDir = $dir . "/thumbs";
            FileHelper::createDirectory($dir);
            FileHelper::createDirectory($thumbDir);
            foreach ($this->images as $k => $image) {
                $ts = time();
                $rand = $this->id . "_" . $k . "_" . $ts;
                $imgname = $rand . ".jpg";
                $decoded = base64_decode($image);
                imagejpeg(imagecreatefromstring($decoded), $dir . '/' . $imgname, 95);
                $this->resizeImage($dir, $rand);
            }
        }
    }

    protected function resizeImage($dir, $imageName)
    {
        if (Yii::$app->request->serverName == 'anticor.loc') {
            Image::$driver = [Image::DRIVER_GD2];
        }
        $imagine = Image::getImagine()->open($dir . '/' . $imageName . '.jpg');
        $imagine->thumbnail(new Box(1200, 1200))->save($dir . '/' . $imageName . '.jpg');
        $imagine->thumbnail(new Box(120, 120))->save($dir . '/thumbs/' . $imageName . '.jpg');
        //$imagine->thumbnail(new Box(80, 80))->save($dir.'/'.$imageName.'_t.jpg');
    }

    public function fields()
    {
        return [
            'id', 'title', 'text', 'date', 'lon', 'lat',
            'anonymous', 'author', 'email', 'contact', 'views',
            'comments_count' => function ($model) {
                return $model->commentsCount;
            },
            'authority_id',
            'authority_title' => function ($model) {
                return $model->authority->title;
            },
            'category_id',
            'category_title' => function ($model) {
                return $model->department->value;
            },
            'city_id',
            'city_title' => function ($model) {
                return $model->city->value;
            },
            'type_id',
            'type_title' => function ($model) {
                return $model->type->value;
            }
        ];
    }

    public function extraFields()
    {
        return ['profile'];
    }

}
