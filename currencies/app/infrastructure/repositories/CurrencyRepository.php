<?php

namespace app\infrastructure\repositories;

use app\application\exceptions\NotValidException;
use app\domain\entities\Currency;
use app\domain\repositories\CurrencyRepositoryInterface;
use app\infrastructure\mappers\Mapper;
use app\infrastructure\models\CurrencyQuery;
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
    public function findByCode(string $code): ?Currency
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
