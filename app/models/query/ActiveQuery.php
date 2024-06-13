<?php

namespace app\models\query;

use yii\db\ActiveRecord;

class ActiveQuery extends \yii\db\ActiveQuery
{
    /**
     * @return ActiveRecord
     */
    public function createModel(): ActiveRecord
    {
        return new $this->modelClass();
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
