<?php

namespace evolutionlabs\ssanta\assets;

use yii\web\AssetBundle;

/**
 * Secret santa list asset bundle.
 */
class ListsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@evolutionlabs/ssanta/assets/source';

    public $js = [
        'js/lists.js'
    ];
}
