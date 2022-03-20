<?php

namespace common\components\totalCell;

use yii\base\InvalidConfigException;
use yii\grid\DataColumn;

/**
 * Class NumberColumn
 *
 * Returns the total of the selected column
 *
 */
class NumberColumn extends DataColumn
{
    private int $_total = 0;

    public function getDataCellValue($model, $key, $index): ?string
    {
        $value        = parent::getDataCellValue($model, $key, $index);
        $this->_total += $value;
        return $value;
    }

    protected function renderFooterCellContent(): string
    {
        return $this->grid->formatter->format($this->grid->formatter->asCurrency($this->_total), $this->format);
    }

}

