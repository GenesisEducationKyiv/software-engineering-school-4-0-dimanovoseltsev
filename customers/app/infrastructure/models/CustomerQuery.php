<?php

namespace app\infrastructure\models;

use app\application\exceptions\NotValidException;
use yii\db\Exception;

/**
 * @method CustomerQuery clear()
 */
class CustomerQuery extends BaseQuery
{
    /**
     * @param string $code
     * @return Customer|null
     */
    public function findByEmail(string $code): ?Customer
    {
        /** @var Customer */
        return $this->findOne(['email' => $code]);
    }

    /**
     * @param array<string, mixed> $values
     * @throws NotValidException|Exception
     */
    public function save(array $values): Customer
    {
        $id = $values['id'] ?? null;

        $model = $id === null
            ? $this->createModel($values)
            : $this->populateModel($values);

        if (!$model->save()) {
            throw new NotValidException($model->getErrors(), 'Customer not saved');
        }
        /** @var Customer */
        return $model;
    }

    /**
     * @param int $break
     * @return CustomerQuery
     */
    public function prepareNotSent(int $break): CustomerQuery
    {
        return $this->andWhere(['<=', 'last_send_at', time() - $break])
            ->orWhere(['IS', 'last_send_at', null]);
    }

    /**
     * @param string $email
     * @return int
     */
    public function deleteByEmail(string $email): int
    {
        return Customer::deleteAll(['email' => $email]);
    }
}
