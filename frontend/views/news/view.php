<script src='https://www.google.com/recaptcha/api.js'></script>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = $model->title;;
?>
<div class="news-view">

    <div class="minor_heading"><?= Html::encode($this->title) ?></div>
    <?
    echo Html::tag('span', Yii::$app->formatter->asDate($model->date), ['class' => 'news_date']);
    echo Html::tag('span', '', ['class' => 'glyphicon glyphicon-time date-clock']);
    echo Html::tag('span', Yii::$app->formatter->asTime($model->date), ['class' => 'news_date']);
    echo Html::beginTag('span', ['class' => 'news_view_count']);
    echo Html::tag('span', "", ['class' => 'glyphicon glyphicon-eye-open ']);
    echo Html::tag('span', "Просмотров: {$model->views}", ['style' => 'margin-left:5px']);
    echo Html::endTag('span');
    ?>
    <div class="demo" style="margin-top:10px;">
        <div class="item" style="margin-bottom:20px;">
            <ul id="content-slider" class="content-slider">
                <?php
                echo Html::beginTag("li", []);
                echo Html::beginTag("div", ['class' => 'slider_cover']);
                echo Html::beginTag("div", ['class' => 'slider_bg']);
                echo Html::a(Html::img(Url::base() . "/images/news/{$model->img}"), ['/news/view', 'id' => $model->id]);
                echo Html::endTag("div");
                echo Html::endTag("div");
                echo Html::endTag("li");
                ?>

                <?php
                echo Html::beginTag("li", []);
                echo Html::beginTag("div", ['class' => 'slider_cover']);
                echo Html::beginTag("div", ['class' => 'slider_bg']);
                echo Html::a(Html::img(Url::base() . "/images/news/{$model->img}"), ['/news/view', 'id' => $model->id]);
                echo Html::endTag("div");
                echo Html::endTag("div");
                echo Html::endTag("li");
                ?>
            </ul>
        </div>
    </div>
    <div class="news_category_italic"><?= $model->category->value; ?></div>
    <div class="news_text"><?= $model->text; ?></div>
    <div class="share_buttons">
        <span class="share_label">Поделиться в соц.сетях: </span>
        <script type="text/javascript">(function () {
                if (window.pluso) if (typeof window.pluso.start == "function") return;
                if (window.ifpluso == undefined) {
                    window.ifpluso = 1;
                    var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                    s.type = 'text/javascript';
                    s.charset = 'UTF-8';
                    s.async = true;
                    s.src = ('https:' == window.location.protocol ? 'https' : 'http') + '://share.pluso.ru/pluso-like.js';
                    var h = d[g]('body')[0];
                    h.appendChild(s);
                }
            })();</script>
        <div class="pluso" data-background="none;"
             data-options="medium,square,line,horizontal,counter,sepcounter=1,theme=14"
             data-services="facebook,vkontakte,odnoklassniki,twitter" data-url="http://anticorruption.kg"
             data-title="Антикоррупционный портал Кыргызской Республики"></div>
    </div>
    <?php Pjax::begin(['id' => 'pjax-comment']);?>
    <div class="comment-box">
        <div class="top_marginer"></div>
        <div class="comments">Комментарии(<?= $model->commentsCount; ?>)</div>
        <?php $comments = $model->comments;
        foreach ($comments as $item) {
            echo Html::beginTag('div', ['class' => 'comment-block']);
            echo Html::tag('div', '', ['class' => 'comment-avatar']);
            echo Html::tag('div', $item->name, ['class' => 'comment-author']);
            echo Html::tag('div', $item->text, ['class' => 'comment-text']);
            echo Html::tag('span', Yii::$app->formatter->asTime($model->date), ['class' => 'comment-date']);
            echo Html::tag('span', Yii::$app->formatter->asDate($model->date), ['class' => 'comment-date']);
            echo Html::endTag('div');
        }
        ?>
        <div class="top_marginer_30"></div>
        <div class="comments">Оставить комментарии</div>
        <?php $form = ActiveForm::begin([
            'action' => Url::to(['comments/create']),
            'id' => 'comment-add',
            'options' => [
                'class' => 'comments-gq',
            ]]); ?>

        <?php
        if (Yii::$app->user->isGuest) {
            echo $form->field($comment, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Введите имя', 'class' => 'form-control sharper'])->label(false);
            echo $form->field($comment, 'email')->textInput(['maxlength' => true, 'placeholder' => '@электронная почта', 'class' => 'form-control sharper'])->label(false);
        } else {
            echo $form->field($comment, 'name')->hiddenInput(['value' => Yii::$app->user->identity->username])->label(false);
            echo $form->field($comment, 'email')->hiddenInput(['value' => Yii::$app->user->identity->email])->label(false);
        }
        ?>
        <?= $form->field($comment, 'news_id')->hiddenInput(['value' => $model->id])->label(false); ?>


        <?= $form->field($comment, 'text')->textarea(['maxlength' => true, 'rows' => 9, 'placeholder' => 'Введите текст комментария', 'class' => 'form-control comment-input-text'])->label(false); ?>

        <div class="form-group">
            <div class="g-recaptcha" data-sitekey="6LfuDjAUAAAAAMoT6P4SAA7HOu1hVa2ibjIAx8Vn"></div>
        </div>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Отправить комментарий'), ['class' => 'send-comment btn btn-danger']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <?php Pjax::end();?>
</div>

<?php


?>

<script type="text/javascript">
    $('body').on('submit', '#comment-add', function (e) {
        e.preventDefault();
        var form = $(this);
        if (form.find('.has-error').length) {
            return false;
        }
        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: form.serialize(),
            success: function (response) {
                if(response == "No") {
                    alert(response);
                }
                else{
                    $.pjax.reload({container: "#pjax-comment"});
                }
            }
        });
        return false;
    });
</script>
