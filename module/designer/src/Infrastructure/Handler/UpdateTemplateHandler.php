<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\Handler;

use Ergonode\Designer\Domain\Command\UpdateTemplateCommand;
use Ergonode\Designer\Domain\Entity\Template;
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

        $template->changeName($command->getName());

        foreach ($command->getElements() as $element) {
            $current[(string) $element->getPosition()] = $element;
            if ($template->hasElement($element->getPosition())) {
                $template->changeElement($element);
            } else {
                $template->addElement($element);
            }
        }

        foreach ($template->getElements() as $element) {
            if (!isset($current[(string) $element->getPosition()])) {
                $template->removeElement($element->getPosition());
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
