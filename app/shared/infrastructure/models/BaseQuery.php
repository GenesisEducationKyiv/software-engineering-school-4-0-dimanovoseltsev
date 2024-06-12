<?php

namespace app\shared\infrastructure\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

abstract class BaseQuery extends ActiveQuery
{
    /**
     * Creates a new model instance and populates it with provided data.
     *
     * @param array<string, mixed> $data
     * @param string $formName The form name of the model. Default is an empty string.
     * @return ActiveRecord The newly created model instance.
     */
    public function createModel(array $data = [], string $formName = ''): ActiveRecord
    {
        $model = new $this->modelClass();
        $model->load($data, $formName);
        return $model;
    }

    /**
     * @param array<string, mixed> $data
     * @param string $formName
     * @return ActiveRecord
     */
    public function populateModel(array $data = [], string $formName = ''): ActiveRecord
    {
        $model = new $this->modelClass();
        $oldValues = [];
        $primaryKeys = array_keys($model->getPrimaryKey(true));
        foreach ($primaryKeys as $primaryKey) {
            $oldValues[$primaryKey] = $data[$primaryKey] ?? null;
        }

        $model->setIsNewRecord(false);
        $model->setOldAttributes($oldValues);
        $model->load($data, $formName);

        return $model;
    }


    /**
     * Clears the state of the query.
     *
     * This method resets the `where`, `orderBy`, `select`, `union`, and `join` properties of the query
     * to their initial values.
     *
     * @return BaseQuery
     */
    public function clear(): BaseQuery
    {
        $this->where = null;
        $this->orderBy = null;
        $this->select = [];
        $this->union = [];
        $this->join = [];

        return $this;
    }

    /**
     * @param array<string, mixed> $conditions
     * @return int
     */
    public function deleteAll(array $conditions): int
    {
        return $this->modelClass::deleteAll($conditions);
    }

    /**
     * @param array<string, mixed> $conditions
     * @return ActiveRecord|null
     */
    public function findOne(array $conditions): ?ActiveRecord
    {
        /** @var ?ActiveRecord */
        return $this->clear()->where($conditions)->one();
    }

    /**
     * @param array<string, mixed> $conditions
     * @return int
     */
    public function getCount(array $conditions): int
    {
        return (int)$this->clear()->where($conditions)->count();
    }
}
