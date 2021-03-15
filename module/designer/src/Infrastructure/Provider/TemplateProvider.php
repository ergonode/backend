<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Provider;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Query\TemplateGroupQueryInterface;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;

class TemplateProvider
{
    private TemplateRepositoryInterface $repository;

    private TemplateQueryInterface $templateQuery;

    private TemplateGroupQueryInterface $query;

    private TemplateGeneratorProvider $provider;

    public function __construct(
        TemplateRepositoryInterface $repository,
        TemplateQueryInterface $templateQuery,
        TemplateGroupQueryInterface $query,
        TemplateGeneratorProvider $provider
    ) {
        $this->repository = $repository;
        $this->templateQuery = $templateQuery;
        $this->query = $query;
        $this->provider = $provider;
    }

    /**
     * @throws \Exception
     */
    public function provide(string $code): Template
    {
        $template = null;
        $id = $this->templateQuery->findTemplateIdByCode($code);

        if ($id) {
            $template = $this->repository->load($id);
        }

        if (!$template) {
            $groupId = $this->query->getDefaultId();
            $template = $this->provider->provide($code)->getTemplate($id, $groupId);
            $this->repository->save($template);
        }

        return $template;
    }
}
