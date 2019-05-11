<?php

declare(strict_types = 1);

namespace Constup\JSONSerializer;

use Constup\ClassObjectUtils\Property\PropertyReader;
use Constup\JSONSerializer\Util\Composer\ComposerJsonFetchAndFind;
use Exception;
use ReflectionClass;
use ReflectionException;
use stdClass;

/**
 * Class ObjectCreator
 *
 * @package Constup\JSONSerializer
 */
class ObjectCreator
{
    /** @var object */
    private $object;
    /** @var bool */
    private $serialize_only_leaf;

    /**
     * ObjectCreator constructor.
     * @param object $object
     * @param bool $serialize_only_leaf
     */
    public function __construct(object $object, bool $serialize_only_leaf)
    {
        $this->object = $object;
        $this->serialize_only_leaf = $serialize_only_leaf;
    }

    /**
     * @return object
     */
    public function getObject(): object
    {
        return $this->object;
    }

    /**
     * @return bool
     */
    public function isSerializeOnlyLeaf(): bool
    {
        return $this->serialize_only_leaf;
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     *
     * @return object
     */
    public function generateJsonObject(): object
    {
        $object = $this->getObject();
        $class = get_class($object);
        $class_file_name = (new ReflectionClass($class))->getFileName();
        $composer_json_object = ComposerJsonFetchAndFind::findAndFetch(dirname($class_file_name));
        
        $result = new stdClass();
        $result->class_name = $class;
        if (!empty($composer_json_object->name)) {
            $result->object_package = $composer_json_object->name;
        }

        $properties = (new PropertyReader())->getPropertiesFromAllParents($object);
        foreach ($properties as $key=>$value) {
            if (is_object($value)) {
                $improved_key = $key . ':' . get_class($value);
                $result->$improved_key = (new self($value, $this->isSerializeOnlyLeaf()))->generateJsonObject();
            } else {
                $improved_key = $key . ':' . gettype($value);
                if (is_array($value)) {
                    $value_array = [];
                    foreach ($value as $data) {
                        if (is_object($data)) {
                            $value_array[] = (new self($data, $this->isSerializeOnlyLeaf()))->generateJsonObject();
                        } else {
                            $value_array[] = $data;
                        }
                    }
                    $result->$improved_key = $value_array;
                } else {
                    $result->$improved_key = $value;
                }
            }
        }

        return $result;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function fetchProperties(): array
    {
        if ($this->isSerializeOnlyLeaf()) {
            return (new PropertyReader())->getPropertiesFromLeafClass($this->getObject());
        } else {
            return (new PropertyReader())->getPropertiesFromAllParents($this->getObject());
        }
    }
}
