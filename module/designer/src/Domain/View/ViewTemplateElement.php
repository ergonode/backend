<?php


namespace Ergonode\Designer\Domain\View;

use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;

/**
 */
class ViewTemplateElement
{
    /**
     * @var Position
     */
    private $position;

    /**
     * @var Size
     */
    private $size;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $properties;

    /**
     * @param Position $position
     * @param Size     $size
     * @param string   $label
     * @param string   $type
     * @param array    $properties
     */
    public function __construct(Position $position, Size $size, string $label, string $type, array $properties = [])
    {
        $this->position = $position;
        $this->size = $size;
        $this->label = $label;
        $this->type = $type;
        $this->properties = $properties;
    }

    /**
     * @return Position
     */
    public function getPosition(): Position
    {
        return $this->position;
    }

    /**
     * @return Size
     */
    public function getSize(): Size
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }
}
