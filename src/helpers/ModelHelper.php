<?php

namespace amocrmtech\client\helpers;

use amocrmtech\client\exceptions\InvalidModelException;
use Closure;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;

/**
 *
 */
class ModelHelper
{
    /**
     * @param Model|array|callable|null $data
     * @param string                    $class
     * @param bool                      $validate
     *
     * @return mixed use dynamicReturnTypeMeta.json instead
     * @throws InvalidConfigException
     * @throws InvalidModelException
     */
    public static function ensure($data, $class, $validate = true)
    {
        if ($data === null) {
            $data = [];
        }

        if ($data instanceof $class) {
            $model = $data;
        } elseif ($data instanceof Closure || is_callable($data)) {
            $model = $data();
        } elseif (is_array($data)) {
            /** @var Model $model */
            $model = Yii::createObject($class);
            $key   = array_key_exists($model->formName(), $data)
                ? $model->formName()
                : '';

            $model->load($data, $key);
        } else {
            throw new InvalidConfigException('$data is not an array or $class instance');
        }

        if ($validate && !$model->validate()) {
            throw new InvalidModelException($model);
        }

        return $model;
    }
}