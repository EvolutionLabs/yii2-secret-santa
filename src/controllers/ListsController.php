<?php declare(strict_types=1);

namespace evo\ssanta\controllers;

use Yii;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use frontend\yii\web\Controller;
use evo\ssanta\models\{
    SecretSantaList,
    SecretSantaListSearch,
    SecretSantaListHandler,
    SecretSantaListMember,
    SecretSantaListPairSearch
};

/**
 * Class ListsController
 * @package evo\ssanta\controllers
 */
class ListsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'delete'       => ['POST'],
                    'create-pairs' => ['POST'],
                ],
            ],
        ]);
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel          = new SecretSantaListSearch();
        $searchModel->user_id = (int)Yii::$app->user->identity->id;
        $dataProvider         = $searchModel->search(request()->queryParams);

        /* render the view */
        return $this->render('list', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        /* @var SecretSantaList */
        $model = new SecretSantaList();

        /* @var SecretSantaListMember */
        $member = new SecretSantaListMember();

        /* @var SecretSantaListMember[] */
        $members = [];

        /* when form is submitted */
        if ($model->load(request()->post())) {
            /* create empty models */
            for ($i = 0; $i < count((array)request()->post($member->formName(), [])); $i++) {
                $members[] = new SecretSantaListMember();
            }

            /* load data into the newly created models */
            Model::loadMultiple($members, request()->post());

            /* start the transaction so we can rollback at errors */
            $transaction = db()->beginTransaction();

            /* whether the action is successful or not */
            $success = false;

            try {
                $model->user_id = Yii::$app->user->identity->id;
                $model->status = SecretSantaList::STATUS_DRAFT;

                /* in case we cannot save the model, we stop */
                if (!$model->save()) {
                    throw new \Exception(Html::errorSummary($model));
                }

                /* make sure the members get the right attribute id */
                foreach ($members as $memberModel) {
                    $memberModel->list_id = $model->id;
                }
                /* validate all the models now */
                if (!Model::validateMultiple($members)) {
                    throw new \Exception(Html::errorSummary($members));
                }

                /* save the attribute members */
                foreach ($members as $memberModel) {
                    $memberModel->save(false);
                }

                /* all went okay, we can commit the transaction */
                $transaction->commit();

                $success = true;

            } catch (\Exception $e) {
                /* something is wrong, rollback */
                $transaction->rollBack();
            }

            if ($success) {
                session()->setFlash('success', t('app', 'Your form has been successfully saved!'));
                return $this->redirect(['index']);
            }
            session()->setFlash('error', t('app', 'Your form has a few errors, please fix them and try again!'));
        }

        /* render the view */
        return $this->render('form', [
            'model'   => $model,
            'member'  => $member,
            'members' => $members,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate($id)
    {
        /* @var SecretSantaList */
        $model = $this->findModel((int)$id);

        /* @var SecretSantaListMember */
        $member = new SecretSantaListMember();

        /* @var SecretSantaListMember[] */
        $members = !empty($model->members) ? $model->members : [];

        /* when form is submitted */
        if ($model->load(request()->post())) {
            /* reset the array because we are going to re-populate it with fresh info */
            $members = [];

            /* keep a list of found items so that later we can remove all items for this attribute but these */
            $foundMembers = [];

            /* check the incoming data, instantiate existing models and create new ones */
            foreach ((array)request()->post($member->formName(), []) as $attributes) {

                $memberModel = null;

                /* if the id is set, it means the member exists and needs an update */
                if (!empty($attributes['id']) && (int)$attributes['id'] > 0) {
                    $memberModel = SecretSantaListMember::findOne([
                        'id'                  => (int)$attributes['id'],
                        'list_id' => $model->id,
                    ]);
                }

                /* this means the member is newly created */
                if (empty($memberModel)) {
                    $memberModel = new SecretSantaListMember();
                }

                /* add the attributes */
                $memberModel->attributes   = $attributes;
                $memberModel->list_id = $model->id;

                $members[] = $memberModel;

                if ($memberModel->id) {
                    $foundMembers[] = $memberModel->id;
                }
            }

            /* start the transaction so we can rollback at errors */
            $transaction = db()->beginTransaction();

            /* whether the action is successful or not */
            $success = false;

            try {

                /* in case we cannot save the model, we stop */
                if (!$model->save()) {
                    throw new \Exception(Html::errorSummary($model));
                }

                /* find the members we have to remove and remove them */
                $removedMembers = SecretSantaListMember::find()->where(['list_id' => $model->id]);
                if (!empty($foundMembers)) {
                    $removedMembers->andWhere(['not in', 'id', $foundMembers]);
                }

                foreach ($removedMembers->each(10) as $removedMember) {
                    $removedMember->delete();
                }

                /* make sure the members get the right attribute id */
                foreach ($members as $memberModel) {
                    $memberModel->list_id = $model->id;
                }

                /* validate all the models now */
                if (!Model::validateMultiple($members)) {
                    throw new \Exception(Html::errorSummary($members));
                }

                /* save the attribute members */
                foreach ($members as $memberModel) {
                    $memberModel->save(false);
                }

                /* all went okay, we can commit the transaction */
                $transaction->commit();

                $success = true;

            } catch (\Exception $e) {
                /* something is wrong, rollback */
                $transaction->rollBack();
            }

            if ($success) {
                session()->setFlash('success', t('app', 'Your form has been successfully saved!'));
                return $this->redirect(['index']);
            }
            session()->setFlash('error', t('app', 'Your form has a few errors, please fix them and try again!'));
        }

        /* render the view */
        return $this->render('form', [
            'model'   => $model,
            'member'  => $member,
            'members' => $members,

        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
	    $model = $this->findModel((int)$id);
        $model->delete();
	    return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionCreatePairs($id)
    {
        $handler = new SecretSantaListHandler([
            'listId' => (int)$id,
            'userId' =>  (int)Yii::$app->user->identity->id
        ]);
        $handler->run();
        if ($handler->hasErrors()) {
            notify()->addError(Html::errorSummary($handler));
            //Yii::$app->session->setFlash('error', Html::errorSummary($handler));
        } else {
            notify()->addSuccess(t('app', 'Secret Santa pairs created with success'));
            notify()->addInfo($handler->getFromToEmails());
            //Yii::$app->session->setFlash('success', t('app', 'Secret Santa pairs created with success'));
        }
        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSend($id)
    {
        $model = $this->findModel((int)$id);

        if (!$model->send()) {
            notify()->addError(t('app', 'The list was not queued for sending'));
        } else {
            notify()->addSuccess(t('app', 'The list was queued for sending successfully'));
        }
        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionPairs($id)
    {
        $searchModel          = new SecretSantaListPairSearch();
        $searchModel->list_id = (int)$id;
        $dataProvider         = $searchModel->search(request()->queryParams);

        /* render the view */
        return $this->render('pairs', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param int $id
     * @return SecretSantaList
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): SecretSantaList
    {
        $attributes = [
            'id'      => (int)$id,
            'user_id' => (int)Yii::$app->user->identity->id
        ];

        if (($model = SecretSantaList::findOne($attributes)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(t('app', 'The requested page does not exist.'));
    }
}
