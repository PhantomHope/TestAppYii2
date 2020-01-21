<?php

use yii\helpers\Html;
use common\assets\CommonAsset; 
/* @var $this yii\web\View */

CommonAsset::register($this);

$this->title = 'Test application';

?>
<div class="site-index">

<div class="product-tool">
    <i id="open-sort" class="material-icons" title="Sort by">format_list_numbered</i>

    <div class="sortBy-block">
        <div class="sortBy_wrap">
            <div class="openSort">
                <ul class="list-sort" aria-show="False">
                    <div class="title">
                        <h4 class="text-info">Chose sort by:</h4>
                    </div>
                    <li><a class="sort" data-sort='length'>Length</a></li>
                    <li><a class="sort" data-sort='width'>Width</a></li>
                    <li><a class="sort" data-sort='height'>Height</a></li>
                    <li><a class="sort" data-sort='price'>Price</a></li>
                </ul>
            </div>
        </div>
    </div>

    <i id="orderBtn" class="material-icons" title="Click for reverse order" data-order='0'>sort</i>
    <i class="material-icons" title="Choose filters" data-order='0' id="open-list">filter_list</i>


    <div class="filters-block">
        <form name="filters" onsubmit="return false">
            <div class="filt_wrap">
                <div class="openFilt">
                    <ul class="list-filters" aria-show="False">
                        <div class="title"><h4 class="text-info">Chose filter:</h4></div>
                        <li><a href="#filt-length" data-toggle='tab'>Length</a></li>
                        <li><a href="#filt-width" data-toggle='tab'>Width</a></li>
                        <li><a href="#filt-height" data-toggle='tab'>Height</a></li>
                        <li><a href="#filt-price" data-toggle='tab'>Price</a></li>
                    </ul>
                </div>
                <div class="tab-content tab-wrap">
                    <div class="tab-pane filt-tab" id='filt-length'><p>Filter length:</p><input type="text" name="lengthFrom" placeholder="From"><input type="text" name="lengthTo" placeholder="To"></div>
                    <div class="tab-pane filt-tab" id='filt-width'><p>Filter width:</p><input type="text" name="widthFrom" placeholder="From"><input type="text" name="widthTo" placeholder="To"></div>
                    <div class="tab-pane filt-tab" id='filt-height'><p>Filter height:</p><input type="text" name="heightFrom" placeholder="From"><input type="text" name="heightTo" placeholder="To"></div>
                    <div class="tab-pane filt-tab" id='filt-price'><p>Filter price:</p><input type="text" name="priceFrom" placeholder="From"><input type="text" name="priceTo" placeholder="To"></div>
                </div>
                <a id="resetFilter" class="disabled" title="Clear filters"><i class="material-icons">delete</i></a>
                <button type="submit" aria-show="False" disabled="">Apply</button>
            </div>
        </form>
    </div>
</div>

<ul class="products clearfix">
        
    <?php if($items == []) echo Html::tag('h3', 'No data', ['style' => ['text-align' => 'center']]); ?>

    <?php foreach ($items as $item): ?>
        <li class="product-wrapper">
            <div class="product">
                <div class="product-photo"><p>Here must be a picture of product</p></div>
                <div class="product-desc">
                    <h3 class="title"><?=Html::encode("{$item->title}")?></h3>
                    <ul>
                        <li>Length: <em><?= Html::encode("{$item->length}")?></em></li>
                        <li>Width: <em><?= Html::encode("{$item->width}")?></em></li>
                        <li>Height: <em><?= Html::encode("{$item->height}")?></em></li>
                        <li>Price: <em><?= Html::encode("{$item->price}")?>$</em></li>
                    </ul>
                </div>
                <a class="buy">Buy now</a>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
    
</div>
