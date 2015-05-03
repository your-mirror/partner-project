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
use yii\web\Response;

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

        if (Yii::$app->request->post('hasEditable')) {
            $siteId = Yii::$app->request->post('editableKey');
            $site = $this->findSite($siteId);

            if (!\Yii::$app->user->can('crudOwnSite', ['author_id'=>$site->author_id]) && !\Yii::$app->user->can('admin')) {
                throw new NotFoundHttpException('Access denied');
            }

            $out = ['output'=>'', 'message'=>''];

            // fetch the first entry in posted data (there should
            // only be one entry anyway in this array for an
            // editable submission)
            // - $posted is the posted data for Book without any indexes
            // - $post is the converted array for single model validation
            $post = [];
            $posted = current($_POST['Sites']);
            $post['Sites'] = $posted;

            if ($site->load($post)) {
                $site->save();

                // custom output to return to be displayed as the editable grid cell
                // data. Normally this is empty - whereby whatever value is edited by
                // in the input by user is updated automatically.
                $output = '';

                // specific use case where you need to validate a specific
                // editable column posted when you have more than one
                // EditableColumn in the grid view. We evaluate here a
                // check to see if buy_amount was posted for the Book model
                if (isset($posted['status'])) {
                    $output =  $site->statuses[$posted['status']];
                }

                if (isset($posted['siteCallbackValue'])) {
                    $site->siteCallback->value = $posted['siteCallbackValue'];
                    $site->siteCallback->save();

                    $label = '';
                    switch ($site->siteCallback->type) {
                        case SiteCallback::TYPE_FORM:
                            $label = 'label-success';
                            break;
                        case SiteCallback::TYPE_SITE_CONTACT:
                            $label = 'label-info';
                            break;
                        case SiteCallback::TYPE_OTHER_CONTACT:
                            $label = 'label-danger';
                            break;
                    }

                    $output = '<p class="text-center"><span class="label '.$label.'">'.$site->siteCallback->types[$site->siteCallback->type] .'</span></p>';
                }

                // similarly you can check if the name attribute was posted as well
                // if (isset($posted['name'])) {
                //   $output =  ''; // process as you need
                // }
                $out = ['output'=>$output, 'message'=>''];
            }

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return $out;
        }

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
