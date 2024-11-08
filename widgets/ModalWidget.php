<?php
namespace app\widgets;
 
use Yii;
use yii\base\Widget;
use app\models\Offers;
 
class ModalWidget extends Widget
{
 
    public function run()
    {
        $model = new Offers();
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->session->setFlash('contactFormSubmitted');
        }

        

        return $this->render('/offers/modalWin', [
            'model' => $model,
        ]);
    }
 
}
?>