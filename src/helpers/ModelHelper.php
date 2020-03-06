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
     * @param string|null               $scenario
     * @param bool                      $validate
     * @param bool                      $throw
     *
     * @return mixed user dynamicReturnTypeMeta.json instead
     * @throws InvalidConfigException
     * @throws InvalidModelException
     */
    public static function ensure($data, $class, $scenario = null, $validate = true, $throw = true)
    {
        if ($data === null) {
            $data = [];
        }

        if ($data instanceof $class) {
            $model = $data;
            $scenario && $model->setScenario($scenario);
        } elseif ($data instanceof Closure || is_callable($data)) {
            $model = $data();
            $scenario && $model->setScenario($scenario);
        } elseif (is_array($data)) {
            /** @var Model $model */
            $model = Yii::createObject($class);
            $key   = array_key_exists($model->formName(), $data)
                ? $model->formName()
                : '';

            $scenario && $model->setScenario($scenario);
            $model->load($data, $key);
        } else {
            $given = is_object($data)
                ? get_class($data)
                : gettype($data);

            throw new InvalidConfigException("\$data is not an array or {$class} instance. {$given} given.");
        }

        /** @noinspection NotOptimalIfConditionsInspection */
        if ($validate && !$model->validate() && $throw) {
            throw new InvalidModelException($model);
        }

        return $model;
    }
}