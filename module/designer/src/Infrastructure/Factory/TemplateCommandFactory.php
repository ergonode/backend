<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Designer\Application\Model\Form\TemplateFormModel;
use Ergonode\Designer\Application\Model\Form\Type\TemplateElementTypeModel;
use Ergonode\Designer\Domain\Command\CreateTemplateCommand;
use Ergonode\Designer\Domain\Command\UpdateTemplateCommand;
use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Designer\Domain\Factory\TemplateElementFactory;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;

/**
 */
class TemplateCommandFactory
{
    /**
     * @var TemplateElementFactory
     */
    private $factory;

    /**
     * TemplateCommandFactory constructor.
     *
     * @param TemplateElementFactory $factory
     */
    public function __construct(TemplateElementFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param TemplateFormModel $model
     *
     * @return CreateTemplateCommand
     * @throws \Exception
     */
    public function getCreateTemplateCommand(TemplateFormModel $model): CreateTemplateCommand
    {
        return new CreateTemplateCommand(
            $model->name,
            $this->createElements($model),
            $model->image?new MultimediaId($model->image):null
        );
    }

    /**
     * @param TemplateId        $id
     * @param TemplateFormModel $model
     *
     * @return UpdateTemplateCommand
     */
    public function getUpdateTemplateCommand(TemplateId $id, TemplateFormModel $model): UpdateTemplateCommand
    {
        return new UpdateTemplateCommand(
            $id,
            $model->name,
            $this->createElements($model),
            $model->image?new MultimediaId($model->image):null
        );
    }

    /**
     * @param TemplateFormModel $model
     *
     * @return ArrayCollection
     */
    private function createElements(TemplateFormModel $model): ArrayCollection
    {
        $result = new ArrayCollection();
        foreach ($model->elements as $element) {
            $result->add($this->createElement($element));
        }

        return $result;
    }

    /**
     * @param TemplateElementTypeModel $model
     *
     * @return TemplateElement
     */
    private function createElement(TemplateElementTypeModel $model): TemplateElement
    {
        return $this
            ->factory
            ->create($model->position, $model->size, $model->type, (array) $model->properties);
    }
}
