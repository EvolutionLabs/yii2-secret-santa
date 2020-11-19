<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use evolutionlabs\ssanta\{
    assets\ListsAsset,
    models\SecretSantaList,
    models\SecretSantaListMember
};

/**
 * These are the classes and ids that are used as selectors in the js file.
 *
 * #list-members-wrapper
 * #template-list-members
 *
 * .list-member-item
 * .btn-add-list-member
 * .btn-delete-list-member
 */
ListsAsset::register($this)

/**
 * @var yii\web\View                   $this
 * @var SecretSantaList                $model
 * @var SecretSantaListMember[]        $members
 */

?>
<div class="box box-primary pulse-attribute-create">
    <div class="box-header">
        <div class="pull-left">
        </div>
        <div class="pull-right">
            <?php echo Html::a('<i class="fa fa-plus-circle"></i>' . t('app', 'Back'), ['index'], ['class' => 'btn btn-outline-secondary']); ?>
        </div>
        <div class="clearfix"><!-- --></div>
    </div>
    <?php $form = ActiveForm::begin([
        'id'                     => 'secret-santa-list-form',
        'enableClientValidation' => false,
    ]) ?>

    <div class="card">
        <div class="card-header">
            <?php echo html_encode(t('app', 'New Secret Santa list'));?>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]); ?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <?php echo $form->field($model, 'budget')->input(['type' => 'number']); ?>
                </div>
            </div>
            <div class="card list-members">
                <div class="card-header">
                    <div class="pull-left">
                        <?php echo Html::a('<i class="fa fa-plus-square"></i>' . t('app', 'Add member'), 'javascript:;', [
                            'class' => 'btn btn-success btn-add-list-member'
                        ]); ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row" id="list-members-wrapper">
                        <?php foreach ($members as $index => $memberModel) {
                            echo $this->context->renderPartial('members', [
                                'form'   => $form,
                                'member' => $memberModel,
                                'index'  => $index,
                            ]);
                        } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-muted">
            <div class="pull-right">
                <button type="submit" class="btn btn-primary btn-sm"><?php echo '<i class="fa fa-save"></i>' . t('app', 'Save changes');?></button>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<div id="template-list-members" style="display: none;">
    <?php echo $this->context->renderPartial('members', [
        'form'   => $form,
        'member' => $member,
        'index'  => '{INDEX}',
    ]);?>
</div>

