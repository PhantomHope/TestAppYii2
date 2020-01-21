<?php 

namespace common\assets;

use yii\web\AssetBundle;

/**
 * Common application asset bundle.
 */
class CommonAsset extends AssetBundle
{
    public $sourcePath = '@common';
    public $css = [
    	'scripts/css/commonStyles.css'
    ];
    public $js = [
        'scripts/js/tools.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'common\assets\CommonFontsAsset',
    ];
}

?>