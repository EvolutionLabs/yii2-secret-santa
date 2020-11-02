<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
?>

<div class="box box-primary">
    <div class="box-header">
        <div class="pull-left">
        </div>
        <div class="pull-right">
            <?php echo Html::a('<i class="fa fa-plus-circle"></i>' . t('app', 'Create'), ['create'], ['class' => 'btn btn-sm btn-default']); ?>
            <?php echo Html::a('<i class="fa fa-arrow-circle-left"></i>' . t('app', 'Back'), ['index'], ['class' => 'btn btn-sm btn-default']); ?>
        </div>
        <div class="clearfix"><!-- --></div>
    </div>
    <div class="box-body">
        <?php Pjax::begin(); ?>
        <?php echo GridView::widget([
            'dataProvider'  => $dataProvider,
            'filterModel'   => $searchModel,
            'columns'       => [
                'name',
                'status',
                [
                    'label' => 'Members',
                    'format'    => 'raw',
                    'value'     => function(\evo\ssanta\models\SecretSantaList $model) {
                        return implode('<br />', array_slice(array_values(ArrayHelper::map($model->members, 'id', 'email')), 0, 5));
                    },
                ],
                [
                    'class'   => yii\grid\ActionColumn::class,
                    'template' => '{create-pairs} {send} {pairs} {update} {delete}',
                    'buttons' => [
                        'create-pairs' => function ($url, $model, $key) {
                            return Html::a('<i class="fa fa-gifts"></i>', $url, [
                                'title'         => t('app', 'Create pairs'),
                                'aria-label'    => t('app', 'Create pairs'),
                                'data-method'   => 'post',
                                'data-pjax'     => '0',
                                'class'         => 'btn btn-success btn-xs',
                            ]);
                        },
                        'send' => function ($url, $model, $key) {
                            if ($model->status !== \evo\ssanta\models\SecretSantaList::STATUS_READY) {
                                return '';
                            }
                            return Html::a('<i class="fa fa-envelope"></i>', $url, [
                                'title'         => t('app', 'Send list'),
                                'aria-label'    => t('app', 'Send list'),
                                'data-confirm'  => t('app', 'Are you sure you want to send the pairs created for this list?'),
                                'data-method'   => 'post',
                                'data-pjax'     => '0',
                                'class'         => 'btn btn-info btn-xs',
                            ]);
                        },
                        'pairs' => function ($url, $model, $key) {
                            if ($model->status === \evo\ssanta\models\SecretSantaList::STATUS_DRAFT) {
                                return '';
                            }
                            return Html::a('<i class="fa fa-users"></i>' , $url, [
                                'title'      => t('app', 'See pairs'),
                                'aria-label' => t('app', 'See pairs'),
                                'data-pjax'  => '0',
                                'class'      => 'btn btn-primary btn-xs',
                            ]);
                        },
                        'update' => function ($url, $model, $key) {
                            return Html::a('<i class="fa fa-pencil-alt"></i>', $url, [
                                'title'      => t('app', 'Update'),
                                'aria-label' => t('app', 'Update'),
                                'data-pjax'  => '0',
                                'class'      => 'btn btn-primary btn-xs',
                            ]);
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<i class="fa fa-trash-alt"></i>', $url, [
                                'title'         => t('app', 'Delete'),
                                'aria-label'    => t('app', 'Delete'),
                                'data-confirm'  => t('app', 'Are you sure you want to delete this item?'),
                                'data-method'   => 'post',
                                'data-pjax'     => '0',
                                'class'         => 'btn btn-danger btn-xs',
                            ]);
                        }
                    ],
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
