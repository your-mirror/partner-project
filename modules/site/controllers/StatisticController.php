<?php

namespace app\modules\site\controllers;

use Yii;
use app\models\Sites;
use app\models\SitesSearch;
use app\models\SiteCallback;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use app\models\UserSitesCountSearch;
use app\models\UserSitesPeriodSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * DefaultController implements the CRUD actions for Sites model.
 */
class StatisticController extends Controller
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
                        'roles' => ['admin']
                    ]
                ]
            ]
        ];
    }

    public function actionCount()
    {
        $searchModel = \Yii::createObject(UserSitesCountSearch::className());
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        return $this->render('count', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionPeriod()
    {
        $searchModel = \Yii::createObject(UserSitesPeriodSearch::className());
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        return $this->render('period', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}