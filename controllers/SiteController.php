<?php

namespace app\controllers;



use Yii;
use app\models\Article;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Topic;
use app\models\Comment;
use app\models\SearchForm;

use app\models\CommentForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()

    {
    
    // build a DB query to get all articles
    
        $query = Article::find();
    
    // get the total number of articles (but do not fetch the article data yet)
    
        $count = $query->count();
    
    // create a pagination object with the total count
    
        $pagination = new Pagination(['totalCount' => $count, 'pageSize'=> 7]);
    
    // limit the query using the pagination and retrieve the articles
    
        $articles = $query->offset($pagination->offset)
    
            ->limit($pagination->limit)
    
            ->all();
    
        $popular = Article::find()->orderBy('viewed desc')->limit(3)->all();

        $recent = Article::find()->orderBy('date desc')->limit(3)->all();
    
        $topics = Topic::find()->all();

        return $this->render('index',[
    
            'articles'=>$articles,
    
            'pagination'=>$pagination,

            'popular' => $popular,

            'recent' => $recent,

            'topics' => $topics
            
        ]);

    
    }

    public function actionView($id)
    {
        
        $article = Article::find()->where(['id' => $id])->one();
        
        $article->viewedCounter();

        $popular = Article::find()->orderBy('viewed desc')->limit(3)->all();
        
        $recent = Article::find()->orderBy('date desc')->limit(3)->all();
        
        $topics = Topic::find()->all();

        $comments = $article->comments;

        $commentsParent = array_filter($comments, function ($k) {

    return $k['comment_id'] == null;

});

$commentsChild = array_filter($comments, function ($k) {

    return ($k['comment_id'] != null && !$k['delete']);

});

$commentForm = new CommentForm();
        
        return $this->render('single', [
        
        'article' => $article,
        
        'popular' => $popular,
        
        'recent' => $recent,
        
        'topics' => $topics,

        'commentsParent' => $commentsParent,

        'commentsChild' => $commentsChild,

        'commentForm' => $commentForm,
        
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome(); // If already logged in, redirect to homepage
        }
    
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack(); // Redirect to the last visited page or home
        }
    
        return $this->render('login', [
            'model' => $model,
        ]);
    }


    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        Yii::$app->session->setFlash('success', 'Ви успішно вийшли з системи.'); // Додаємо flash повідомлення


        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionComment($id, $id_comment = null)
{

    $model = new CommentForm();

    if (Yii::$app->request->isPost) {

        $model->load(Yii::$app->request->post());

        if ($model->saveComment($id, $id_comment)) {

            return $this->redirect(['site/view', 'id' => $id]);

        }

    }

}

public function actionCommentDelete($id, $id_comment)
{

    if (Yii::$app->request->isPost) {

        $data = Comment::findOne($id_comment);

        if ($data->user_id == Yii::$app->user->id) {

            $data->delete = true;

            $data->save(false);

        }

        return $this->redirect(['site/view', 'id' => $id]);

    }

}
public function actionTopic($id)
{
    $query = Article::find()->where(['topic_id' => $id]);

    $count = $query->count();

    // створення об'єкта пагінації
    $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 1]);

    // отримання статей з урахуванням пагінації
    $articles = $query->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();

    $popular = Article::find()->orderBy('viewed desc')->limit(3)->all();
    $recent = Article::find()->orderBy('date desc')->limit(3)->all();
    $topics = Topic::find()->all();

    return $this->render('topic', [
        'articles' => $articles,
        'pagination' => $pagination,
        'popular' => $popular,
        'recent' => $recent,
        'topics' => $topics,
    ]);
}

public function actionSearch()

{

    $model = new SearchForm();
    
    if (Yii::$app->request->isGet) {
    
        $model->load(Yii::$app->request->get());
        
        $data = $model->SearchAtricle(3);
        
        $popular = Article::find()->orderBy('viewed desc')->limit(3)->all();
        
        $recent = Article::find()->orderBy('date desc')->limit(3)->all();
        
        $topics = Topic::find()->all();
        
        return $this->render('search',[
        
            'articles' => $data['articles'],
            
            'pagination' => $data['pagination'],
            
            'popular' => $popular,
            
            'recent' => $recent,
            
            'topics' => $topics,
            
            'search' => $model->text
        
        ]);

    }

}

}
