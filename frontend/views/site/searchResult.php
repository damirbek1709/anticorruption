<?php
use yii\helpers\Html;

$thetitle = Yii::t('app', 'Результаты поиска по запросу "').$queryWord.'"';
$this->title = $thetitle . ' | ' . Yii::$app->name;
$this->params['breadcrumbs'][] = $thetitle;

function word_limiter($string, $number_of_words)
{
    $words = explode(' ', $string);
    $endPixels = count($words) > $number_of_words ? ' ..' : '';
    return trim(strip_tags(implode(' ', array_slice($words, 0, $number_of_words)) . $endPixels));
}
?>
<div class="sked-index">

    <h1 class="general_heading"><?= $thetitle; ?></h1>
    <style type="text/css">
        .founded {
            background-color: #ffff00;
        }

        .search-result {
            color: #444;
            margin-bottom: 24px;
        }

        .search-result .title a {
            font-size: 17px;
        }
    </style>
    <?php
    if ($news) {
        foreach ($news as $result) {
            ?>
            <div class="search-result">
                <div class="list-news-title"><?= Html::a($result['title'.$langInt], ['/news/view', 'id' => $result['id']],['class'=>'news_title']); ?></div>
                <?php
                $text = word_limiter($result['text'.$langInt], 50);
                $text = preg_replace("/{$_POST['search']}/i", "<span class='founded' >{$_POST['search']}</span>", $text);
                echo Html::tag('div',$text,['class'=>'news_description']);?>
            </div>
            <?php
        }
    }

    if ($report) {
        foreach ($report as $result) {
            ?>
            <div class="search-result">
                <div class="list-news-title"><?= Html::a($result['title'.$langInt], ['/report/view', 'id' => $result['id']],['class'=>'news_title']); ?></div>
                <?php
                $text = word_limiter($result['text'.$langInt], 50);
                $text = preg_replace("/{$_POST['search']}/i", "<span class='founded' >{$_POST['search']}</span>", $text);
                echo Html::tag('div',$text,['class'=>'news_description']);?>
            </div>
            <?php
        }
    }

    if ($politics) {
        foreach ($politics as $result) {
            ?>
            <div class="search-result">
                <div class="list-news-title"><?= Html::a($result['title'.$langInt], ['/politics/view', 'id' => $result['id']],['class'=>'news_title']); ?></div>
                <?php
                $text = word_limiter($result['text'.$langInt], 50);
                $text = preg_replace("/{$_POST['search']}/i", "<span class='founded' >{$_POST['search']}</span>", $text);
                echo Html::tag('div',$text,['class'=>'news_description']);?>
            </div>
            <?php
        }
    }

    if ($resistance) {
        foreach ($resistance as $result) {
            ?>
            <div class="search-result">
                <div class="list-news-title"><?= Html::a($result['title'.$langInt], ['/resistance/view', 'id' => $result['id']],['class'=>'news_title']); ?></div>
                <?php
                $text = word_limiter($result['text'.$langInt], 50);
                $text = preg_replace("/{$_POST['search']}/i", "<span class='founded' >{$_POST['search']}</span>", $text);
                echo Html::tag('div',$text,['class'=>'news_description']);?>
            </div>
            <?php
        }
    }
    if (!$news && !$report && !$politics && !$resistance)
        echo Yii::t('app', 'Не найдено результатов');
    ?>
</div>