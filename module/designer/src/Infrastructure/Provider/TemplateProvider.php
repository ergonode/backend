<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\Provider;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Query\TemplateGroupQueryInterface;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;

/**
 */
class TemplateProvider
{
    /**
     * @var TemplateRepositoryInterface
     */
    private TemplateRepositoryInterface $repository;

    /**
     * @var TemplateQueryInterface
     */
    private TemplateQueryInterface $templateQuery;

    /**
     * @var TemplateGroupQueryInterface
     */
    private TemplateGroupQueryInterface $query;

    /**
     * @var TemplateGeneratorProvider
     */
    private TemplateGeneratorProvider $provider;

    /**
     * @param TemplateRepositoryInterface $repository
     * @param TemplateQueryInterface      $templateQuery
     * @param TemplateGroupQueryInterface $query
     * @param TemplateGeneratorProvider   $provider
     */
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
     * @param string $code
     *
     * @return Template
     *
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
