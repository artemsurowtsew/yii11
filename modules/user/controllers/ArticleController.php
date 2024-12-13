<?php

namespace app\modules\user\controllers;

use Yii;
use app\models\Article;
use app\models\ArticleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

class ArticleController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Дозволено лише авторизованим користувачам
                        'matchCallback' => function($rule, $action) {
                            return !Yii::$app->user->identity->isAdmin(); // Перевірка на звичайного користувача
                        }
                    ],
                ],
                'denyCallback' => function($rule, $action) {
                    throw new \yii\web\NotFoundHttpException('Доступ заборонено.');
                },
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Article models for the current user.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        // Фільтруємо статті за поточним користувачем
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['user_id' => Yii::$app->user->id]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    // Інші дії: view, create, update, delete аналогічні Admin ArticleController, але з перевіркою автора

    /**
     * Displays a single Article model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException якщо модель не знайдена або не належить користувачу
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        // Перевірка, чи належить стаття поточному користувачу
        if ($model->user_id !== Yii::$app->user->id) {
            throw new NotFoundHttpException('Запитувана сторінка не існує.');
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Article model for the current user.
     * Якщо створення успішне, перенаправляє на сторінку перегляду.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Article();
        $model->user_id = Yii::$app->user->id; // Призначаємо автора поточному користувачу

        if ($model->load(Yii::$app->request->post())) {
            // Обробка завантаження зображення
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->image) {
                $uniqueName = uniqid() . '.' . $model->image->extension;
                $model->image->saveAs(Yii::getAlias('@webroot/uploads/') . $uniqueName);
                $model->image = $uniqueName;
            }

            // Збереження статті
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Статтю успішно створено.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing Article model.
     * Якщо оновлення успішне, перенаправляє на сторінку перегляду.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException якщо модель не знайдена або не належить користувачу
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        // Перевірка, чи належить стаття поточному користувачу
        if ($model->user_id !== Yii::$app->user->id) {
            throw new NotFoundHttpException('Запитувана сторінка не існує.');
        }

        $currentImage = $model->image;

        if ($model->load(Yii::$app->request->post())) {
            // Обробка завантаження зображення
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->image) {
                // Видалення старого зображення, якщо існує
                if ($currentImage && file_exists(Yii::getAlias('@webroot/uploads/') . $currentImage)) {
                    unlink(Yii::getAlias('@webroot/uploads/') . $currentImage);
                }
                $uniqueName = uniqid() . '.' . $model->image->extension;
                $model->image->saveAs(Yii::getAlias('@webroot/uploads/') . $uniqueName);
                $model->image = $uniqueName;
            } else {
                // Якщо зображення не було завантажено, залишаємо попереднє
                $model->image = $currentImage;
            }

            // Оновлення статті
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Статтю успішно оновлено.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing Article model.
     * Якщо видалення успішне, перенаправляє на сторінку списку.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException якщо модель не знайдена або не належить користувачу
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        // Перевірка, чи належить стаття поточному користувачу
        if ($model->user_id !== Yii::$app->user->id) {
            throw new NotFoundHttpException('Запитувана сторінка не існує.');
        }

        // Видалення статті разом із зображенням
        if ($model->image && file_exists(Yii::getAlias('@webroot/uploads/') . $model->image)) {
            unlink(Yii::getAlias('@webroot/uploads/') . $model->image);
        }
        $model->delete();

        Yii::$app->session->setFlash('success', 'Статтю успішно видалено.');

        return $this->redirect(['index']);
    }

    /**
     * Знаходить модель Article за її первинним ключем.
     * Якщо модель не знайдена, кидає 404 HTTP виняток.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException якщо модель не знайдена
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запитувана сторінка не існує.');
    }
}
