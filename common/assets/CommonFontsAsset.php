<?php 

namespace common\assets;

use yii\web\AssetBundle;

/**
 * Common application asset bundle.
 */
class CommonFontsAsset extends AssetBundle
{
    public $sourcePath = '@bower/commonAssets/dist';
    public $css = [
    	'css/material-icons.css'
    ];
}

?>