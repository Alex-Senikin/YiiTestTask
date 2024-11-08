<?php
use yii\helpers\Html;
use yii\bootstrap5\Modal;
use yii\bootstrap5\ActiveForm;

Modal::begin([
  'id' => 'modalWin',
    'title' => '<h2>Оффер</h2>',
]);
 
$form = ActiveForm::begin([
    'id' => 'form',
]);
echo $form->field($model, 'id')->input('hidden')->label('');
echo $form->field($model, 'offerName');
echo $form->field($model, 'email')->input('email');
echo $form->field($model, 'phoneNumber');
echo $form->field($model, 'createdAt')->input('hidden')->label('');
echo '<div class="invalid-block"></div>';
echo Html::submitButton('Send', ['class' => 'btn btn-success']);
ActiveForm::end();
 
Modal::end();
?>