<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\Items;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
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
     * @return mixed
     */
    public function actionIndex()
    {
        $db = new \yii\db\Query();
        $request = Yii::$app->request;
        $get = $request->get();
        $sortby = $request->get('sortby');
        $filters = array(
            'lengthFrom' => isset($get['lengthFrom'])? $get['lengthFrom'] : '', 
            'lengthTo' => isset($get['lengthTo'])? $get['lengthTo'] : '', 
            'widthFrom' => isset($get['widthFrom'])? $get['widthFrom'] : '', 
            'widthTo' => isset($get['widthTo'])? $get['widthTo'] : '', 
            'heightFrom' => isset($get['heightFrom'])? $get['heightFrom'] : '', 
            'heightTo' => isset($get['heightTo'])? $get['heightTo'] : '', 
            'priceFrom' => isset($get['priceFrom'])? $get['priceFrom'] : '', 
            'priceTo' => isset($get['priceTo'])? $get['priceTo'] : '', 
        );
        // echo "<pre>";
        // var_dump($filters);
        // die;
        // echo "</pre>";
        if( is_null($request->get('order')) ) $order = 'ASC';
        else 
            !boolval($request->get('order'))?$order = 'ASC' : $order = 'DESC';
        $COLUMNS = ($db)
            ->select("*")
            ->from('INFORMATION_SCHEMA.COLUMNS')
            ->where(['table_name' => 'items', 'COLUMN_NAME' => $sortby])
            ->all();
        if( $COLUMNS != []) 
            $items = Items::find()
                ->orderBy($sortby.' '.$order )
                ->where(['>=', 'length', $filters['lengthFrom']])
                ->andFilterWhere(['<=', 'length', $filters['lengthTo']])
                ->andFilterWhere(['>=', 'width', $filters['widthFrom']])
                ->andFilterWhere(['<=', 'width', $filters['widthTo']])
                ->andFilterWhere(['>=', 'height', $filters['heightFrom']])
                ->andFilterWhere(['<=', 'height', $filters['heightTo']])
                ->andFilterWhere(['>=', 'price', $filters['priceFrom']])
                ->andFilterWhere(['<=', 'price', $filters['priceTo']])
                ->all();
        else {
            $items = Items::find()
                ->orderBy('id'.' '.$order)
                ->where(['>=', 'length', $filters['lengthFrom']])
                ->andFilterWhere(['<=', 'length', $filters['lengthTo']])
                ->andFilterWhere(['>=', 'width', $filters['widthFrom']])
                ->andFilterWhere(['<=', 'width', $filters['widthTo']])
                ->andFilterWhere(['>=', 'height', $filters['heightFrom']])
                ->andFilterWhere(['<=', 'height', $filters['heightTo']])
                ->andFilterWhere(['>=', 'price', $filters['priceFrom']])
                ->andFilterWhere(['<=', 'price', $filters['priceTo']])
                ->all();
        }
        
        return $this->render('@common/views/indexProducts', [ 'items' => $items ]);
    }
}
