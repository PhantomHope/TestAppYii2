<?php 
namespace common\widgets;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Products extends Widget
{
	public $message;
	public $items;

    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = 'Hello World';
        }
        if ($this->items === null) {
        	$this->items = [];
        }
    }

    public function run()
    {
        return Html::encode();
    }

	public function renderItems($value='')
    {
    	if( $this->items === [] ) return Html::tag('p', 'No data');

    	
    }
}

?>