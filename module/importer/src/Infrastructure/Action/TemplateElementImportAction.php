<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;

class TemplateElementImportAction
{
    private TemplateQueryInterface $templateQuery;

    private TemplateRepositoryInterface $templateRepository;

    public function __construct(TemplateQueryInterface $templateQuery, TemplateRepositoryInterface $templateRepository)
    {
        $this->templateQuery = $templateQuery;
        $this->templateRepository = $templateRepository;
    }

    public function action(string $name, TemplateElementInterface $element): Template
    {
        $templateId = $this->templateQuery->findTemplateIdByCode($name);
        if (!$templateId instanceof TemplateId) {
            throw new ImportException('Template {name} not found', ['{name}' => $name]);
        }

        $template = $this->templateRepository->load($templateId);

        if (!$template instanceof Template) {
            throw new ImportException('Template {name} not found', ['{name}' => $name]);
        }

        if($template->hasElement($element->getPosition())) {
            $template->changeElement($element);
        } else {
            $template->addElement($element);
        }

        return $template;
    }
}
