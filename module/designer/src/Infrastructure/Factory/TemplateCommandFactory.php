<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Core\Infrastructure\Mapper\SnakeCaseMapper;
use Ergonode\Designer\Application\Model\Form\TemplateFormModel;
use Ergonode\Designer\Application\Model\Form\Type\TemplateElementTypeModel;
use Ergonode\Designer\Domain\Command\CreateTemplateCommand;
use Ergonode\Designer\Domain\Command\UpdateTemplateCommand;
use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\Designer\Domain\Factory\TemplateElementFactory;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class TemplateCommandFactory
{
    private TemplateElementFactory $factory;

    private SnakeCaseMapper $mapper;

    public function __construct(TemplateElementFactory $factory, SnakeCaseMapper $mapper)
    {
        $this->factory = $factory;
        $this->mapper = $mapper;
    }

    /**
     * @throws \Exception
     */
    public function getCreateTemplateCommand(TemplateFormModel $model): CreateTemplateCommand
    {
        return new CreateTemplateCommand(
            $model->name,
            $this->createElements($model),
            $model->defaultLabel,
            $model->defaultImage,
            $model->image ? new MultimediaId($model->image) : null
        );
    }

    public function getUpdateTemplateCommand(TemplateId $id, TemplateFormModel $model): UpdateTemplateCommand
    {
        return new UpdateTemplateCommand(
            $id,
            $model->name,
            $this->createElements($model),
            $model->defaultLabel,
            $model->defaultImage,
            $model->image ? new MultimediaId($model->image) : null
        );
    }

    private function createElements(TemplateFormModel $model): ArrayCollection
    {
        $result = new ArrayCollection();
        foreach ($model->elements as $element) {
            $result->add($this->createElement($element));
        }

        return $result;
    }

    private function createElement(TemplateElementTypeModel $model): TemplateElement
    {
        $property = $this->mapper->map((array) $model->properties);

        return $this
            ->factory
            ->create($model->position, $model->size, $model->type, $property);
    }
}
