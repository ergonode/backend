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
use Ergonode\Designer\Domain\Entity\AbstractTemplateElement;
use Ergonode\Designer\Domain\Entity\AttributeTemplateElement;
use Ergonode\Designer\Domain\Entity\TemplateElementId;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;

/**
 */
class TemplateCommandFactory
{
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
            $this->createSections($model),
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
            $this->createSections($model),
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
     * @return AbstractTemplateElement
     */
    private function createElement(TemplateElementTypeModel $model): AbstractTemplateElement
    {
        return new AttributeTemplateElement(
            $model->position,
            $model->size,
            new TemplateElementId($model->id),
            $model->required
        );
    }

    /**
     * @param TemplateFormModel $model
     *
     * @return ArrayCollection
     */
    private function createSections(TemplateFormModel $model): ArrayCollection
    {
        $result = [];
        foreach ($model->sections as $section) {
            $result[$section->row] = $section->title;
        }

        return new ArrayCollection($result);
    }
}
