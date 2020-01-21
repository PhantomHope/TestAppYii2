<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Item model
 *
 * @property integer $id
 * @property string $title
 * @property string $length
 * @property string $width
 * @property string $height
 * @property float $price
 */
class Items extends ActiveRecord
{
	/**
     * @return string название таблицы, сопоставленной с этим ActiveRecord-классом.
     */
    public static function tableName()
    {
        return '{{items}}';
    }

    public function rules() 
    {
    	return [
    		['id', 'integer'],
    		['title', 'string'],
    		['length', 'integer'],
    		['width', 'integer'],
    		['height', 'integer'],
    		['price', 'double'],
    		['length', 'compare', 'compareValue' => 0, 'operator' => '>'],
    		['width', 'compare', 'compareValue' => 0, 'operator' => '>'],
    		['height', 'compare', 'compareValue' => 0, 'operator' => '>'],
    		['price', 'compare', 'compareValue' => 0, 'operator' => '>'],
        ];
    }
}

?>