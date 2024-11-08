<?php

use yii\helpers\Html;
use app\widgets\ModalWidget;
use yii\bootstrap5\LinkPager;
use yii\web\View;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
$this->title = 'Offers';
$this->params['breadcrumbs'][] = $this->title;
// подключени файла со скриптами
$this->registerJsFile('/scripts/scripts.js', ['position' => View::POS_END]);


?>
<div class="notifications"></div>
<?= ModalWidget::widget([]) ?>

<div class="offers-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="createButton">
		<button class="create">Создать оффер</button>
		<input class="filter" placeholder="Поиск"/>
	</div>
	<div class="offerHead">
		<div class="offerID" id="id" tag="DESC">ID</div>
		<div class="offerName" id="offerName">Название оффера</div>
		<div class="offerEmail">E-mail</div>
		<div class="offerPhone">Номер телефона</div>
		<div class="offerCreated">Дата создания</div>
	</div>
	<div class="offers">
		<?php foreach ($offers as $offer): ?>
		<div class="offerblock" id=<?= $offer->id ?>>
            <div class="offer" onclick="view($(this).parent()[0].id)">
			<div class="offerID"><?= $offer->id ?></div>
			<div class="offerName"><?= $offer->offerName ?></div>
			<div class="offerEmail"><?= $offer->email ?></div>
			<div class="offerPhone">+<?= $offer->phoneNumber ?></div>
			<div class="offerCreated"><?= date("d.m.Y", $offer->createdAt) ?></div>
            </div>
            <img class="trash" src="/images/recycle-bin.png" onclick="del($(this).parent()[0].id)">
        </div>
		<?php endforeach?>
	</div>

</div>

<?= LinkPager::widget([
    'pagination' => $pages,
]); ?>