GridView::widget([

'dataProvider' => $dataProvider,

'columns' => [

    ['class' => 'yii\grid\SerialColumn'],

    'id',

    'name',

    'login',

    'password',

    [

        'format' => 'html',

        'label' => 'Image',

        'value' => function ($data) {

            return Html::img($data->getImage(), ['width' => 200]);

        }

    ],

    ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {update}'],

],

]); 