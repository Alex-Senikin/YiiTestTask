<?php

namespace app\controllers;

use app\models\Offers;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;

/**
 * OffersController implements the CRUD actions for Offers model.
 */
class OffersController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Offers models.
     *
     * @return string
     */
    public function actionIndex()
    {
        //Берем из БД все данные и делаем пагиацию
        $query = Offers::find();
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 10, 
        'forcePageParam' => false, 'pageSizeParam' => false]);
        $offers = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();
        //Передаем данные на главную страницу
        return $this->render('index', [
            'offers' => $offers,
            'pages' => $pages,
        ]);
    }

    /**
     * Displays a single Offers model.
     * @param int $id ID
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView()
    {
        // Получение оффера для заполнения модалки
        if (\Yii::$app->request->isAjax && \Yii::$app->request->post('id')) {
            //Берем из ajax ID оффер
            $id = \Yii::$app->request->post('id');
            //Ищем оффер по ID
            $model = $this->findModel($id);
            //Если оффер существует в БД отправляем его данные на front
            if ($model) {
                return $this->asJson([
                    'success' => true,
                    'data' => [
                        'id' => $model->id,
                        'offerName' => $model->offerName,
                        'email' => $model->email,
                        'phoneNumber' => $model->phoneNumber,
                        'createdAt' => $model->createdAt,
                    ]
                ]);
            } else {
                return $this->asJson([
                    'success' => false,
                    'error' => 'Offer not found'
                ]);
            }
        }
    }

    /**
     * Creates a new Offers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        // Создание нового оффера
        $offer = new Offers();
        if (\Yii::$app->request->isAjax) {
            if ($offer) {
                //Заполняем модель данными
                $offer->offerName = \Yii::$app->request->post('offerName');
                $offer->email = \Yii::$app->request->post('email');
                $offer->phoneNumber = \Yii::$app->request->post('phoneNumber');
                $offer->createdAt = strtotime('now');
                //Валидируем данные по правилам указанным в модели Offers
                if ($offer->validate()) {
                    //Если валидация прошла успешно сохраняем оффер в БД
                    $offer->save();
                    return $this->asJson([
                        'success' => true,
                        'data' => [
                            'id' => $offer->id,
                        ],
                        // Формируем html код для вставки на страницу нового оффера
                        'html' => '<div class="offerblock" id="'.$offer->id.'">
                        <div class="offer" onclick="view($(this).parent()[0].id)">
                        <div class="offerID">'.$offer->id.'</div>
                        <div class="offerName">'. $offer->offerName.'</div>
                        <div class="offerEmail">'.$offer->email.'</div>
                        <div class="offerPhone">+'.$offer->phoneNumber.'</div>
                        <div class="offerCreated">'.date("d.m.Y", $offer->createdAt).'
                        </div>
                        </div>
                        <img class="trash" src="/images/recycle-bin.png" 
                        onclick="del($(this).parent()[0].id)">
                        </div>'
                    ]);
                } else {
                    return $this->asJson([
                        'success' => false,
                        'error' => $offer->errors,
                    ]);
                }
            }
        }
    }

    /**
     * Updates an existing Offers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        //Изменение данных оффера
        if (\Yii::$app->request->isAjax) {
            //Находим оффер по ID полученному из AJAX
            $id = \Yii::$app->request->post('id');
            $offer = $this->findModel($id);
            if ($offer) {
                //Заполняем модель данными
                $offer->offerName = \Yii::$app->request->post('offerName');
                $offer->email = \Yii::$app->request->post('email');
                $offer->phoneNumber = \Yii::$app->request->post('phoneNumber');
                //Валидируем данные по правилам указанным в модели Offers
                if ($offer->validate()) {
                    //Если валидация прошла успешно обновляем оффер в БД
                    $offer->update();
                    return $this->asJson([
                        'success' => true,
                        'data' => [
                            'id' => $offer->id,
                        ],
                        // Формируем html код для вставки на страницу изменений оффера
                        'html' => '<div class="offer" onclick="view($(this).parent()[0].id)">
                        <div class="offerID">'.$offer->id.'</div>
                        <div class="offerName">'. $offer->offerName.'</div>
                        <div class="offerEmail">'.$offer->email.'</div>
                        <div class="offerPhone">+'.$offer->phoneNumber.'</div>
                        <div class="offerCreated">'.date("d.m.Y", $offer->createdAt).'
                        </div>
                        </div>
                        <img class="trash" src="/images/recycle-bin.png" 
                        onclick="del($(this).parent()[0].id)">'
                    ]);
                } else {
                    return $this->asJson([
                        'success' => false,
                        'error' => $offer->errors,
                    ]);
                }
            }
        }
    }

    /**
     * Deletes an existing Offers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {
        // Удаление оффера
        if (\Yii::$app->request->isAjax) {
            //Находим оффер по ID полученному из AJAX
            $id = \Yii::$app->request->post('id');
            $offer = $this->findModel($id);
            if ($offer) {
                //Если оффер существует в БД удаляем его
                $offer->delete();
                return $this->asJson([
                    'success' => true,
                    'data' => [
                        'id' => $offer->id,
                    ]
                ]);
            };
        }
    }

    public function actionSort()
    {
        //Сортировка офферов
        if (\Yii::$app->request->isAjax) {
            //Получаем из AJAX по какому полю и в какую сторону сортировать
            $sortAs = \Yii::$app->request->post('sortAs');
            $sortDirection = \Yii::$app->request->post('sortDirection');
            //Проверяем на какой странице находимся
            if ($page = \Yii::$app->request->post('page')) {
                $page = intval(\Yii::$app->request->post('page'));
            } else {
                $page = 1;
            };
            //Ищем офферы на нужной странице
            $query = Offers::find();
            $offers = $query->offset(($page - 1) * 10)->limit(10)->all();
            //Применяем сортировку к офферам
            usort($offers, function($a, $b) use ($sortAs, $sortDirection) {
                if ($sortDirection == 'ASC') {
                    return $a->$sortAs <=> $b->$sortAs;
                } else {
                    return $b->$sortAs <=> $a->$sortAs;
                }
            });
            // Формируем html код для вставки на страницу отсортированных офферов
            $data ='<div class="offers">';
            foreach ($offers as $offer) {
                $data = $data.'<div class="offerblock" id="'.$offer->id.'">
                    <div class="offer" onclick="view($(this).parent()[0].id)">
                    <div class="offerID">'.$offer->id.'</div>
                    <div class="offerName">'. $offer->offerName.'</div>
                    <div class="offerEmail">'.$offer->email.'</div>
                    <div class="offerPhone">+'.$offer->phoneNumber.'</div>
                    <div class="offerCreated">'.date("d.m.Y", $offer->createdAt).'
                    </div>
                    </div>
                    <img class="trash" src="/images/recycle-bin.png" 
                    onclick="del($(this).parent()[0].id)">
                    </div>';
            };
            $data = $data.'</div>';
            return $this->asJson([
                'success' => true,
                'res' => $offers,
                'html' => $data,
            ]);
        }
    }

    public function actionFind()
    {
        //Поиск офферов по Email и названию оффера
        if(\Yii::$app->request->isAjax){
            $findParam = \Yii::$app->request->post('findParam');
            $query = Offers::find();
            //Проверяем если пришел пустой запрос выводим офферы на страницу
            if ($findParam == '') {
                $offers = $query->limit(10)->all();
            } else {
                //Если пришли даные для поиска ищем офферы
                $offers = $query->where(['like', 'offerName', $findParam])
                    ->orWhere(['like', 'email', $findParam])->limit(10)->all();
            }
            // Формируем html код для вставки на страницу отфильтрованных офферов
            $data = '<div class="offers">';
            foreach ($offers as $offer) {
                $data = $data.'<div class="offerblock" id="'.$offer->id.'">
                    <div class="offer" onclick="view($(this).parent()[0].id)">
                    <div class="offerID">'.$offer->id.'</div>
                    <div class="offerName">'. $offer->offerName.'</div>
                    <div class="offerEmail">'.$offer->email.'</div>
                    <div class="offerPhone">+'.$offer->phoneNumber.'</div>
                    <div class="offerCreated">'.date("d.m.Y", $offer->createdAt).'
                    </div>
                    </div>
                    <img class="trash" src="/images/recycle-bin.png" 
                    onclick="del($(this).parent()[0].id)">
                    </div>';
            };
            $data = $data.'</div>';
            return $this->asJson([
                'success' => true,
                'res' => $offers,
                'html' => $data,
            ]);
        }
    }
    /**
     * Finds the Offers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Offers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Offers::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
