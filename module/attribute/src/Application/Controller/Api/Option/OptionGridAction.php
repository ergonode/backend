<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Controller\Api\Option;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractOptionAttribute;

/**
 * @Route("/attributes/{attribute}/options/grid", methods={"GET"})
 */
class OptionGridAction
{
    private GridBuilderInterface $gridBuilder;

    private OptionQueryInterface $query;

    private GridRenderer $gridRenderer;

    public function __construct(
        GridBuilderInterface $gridBuilder,
        OptionQueryInterface $query,
        GridRenderer $gridRenderer
    ) {
        $this->gridBuilder = $gridBuilder;
        $this->query = $query;
        $this->gridRenderer = $gridRenderer;
    }

    /**
     * @IsGranted("ERGONODE_ROLE_ATTRIBUTE_GET_OPTION_GRID")
     *
     * @SWG\Tag(name="Attribute")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="50",
     *     description="Number of returned lines",
     * )
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="0",
     *     description="Number of start line",
     * )
     * @SWG\Parameter(
     *     name="field",
     *     in="query",
     *     required=false,
     *     type="string",
     *     description="Order field",
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"ASC","DESC"},
     *     description="Order",
     * )
     * @SWG\Parameter(
     *     name="filter",
     *     in="query",
     *     required=false,
     *     type="string",
     *     description="Filter"
     * )
     * @SWG\Parameter(
     *     name="view",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"grid","list"},
     *     description="Specify respons format"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns attribute collection",
     * )
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration", name="configuration")
     */
    public function __invoke(
        AbstractOptionAttribute $attribute,
        Language $language,
        RequestGridConfiguration $configuration
    ): array {
        $grid = $this->gridBuilder->build($configuration, $language);
        $dataSet = $this->query->getDataSet($attribute->getId(), $language);

        return $this->gridRenderer->render($grid, $configuration, $dataSet);
    }
}
