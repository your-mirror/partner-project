<?php

namespace app\modules\site\controllers;

use app\models\SitesSearch;
use Yii;
use app\models\SiteCallback;
use app\models\Sites;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * DefaultController implements the CRUD actions for Sites model.
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => 'app\components\AccessRule'
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin', 'manager']
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all Sites models.
     * @return mixed
     */
    public function actionIndex()
    {
        $GET = \Yii::$app->request->get();
        $searchModel  = \Yii::createObject(SitesSearch::className());
        $dataProvider = $searchModel->search($GET);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
            'siteModel'    => new Sites(),
        ]);
    }

    public function actionContact()
    {
        $GET = \Yii::$app->request->get();
        if(!isset($GET['SitesSearch']['status']))
            $GET['SitesSearch']['status'] = Sites::STATUS_WAIT_CONTACT;
        $searchModel  = \Yii::createObject(SitesSearch::className());
        $dataProvider = $searchModel->search($GET);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
            'siteModel'    => new Sites(),
        ]);
    }

    public function actionAnswer()
    {
        $GET = \Yii::$app->request->get();
        if(!isset($GET['SitesSearch']['status']))
            $GET['SitesSearch']['status'] = Sites::STATUS_WAIT_ANSWER;
        $searchModel  = \Yii::createObject(SitesSearch::className());
        $dataProvider = $searchModel->search($GET);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
            'siteModel'    => new Sites(),
        ]);
    }

    public function actionDeny()
    {
        $GET = \Yii::$app->request->get();
        if(!isset($GET['SitesSearch']['status']))
            $GET['SitesSearch']['status'] = Sites::STATUS_DENY;
        $searchModel  = \Yii::createObject(SitesSearch::className());
        $dataProvider = $searchModel->search($GET);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
            'siteModel'    => new Sites(),
        ]);
    }

    public function actionAgree()
    {
        $GET = \Yii::$app->request->get();
        if(!isset($GET['SitesSearch']['status']))
            $GET['SitesSearch']['status'] = Sites::STATUS_AGREE;
        $searchModel  = \Yii::createObject(SitesSearch::className());
        $dataProvider = $searchModel->search($GET);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
            'siteModel'    => new Sites(),
        ]);
    }

    /**
     * Displays a single Sites model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'site' => $this->findSite($id),
        ]);
    }

    /**
     * Creates a new Sites model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $site = new Sites();
        $siteCallback = new SiteCallback();

        $transaction = Yii::$app->db->beginTransaction();
        if($site->load(Yii::$app->request->post()) && $site->save()){
            $siteCallback->site_id = $site->id;
            if($siteCallback->load(Yii::$app->request->post()) && $siteCallback->save()){
                $transaction->commit();
                return $this->redirect('index');
            }
        }
        $transaction->rollBack();

        return $this->render('create', [
            'site' => $site,
            'siteCallback' => $siteCallback
        ]);
    }

    /**
     * Updates an existing Sites model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $site = $this->findSite($id);

        if (!\Yii::$app->user->can('crudOwnSite', ['author_id'=>$site->author_id]) && !\Yii::$app->user->can('admin')) {
            throw new NotFoundHttpException('Access denied');
        }

        $transaction = Yii::$app->db->beginTransaction();
        if($site->load(Yii::$app->request->post()) && $site->save()){
            if($site->siteCallback->load(Yii::$app->request->post()) && $site->siteCallback->save()){
                $transaction->commit();
                return $this->redirect('index');
            }
        }
        $transaction->rollBack();


        return $this->render('update', [
            'site' => $site,
            'siteCallback' => $site->siteCallback
        ]);
    }

    /**
     * Deletes an existing Sites model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $site = $this->findSite($id);

        if (!\Yii::$app->user->can('crudOwnSite', ['author_id'=>$site->author_id]) && !\Yii::$app->user->can('admin')) {
            throw new NotFoundHttpException('Access denied');
        }

        $site->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Sites model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sites the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findSite($id)
    {
        if (($model = Sites::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
