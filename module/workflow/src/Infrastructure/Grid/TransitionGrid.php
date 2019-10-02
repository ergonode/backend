<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\LabelColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\SelectFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class TransitionGrid extends AbstractGrid
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var StatusQueryInterface
     */
    private $statusQuery;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @param TranslatorInterface   $translator
     * @param StatusQueryInterface  $statusQuery
     * @param UrlGeneratorInterface $router
     */
    public function __construct(TranslatorInterface $translator, StatusQueryInterface $statusQuery, UrlGeneratorInterface $router)
    {
        $this->translator = $translator;
        $this->statusQuery = $statusQuery;
        $this->router = $router;
    }

    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     *
     * @throws \Exception
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $statuses = $this->statusQuery->getAllStatuses($language);
        $filters = $configuration->getFilters();
        $codes = [];
        foreach ($statuses as $code => $status) {
            $codes[$code] = $status['name'];
        }

        $code = new LabelColumn('source', $this->trans('Source'), $statuses, new SelectFilter($codes, $filters->getString('source')));
        $this->addColumn('source', $code);

        $code = new LabelColumn('destination', $this->trans('Destination'), $statuses, new SelectFilter($codes, $filters->getString('destination')));
        $this->addColumn('destination', $code);

        $column = new TextColumn('name', $this->trans('Name'), new TextFilter($filters->getString('name')));
        $column->setWidth(200);
        $this->addColumn('name', $column);

        $column = new TextColumn('description', $this->trans('Description'), new TextFilter($filters->getString('description')));
        $column->setWidth(300);
        $this->addColumn('description', $column);

       // 'href' =>  $this->router->generate('ergonode_product_application_api_product_getproduct', [ 'product' => $productId->getValue(), 'language' => $language->getCode()]),
        $url1 = sprintf('/api/v1/%s/workflow/default/transitions/{source}/{destination}', $language->getCode());
        $url2 = sprintf('/api/v1/%s/workflow/default/transitions/{source}/{destination}', $language->getCode());

        $this->addColumn('_links', new LinkColumn('edit', ['edit' => ['href' => $url1], 'delete' => ['href' => $url2]]));
        $this->orderBy('code', 'DESC');

        $this->setConfiguration(AbstractGrid::PARAMETER_ALLOW_COLUMN_RESIZE, true);
    }

    /**
     * @param string $id
     * @param array  $parameters
     *
     * @return string
     */
    private function trans(string $id, array $parameters = []): string
    {
        return $this->translator->trans($id, $parameters, 'grid');
    }
}
