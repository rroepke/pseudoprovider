<?php

namespace frontend\controllers;

use common\models\App;
use common\models\WebService;
use Yii;
use common\models\Cipher;
use common\models\Hash;
use common\models\Service;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Class ServiceController
 * @package frontend\controllers
 * @author Rene Roepke
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class ServiceController extends Controller
{
    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['register', 'index', 'delete', 'update'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'actions' => ['register','index', 'delete', 'update'],
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * Lists all Service models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Service::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Service model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Deletes an existing Service model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Service model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Service the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Service::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Registers a new service
     * @param string $type
     * @return string|\yii\web\Response
     */
    public function actionRegister($type = 'web'){
        $ciphers = ArrayHelper::map(Cipher::find()->all(), 'param', 'param');
        $hashes = ArrayHelper::map(Hash::find()->all(), 'param', 'param');

        $model = new Service(['scenario' => $type]);
        $model->type = $type;

        if ($model->load(Yii::$app->request->post())) {
            $id = Yii::$app->user->getId();
            $model->type = $type;
            $model->created_by = (is_null($id))?:$id;
            $model->timestamp = time();
            $model->token = hash('sha256',time().$model->name.$model->url);
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('register', [
            'ciphers' => $ciphers,
            'hashes' => $hashes,
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Service model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $type
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionUpdate($id, $type = 'web')
    {
        $ciphers = ArrayHelper::map(Cipher::find()->all(), 'param', 'param');
        $hashes = ArrayHelper::map(Hash::find()->all(), 'param', 'param');

        $model = $this->findModel($id);
        $model->scenario = $type;

        if ($model->created_by !== Yii::$app->user->id){
            throw new ForbiddenHttpException('You are not allowed to perform this action.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'ciphers' => $ciphers,
                'hashes' => $hashes,
                'model' => $model,
            ]);
        }
    }

    /**
     * Action to download communication handler file
     */
    public function actionDownload(){
        $path = Yii::getAlias('@webroot') . '/files';
        $file = $path . '/CommunicationHandler.php';

        if (file_exists($file)) {

            Yii::$app->response->sendFile($file);

        }
    }
}
