<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

?>

<div class="box box-primary">
    <div class="box-header">
        <div class="pull-left">
        </div>
        <div class="pull-right">
            <?php echo Html::a('<i class="fa fa-plus-circle"></i>' . t('app', 'Back'), ['index'], ['class' => 'btn btn-sm btn-default']); ?>
        </div>
        <div class="clearfix"><!-- --></div>
    </div>
    <div class="box-body">
        <?php Pjax::begin(); ?>
        <?php echo GridView::widget([
            'dataProvider'  => $dataProvider,
            'filterModel'   => $searchModel,
            'columns'       => [
                [
                    'label' => 'Givers',
                    'format'    => 'raw',
                    'value'     => function(\evolutionlabs\ssanta\models\SecretSantaListPair $model) {
                        return !empty($model->giver) ? $model->giver->getDisplayName() : '';
                    },
                ],
                [
                    'label' => 'Receivers',
                    'format'    => 'raw',
                    'value'     => function(\evolutionlabs\ssanta\models\SecretSantaListPair $model) {
                        return !empty($model->receiver) ? $model->receiver->getDisplayName() : '';
                    },
                ],
                'status'
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
