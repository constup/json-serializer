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

    /**
     * JSONSerializer constructor.
     *
     * @param object $object
     */
    public function __construct(object $object)
    {
        $this->object = $object;
    }

    /**
     * @return object
     */
    public function getObject(): object
    {
        return $this->object;
    }

    /**
     * @throws ReflectionException
     *
     * @return string
     */
    public function serialize(): string
    {
        $result = (new ObjectCreator($this->getObject()))->generateJsonObject();

        return json_encode($result);
    }
}
