<?php

namespace vthang87\workflow\assets;

use yii\web\AssetBundle;

/**
 * Created by PhpStorm.
 * User: dangvanthang
 * Date: 5/30/18
 * Time: 11:29 AM
 */
class WorkflowAsset extends AssetBundle
{
    public $sourcePath = '@bower/vis/dist';
    public $depends = [
        'yii\jui\JuiAsset',
    ];

    public function init()
    {
        $this->js = [
            'vis' . (YII_ENV_DEV ? '.js' : '.min.js'),
        ];
        $this->css = [
            'vis' . (YII_ENV_DEV ? '.css' : '.min.css'),
        ];
        return parent::init();
    }
}
