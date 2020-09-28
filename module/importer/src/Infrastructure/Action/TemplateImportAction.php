<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\Transformer\Domain\Model\Record;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Query\TemplateGroupQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

/**
 */
class TemplateImportAction implements ImportActionInterface
{
    public const TYPE = 'TEMPLATE';

    public const CODE_FIELD = 'code';

    /**
     * @var TemplateQueryInterface
     */
    private TemplateQueryInterface $query;

    /**
     * @var TemplateGroupQueryInterface
     */
    private TemplateGroupQueryInterface $templateGroupQuery;

    /**
     * @var TemplateRepositoryInterface
     */
    private TemplateRepositoryInterface $templateRepository;

    /**
     * @param TemplateQueryInterface      $query
     * @param TemplateGroupQueryInterface $templateGroupQuery
     * @param TemplateRepositoryInterface $templateRepository
     */
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
     * @param ImportId $importId
     * @param Record   $record
     *
     * @throws \Exception
     */
    public function action(ImportId $importId, Record $record): void
    {
        $code = $record->has(self::CODE_FIELD) ? $record->get(self::CODE_FIELD) : null;

        Assert::notNull($code, 'Template import required "code" field not exists');

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
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
