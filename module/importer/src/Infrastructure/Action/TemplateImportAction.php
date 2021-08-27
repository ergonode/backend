<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Query\TemplateGroupQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;
use Ergonode\Importer\Infrastructure\Action\Process\Template\TemplateElementBuilderProvider;
use Ergonode\Designer\Domain\ValueObject\TemplateCode;

class TemplateImportAction
{
    private TemplateQueryInterface $query;

    private TemplateGroupQueryInterface $templateGroupQuery;

    private TemplateRepositoryInterface $templateRepository;

    private TemplateElementBuilderProvider $provider;

    public function __construct(
        TemplateQueryInterface $query,
        TemplateGroupQueryInterface $templateGroupQuery,
        TemplateRepositoryInterface $templateRepository,
        TemplateElementBuilderProvider $provider
    ) {
        $this->query = $query;
        $this->templateGroupQuery = $templateGroupQuery;
        $this->templateRepository = $templateRepository;
        $this->provider = $provider;
    }

    /**
     * @throws \Exception
     */
    public function action(TemplateCode $code, string $name, array $elements): Template
    {
        $template = null;
        $templateId = $this->query->findTemplateIdByCode($code);

        if ($templateId) {
            $template = $this->templateRepository->load($templateId);
        }

        if (!$template) {
            $groupId = $this->templateGroupQuery->getDefaultId();
            $template = new Template(
                TemplateId::generate(),
                $code,
                $groupId,
                $name,
            );
        } else {
            $template->changeName($name);
        }

        $elements = $this->getElements($template, $elements);

        foreach ($elements as $element) {
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

        $this->templateRepository->save($template);

        return $template;
    }

    private function getElements(Template $template, array $elements): array
    {
        $result = [];

        foreach ($elements as $element) {
            $position = new Position($element['x'], $element['y']);
            $size = new Size($element['width'], $element['height']);
            $properties = $element['properties'];
            $result[] = $this->provider->provide($element['type'])->build($template, $position, $size, $properties);
        }

        return $result;
    }
}
