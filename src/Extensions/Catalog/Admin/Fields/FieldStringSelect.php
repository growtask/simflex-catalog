<?php
namespace App\Extensions\Catalog\Admin\Fields;

use Simflex\Admin\Fields\Field;
use Simflex\Core\DB;

class FieldStringSelect extends Field
{
    public function input($value)
    {
        $select = '<div class="form-control form-control--sm">
                        <div class="form-control__dropdown" data-action="searchString" data-ajax="true">
                            <div class="form-control__dropdown-top">
                                <input class="form-control__dropdown-input" onchange="'.$this->onchange.'" value="'.(!$value ? '' : $value).'" type="hidden" name="' . $this->name . '" >
                               <input placeholder="Начните вводить название..." class="form-control__dropdown-text" type="text">
                                <div class="form-control__dropdown-current">'.$value.'</div>
                                <button type="button" class="form-control__dropdown-toggle">
                                    <svg viewBox="0 0 24 24">
                                        <use xlink:href="'.asset('img/icons/svg-defs.svg').'#chevron-mini"></use>
                                    </svg>
                                </button>
                            </div>
                            <div class="form-control__dropdown-list">
                                           
                                        ';

        $select .= '<div data-value="'.$value.'" class="form-control__dropdown-item">'.$value.'</div>';
        $select .= '</div>
            </div>
        </div>';

        return $select;
    }
}