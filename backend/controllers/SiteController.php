<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
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
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'delete', 'edit', 'add'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'delete' => ['post'],
                    'edit' => ['post'],
                    'add' => ['post'],
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
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

        if (Yii::$app->user->identity->status == 777) {
            // return $this->render('indexAdmin');

            return $this->render('indexAdmin', [ 'items' => $items ]);
        }

        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionAdd(){
        if (Yii::$app->user->identity->status != 777)
            return Yii::$app->response->statusCode = 403;

        $request = Yii::$app->request;
        $newData = $request->bodyParams;

        $newItem = new Items();

        foreach ($newData as $key => $value) {
            if ($key == 'title') $newItem->{$key} = $value;
            else if ($key == 'price') 
                $newItem->{$key} = floatval($value);
            else $newItem->{$key} = intval($value);
        }

        if ($newItem && $newItem->validate()) {

            if( $newItem->save() ) 
                Yii::$app->response->statusCode = 200;
            else Yii::$app->response->statusCode = 520;
        } else Yii::$app->response->statusCode = 400;

    }

    /**
     * Get ajax action.
     *
     * return string
     */
    public function actionEdit()
    {
        if (Yii::$app->user->identity->status != 777)
            return Yii::$app->response->statusCode = 403;

        $request = Yii::$app->request;
        $updateObj = $request->bodyParams;

        $item = Items::findOne(['id' => $request->getBodyParam('id')]);

        if( $item ) {

            $arrKeys = [];
            foreach ($updateObj as $key => $value) {
                if( $key == 'id' ) continue;
                
                if ($key == 'price') 
                    $item->{$key} = floatval($value);
                else if ($key == 'title') 
                    $item->{$key} = $value;
                else $item->{$key} = intval($value);
            }

            if ($item->update(false) !== false) {
                // update successful
                $response = Yii::$app->response;
                $response->statusCode = 200;
                $response->format = \yii\web\Response::FORMAT_JSON;
                $response->data = $item;
            } else {
                // update failed
                Yii::$app->response->statusCode = 520;
            }
        } else Yii::$app->response->statusCode = 404;
        
    }

    public function actionDelete()
    {
        if (Yii::$app->user->identity->status != 777)
            return Yii::$app->response->statusCode = 403;

        $request = Yii::$app->request;

        $res = Items::findOne(['id' => $request->getBodyParam('id')]);

        if( $res ) { 

            if( !$res->delete() ) 
                Yii::$app->response->statusCode = 520;
            else Yii::$app->response->statusCode = 200;

        } else Yii::$app->response->statusCode = 404;

    }
}
