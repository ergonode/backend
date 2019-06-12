<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\Handler;

use Ergonode\Designer\Domain\Command\UpdateTemplateCommand;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Entity\TemplateElementId;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class UpdateTemplateHandler
{
    /**
     * @var TemplateRepositoryInterface
     */
    private $designerTemplateRepository;

    /**
     * @param TemplateRepositoryInterface $designerTemplateRepository
     */
    public function __construct(TemplateRepositoryInterface $designerTemplateRepository)
    {
        $this->designerTemplateRepository = $designerTemplateRepository;
    }

    /**
     * @param UpdateTemplateCommand $command
     */
    public function __invoke(UpdateTemplateCommand $command)
    {
        $current = [];
        /** @var Template $template */
        $template = $this->designerTemplateRepository->load($command->getId());

        Assert::notNull($template);

        if ($template->getName() !== $command->getName()) {
            $template->changeName($command->getName());
        }

        foreach ($command->getElements() as $element) {
            $id = $element->getElementId();
            $current[$id->getValue()] = $id;
            if ($template->hasElement($id)) {
                $template->moveElement($id, $element->getPosition());
                $template->resizeElement($id, $element->getSize());
                if ($element->isRequired()) {
                    $template->makeRequired($id);
                } else {
                    $template->makeNonRequired($id);
                }
            } else {
                $template->addElement($id, $element->getPosition(), $element->getSize(), $element->isRequired());
            }
        }

        foreach ($template->getElements() as $key => $element) {
            if (!isset($current[$key])) {
                $template->removeElement(new TemplateElementId($key));
            }
        }

        foreach ($command->getSections() as $column => $section) {
            if ($template->hasSection($column)) {
                $template->changeSection($column, $section);
            } else {
                $template->addSection($column, $section);
            }
        }

        foreach ($template->getSections() as $row => $section) {
            if (!$command->getSections()->containsKey($row)) {
                $template->removeSection($row);
            }
        }

        if ($command->getImageId()) {
            if ($template->getImageId()) {
                $template->changeImage($command->getImageId());
            } else {
                $template->addImage($command->getImageId());
            }
        } elseif ($template->getImageId()) {
            $template->removeImage();
        }

        $this->designerTemplateRepository->save($template);
    }
}
