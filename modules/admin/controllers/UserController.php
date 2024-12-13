<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

class UserController extends Controller
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
                            return Yii::$app->user->identity->isAdmin(); // Перевірка на адміністратора
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    // Інші дії: view, create, update, delete

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException якщо модель не знайдена
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * Якщо створення успішне, перенаправляє на сторінку перегляду.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->scenario = 'create'; // Встановлення сценарію створення

        if ($model->load(Yii::$app->request->post())) {
            // Обробка завантаження зображення
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->image) {
                $uniqueName = uniqid() . '.' . $model->image->extension;
                $model->image->saveAs(Yii::getAlias('@webroot/uploads/') . $uniqueName);
                $model->image = $uniqueName;
            }

            // Створення користувача
            if ($model->create()) {
                Yii::$app->session->setFlash('success', 'Користувача успішно створено.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing User model.
     * Якщо оновлення успішне, перенаправляє на сторінку перегляду.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException якщо модель не знайдена
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update'; // Встановлення сценарію оновлення
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

            // Оновлення користувача
            if ($model->updateUser()) {
                Yii::$app->session->setFlash('success', 'Користувача успішно оновлено.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing User model.
     * Якщо видалення успішне, перенаправляє на сторінку списку.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException якщо модель не знайдена
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        // Видалення користувача разом із зображенням
        if ($model->image && file_exists(Yii::getAlias('@webroot/uploads/') . $model->image)) {
            unlink(Yii::getAlias('@webroot/uploads/') . $model->image);
        }
        $model->delete();

        Yii::$app->session->setFlash('success', 'Користувача успішно видалено.');

        return $this->redirect(['index']);
    }

    /**
     * Знаходить модель User за її первинним ключем.
     * Якщо модель не знайдена, кидає 404 HTTP виняток.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException якщо модель не знайдена
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запитувана сторінка не існує.');
    }
}
