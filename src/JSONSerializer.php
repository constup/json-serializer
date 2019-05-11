<?php

declare(strict_types = 1);

namespace Constup\JSONSerializer;

use ReflectionException;

/**
 * Class JSONSerializer
 *
 * @package Constup\JSONSerializer
 */
class JSONSerializer
{
    /** @var object */
    private $object;
    /** @var bool */
    private $serialize_only_leaf;

    /**
     * JSONSerializer constructor.
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
     *
     * @return string
     */
    public function serialize(): string
    {
        $result = (new ObjectCreator($this->getObject(), $this->isSerializeOnlyLeaf()))->generateJsonObject();

        return json_encode($result);
    }
}
