<?php

namespace app\models\query;

use yii\db\ActiveRecord;

class ActiveQuery extends \yii\db\ActiveQuery
{
    /**
     * @param array $data
     * @param string $formName
     * @return ActiveRecord
     */
    public function createModel(array $data = [], string $formName = ''): ActiveRecord
    {
        $model = new $this->modelClass();
        $model->load($data, $formName);
        return $model;
    }

    /**
     * @return ActiveQuery
     */
    public function clear(): ActiveQuery
    {
        $this->where = null;
        $this->orderBy = null;
        $this->asArray = false;
        $this->indexBy = null;
        $this->select = [];
        $this->union = [];
        $this->join = [];

        return $this;
    }
}
