<script src='https://www.google.com/recaptcha/api.js'></script>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$cssSlider = Yii::getAlias('@frontend/web')."/js/lightslider/src/css/lightslider.css";
$cssSlider = str_replace([Yii::getAlias('@frontend/web'), DIRECTORY_SEPARATOR], ['', '/'], $cssSlider);

$jsSlider = Yii::getAlias('@frontend/web')."/js/lightslider/src/js/lightslider.js";
$jsSlider = str_replace([Yii::getAlias('@frontend/web'), DIRECTORY_SEPARATOR], ['', '/'], $jsSlider);?>

<link rel="stylesheet" href="<?=$cssSlider?>"/>
<script src="<?=$jsSlider?>"></script>

<? $this->title = $model->title;?>
<div class="news-view mobile_padder">
    <div class="minor_heading"><?= Html::encode($this->title) ?></div>
    <?
    echo Html::tag('span', Yii::$app->formatter->asDate($model->date), ['class' => 'news_date']);
    echo Html::tag('span', '', ['class' => 'glyphicon glyphicon-time date-clock']);
    echo Html::tag('span', date("H:i", strtotime($model->date)), ['class' => 'news_date']);
    echo Html::beginTag('span', ['class' => 'news_view_count']);
    echo Html::tag('span', "", ['class' => 'glyphicon glyphicon-eye-open ']);
    echo Html::tag('span', "Просмотров: {$model->views}", ['style' => 'margin-left:5px']);
    echo Html::endTag('span');
    ?>

    <?php
    $images = $model->getImages();
    if (!empty($images)):
        ?>
        <div class="demo chameleon_reversed" style="margin-top:10px;">
            <div class="item" style="margin-bottom:20px;">
                <ul id="content-slider" class="content-slider">
                    <?php
                    foreach ($images as $image) {
                        echo Html::beginTag("li", []);
                        echo Html::beginTag("div", ['class' => 'slider_cover']);
                        echo Html::beginTag("div", ['class' => 'slider_bg']);
                        echo $image;
                        echo Html::endTag("div");
                        echo Html::endTag("div");
                        echo Html::endTag("li");
                    }
                    ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <div class="news_category_italic"><?= $model->category->value; ?></div>
    <div class="news_text"><?= $model->text; ?></div>
    <div class="chameleon_backend">
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
                 data-services="facebook,vkontakte,odnoklassniki,twitter"
                 data-url="http://anticorruption.kg/news/<?= $model->id ?>"
                 data-title="Антикоррупционный портал Кыргызской Республики">
            </div>
        </div>
        <?php Pjax::begin(['id' => 'pjax-comment']); ?>
        <div class="comment-box">
            <div class="top_marginer"></div>
            <div class="comments">Комментарии(<?= $model->commentsCount; ?>)</div>
            <?php $comments = $model->comments;
            foreach ($comments as $item) {
                echo Html::beginTag('div', ['class' => 'comment-block']);
                echo Html::tag('div', '', ['class' => 'comment-avatar']);
                echo Html::tag('div', $item->name, ['class' => 'comment-author']);
                echo Html::tag('div', $item->text, ['class' => 'comment-text']);
                echo Html::tag('span', date("H:i", strtotime($model->date)), ['class' => 'comment-date']);
                echo Html::tag('span', Yii::$app->formatter->asDate($model->date), ['class' => 'comment-date']);
                if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin) {
                    echo Html::tag('div', '', ['class' => 'clear']);
                    echo Html::tag('button', 'Редактировать', ['class' => 'btn btn-primary comment-update', 'style' => 'margin:10px 10px 0 0;', 'data-id' => $item->id]);
                    echo Html::tag('button', 'Удалить', ['class' => 'btn btn-danger comment-delete', 'style' => 'margin:10px 0 0 0;', 'data-id' => $item->id]);
                }
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
            echo $form->field($comment, 'category_id')->hiddenInput(['value' => 2])->label(false); ?>
            <?= $form->field($comment, 'news_id')->hiddenInput(['value' => $model->id])->label(false); ?>


            <?= $form->field($comment, 'text')->textarea(['maxlength' => true, 'rows' => 9, 'placeholder' => 'Введите текст комментария', 'class' => 'form-control comment-input-text'])->label(false); ?>

            <div class="form-group">
                <div class="g-recaptcha" data-sitekey="6LegmzEUAAAAAGucd6quo8hn50mfC6xt_WF9u43P"></div>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Отправить комментарий'), ['class' => 'send-comment btn btn-danger']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <?php Pjax::end(); ?>
    </div>
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
                if (response == "No") {
                    alert(response);
                }
                else {
                    $.pjax.reload({container: "#pjax-comment"});
                }
            }
        });
        return false;
    });

    $('body').on('click', '.comment-delete', function (e) {
        e.preventDefault();
        var commentId = $(this).attr('data-id');
        var thisOne = $(this);
        $.ajax({
            url: '/comments/remove',
            type: 'post',
            data: {id: commentId},
            success: function () {
                $.pjax.reload({container: "#pjax-comment"});
            }
        });
        return false;
    });


    $('body').on('click', '.comment-update', function (e) {
        e.preventDefault();

        var thisOne = $(this);
        if (!thisOne.hasClass('sender')) {
            thisOne.addClass('sender');
            thisOne.before("<textarea class='form-control editable-comment comment-input-text'>" + thisOne.siblings('.comment-text').text() + "</textarea>");
        }

    });
    $('body').on('click', '.sender', function (e) {
        e.preventDefault();
        var commentId = $(this).attr('data-id');
        var thisOne = $(this);
        var newText = thisOne.siblings('.editable-comment').val();

        $.ajax({
            url: '/comments/edit',
            type: 'post',
            data: {id: commentId, text: newText},
            success: function () {
                $.pjax.reload({container: "#pjax-comment"});
            }
        });
        return false;
    });
</script>

<?php
$script = <<<SCRIPT
            $(document).ready(function() {
                    $("#content-slider").lightSlider({
                        loop:true,
                        keyPress:true,
                        item:1,
                        slideMargin:0,
                        adaptiveHeight:true,
                       
                    });
                    
                     $("#authority-slider").lightSlider({
                        loop:true,
                        keyPress:true,
                        item:1,
                        slideMargin:0,
                        adaptiveHeight:true,
                        pager:false,
                       
                    });
                });
                
SCRIPT;
$this->registerJs($script);
?>
