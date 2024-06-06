<?php

namespace app\exceptions;

use Exception;
use Throwable;
use yii\base\Model;

class EntityException extends Exception
{
    private Model $model;

    /**
     * EntityException constructor.
     * @param Model $model
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(Model $model, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->model = $model;
        parent::__construct($message, $code, $previous);
        if ($model->hasErrors()) {
            $this->message .= ' Errors: ' . var_export($model->getErrors(), true);
        }
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }
}
