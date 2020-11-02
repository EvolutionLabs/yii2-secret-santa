<div class="col-sm-12 list-member-item" style="padding-bottom: 10px;">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <?php echo $form->field($member, '[' . $index . ']id')->hiddenInput()->label(false);?>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <?php echo $form->field($member, '[' . $index . ']name')->textInput([
                        'maxlength' => true
                    ]); ?>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <?php echo $form->field($member, '[' . $index . ']email')->textInput([
                        'maxlength' => true
                    ]); ?>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <?php echo $form->field($member, '[' . $index . ']address')->textarea([
                        'maxlength' => true
                    ]); ?>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                    <label>&nbsp;</label>
                    <div class="clearfix"><!-- --></div>
                    <a href="javascript:;" class="btn btn-danger btn-flat btn-delete-list-member">x</a>
                </div>
            </div>
        </div>
    </div>
</div>
<hr />
