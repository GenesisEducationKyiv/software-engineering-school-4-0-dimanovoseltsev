<?php

namespace app\currencies\infrastructure\repositories;

use app\currencies\domain\entities\Currency;
use app\currencies\domain\repositories\CurrencyRepositoryInterface;
use app\currencies\infrastructure\mappers\Mapper;
use app\currencies\infrastructure\models\CurrencyQuery;
use app\shared\application\exceptions\NotValidException;
use yii\db\Exception;

class CurrencyRepository implements CurrencyRepositoryInterface
{
    /**
     * @param CurrencyQuery $query
     */
    public function __construct(
        private readonly CurrencyQuery $query,
    ) {
    }

    /**
     * @param string $code
     * @return Currency|null
     */
    public function getByCode(string $code): ?Currency
    {
        $model = $this->query->findByCode($code);
        return $model === null ? null : Mapper::toEntity($model);
    }

    /**
     * @param Currency $currency
     * @return Currency
     * @throws NotValidException
     * @throws Exception
     */
    public function save(Currency $currency): Currency
    {
        $values = Mapper::toModelAttributes($currency);
        return Mapper::toEntity($this->query->save($values));
    }
}
