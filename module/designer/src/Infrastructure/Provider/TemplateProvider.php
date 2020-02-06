<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\Provider;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Designer\Domain\Query\TemplateGroupQueryInterface;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;

/**
 */
class TemplateProvider
{
    /**
     * @var TemplateRepositoryInterface
     */
    private $repository;

    /**
     * @var TemplateGroupQueryInterface
     */
    private $query;

    /**
     * @var TemplateGeneratorProvider
     */
    private $provider;

    /**
     * @param TemplateRepositoryInterface $repository
     * @param TemplateGroupQueryInterface $query
     * @param TemplateGeneratorProvider   $provider
     */
    public function __construct(
        TemplateRepositoryInterface $repository,
        TemplateGroupQueryInterface $query,
        TemplateGeneratorProvider $provider
    ) {
        $this->repository = $repository;
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
        $id = TemplateId::fromKey($code);
        $template = $this->repository->load($id);
        if (!$template) {
            $groupId = $this->query->getDefaultId();
            $template = $this->provider->provide($code)->getTemplate($id, $groupId);
            $this->repository->save($template);
        }

        return $template;
    }
}
