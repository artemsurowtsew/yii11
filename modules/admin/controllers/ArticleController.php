<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Article;
use app\models\ArticleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
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
                'denyCallback' => function($rule, $action) {
                    throw new \yii\web\NotFoundHttpException('Доступ заборонено.');
                },
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Дозволено лише авторизованим користувачам
                        'matchCallback' => function($rule, $action) {
                            return Yii::$app->user->identity->login === 'antony@gmail.com'; // Перевірка на адміністратора
                        }
                    ],
                ],
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
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        // Видаліть або коментуйте наступний рядок, щоб відображати всі пости
        // $searchModel->user_id = Yii::$app->user->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Article model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->checkAccess($id); // Перевірка доступу
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Article();
        $model->user_id = Yii::$app->user->id; // Автоматичне додавання user_id

        if ($model->load(Yii::$app->request->post())) {
            // Обробка завантаження зображення
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->image && $model->validate()) {
                $uniqueName = uniqid() . '.' . $model->image->extension;
                $model->image->saveAs(Yii::getAlias('@webroot/uploads/') . $uniqueName);
                $model->image = $uniqueName;
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $topics = \yii\helpers\ArrayHelper::map(\app\models\Topic::find()->all(), 'id', 'name');

        return $this->render('create', [
            'model' => $model,
            'topics' => $topics,
        ]);
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $this->checkAccess($id); // Перевірка доступу
        $model = $this->findModel($id);
        $currentImage = $model->image;

        if ($model->load(Yii::$app->request->post())) {
            // Обробка завантаження зображення
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->image && $model->validate()) {
                // Видалення старого зображення, якщо існує
                if ($currentImage && file_exists(Yii::getAlias('@webroot/uploads/') . $currentImage)) {
                    unlink(Yii::getAlias('@webroot/uploads/') . $currentImage);
                }
                $uniqueName = uniqid() . '.' . $model->image->extension;
                $model->image->saveAs(Yii::getAlias('@webroot/uploads/') . $uniqueName);
                $model->image = $uniqueName;
            } else {
                $model->image = $currentImage;
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $topics = \yii\helpers\ArrayHelper::map(\app\models\Topic::find()->all(), 'id', 'name');

        return $this->render('update', [
            'model' => $model,
            'topics' => $topics,
        ]);
    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->checkAccess($id); // Перевірка доступу
        $model = $this->findModel($id);
        // Видалення зображення
        if ($model->image && file_exists(Yii::getAlias('@webroot/uploads/') . $model->image)) {
            unlink(Yii::getAlias('@webroot/uploads/') . $model->image);
        }
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запитувана сторінка не існує.');
    }

    /**
     * Перевірка доступу до редагування або видалення посту
     * @param integer $id
     * @throws NotFoundHttpException
     */
    protected function checkAccess($id)
    {
        $model = $this->findModel($id);
        if ($model->user_id !== Yii::$app->user->id && Yii::$app->user->identity->login !== 'antony@gmail.com') {
            throw new NotFoundHttpException('Доступ заборонено.');
        }
    }
}
