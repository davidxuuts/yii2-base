<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

use davidxu\uppy\Uppy;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var ActiveDataProvider $dataProvider
 */
$action = Url::current();

?>

<div class="modal-header">
    <h4 class="modal-title"><?= Yii::t('base', 'Select image') ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="attachment-selector-index card card-outline card-secondary">
        <div class="card-header">
            <h4 class="card-title"><?= Yii::t('base', 'Please select image') ?> </h4>
            <div class="card-tools">
                <div class="col text-right pb-3">
                    <div class="input-group input-group-sm">
                        <?= Html::input('text', 'key', '', [
                            'class' => 'form-control input-sm input-sm-2',
                            'id' => 'input-key',
                            'placeholder' => Yii::t('base', 'Search image name')
                        ])?>
                        <span class="input-group-append">
                            <?= Html::button('<i class="fas fa-search"></i>', [
                                'class' => 'btn btn-sm btn-default btn-flat',
                                'id' => 'search-img'
                            ]) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body pt-3 pl-0 pr-0">
            <?= $this->render('_selector_partial', [
                'dataProvider' => $dataProvider
            ]) ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <?= Html::button(Yii::t('app', 'Close'), [
        'class' => 'btn btn-secondary',
        'data-dismiss' => 'modal'
    ]) ?>
    <?= Html::button(Yii::t('base', 'Confirm'), ['class' => 'btn btn-primary']) ?>
</div>

<?php $js = /** @lang JavaScript */ <<< JS
$('#search-img').on('click', function(e) {
    e.preventDefault()
    $.ajax({
        url: '{$action}',
        data: {key: $('#input-key').val()},
        method: 'post',
        success: function (response) {
            $('#modal').find('.card-body').html(response)
        }
    })
})
$('.select-img').on('click', function() {
    console.log($(this))
    $(this).siblings().removeClass('img-selected')
    if ($(this).hasClass('img-selected')) {
        $(this).removeClass('img-selected')
    } else {
        $(this).addClass('img-selected')
    }
})
JS;
$this->registerJs($js);
