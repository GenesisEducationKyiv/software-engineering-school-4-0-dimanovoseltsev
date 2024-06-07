<?php

namespace app\models\query;

use app\models\Currency;

/**
 * Class CurrencyQuery.
 *
 * @package app\models\query
 *
 * @method CurrencyQuery clear()
 * @method Currency createModel(array $data = [], string $formName = '')
 */
class CurrencyQuery extends ActiveQuery
{
    /**
     * @param string $code
     * @return Currency|null
     */
    public function findByCode(string $code): ?Currency
    {
        /** @var ?Currency */
        return $this->where(['iso3' => mb_strtoupper($code)])->one();
    }
}
