<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Query\TemplateGroupQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class TemplateImportAction
{
    private TemplateQueryInterface $query;

    private TemplateGroupQueryInterface $templateGroupQuery;

    private TemplateRepositoryInterface $templateRepository;

    public function __construct(
        TemplateQueryInterface $query,
        TemplateGroupQueryInterface $templateGroupQuery,
        TemplateRepositoryInterface $templateRepository
    ) {
        $this->query = $query;
        $this->templateGroupQuery = $templateGroupQuery;
        $this->templateRepository = $templateRepository;
    }

    /**
     * @throws \Exception
     */
    public function action(string $code): Template
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
                $groupId,
                $code,
            );
        } else {
            $template->changeName($code);
        }

        $this->templateRepository->save($template);

        return $template;
    }
}
