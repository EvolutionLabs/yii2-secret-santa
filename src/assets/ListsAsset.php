<?php

namespace evo\ssanta\assets;

use yii\bootstrap4\BootstrapAsset;
use yii\web\AssetBundle;

/**
 * Secret santa list asset bundle.
 */
class ListsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@frontend/modules/ssanta/assets/source';

    public $js = [
        'js/lists.js'
    ];

    public $depends = [
        BootstrapAsset::class
    ];
}