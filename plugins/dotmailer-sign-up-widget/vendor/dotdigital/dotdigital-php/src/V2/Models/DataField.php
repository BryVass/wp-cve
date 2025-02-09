<?php

declare (strict_types=1);
namespace Dotdigital_WordPress_Vendor\Dotdigital\V2\Models;

/**
 * @property string $name
 * @property string $type
 * @property string $visibility
 * @property string $defaultValue
 */
class DataField extends AbstractSingletonModel
{
    /**
     * @var string
     */
    protected string $name;
    /**
     * @var string
     */
    protected string $type;
    /**
     * @var string
     */
    protected string $visibility;
    /**
     * @var mixed
     */
    protected $defaultValue;
    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }
    /**
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }
}
