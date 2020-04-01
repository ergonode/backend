<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Controller\Api\Role;

use Ergonode\Account\Domain\Query\RoleQueryInterface;
use Ergonode\Account\Infrastructure\Grid\RoleGrid;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/roles", methods={"GET"})
 */
class RoleGridReadAction
{
    /**
     * @var RoleQueryInterface
     */
    private RoleQueryInterface $query;

    /**
     * @var RoleGrid
     */
    private RoleGrid $grid;

    /**
     * @var GridRenderer
     */
    private GridRenderer $gridRenderer;

    /**
     * @param GridRenderer       $gridRenderer
     * @param RoleQueryInterface $query
     * @param RoleGrid           $grid
     */
    public function __construct(
        GridRenderer $gridRenderer,
        RoleQueryInterface $query,
        RoleGrid $grid
    ) {
        $this->query = $query;
        $this->grid = $grid;
        $this->gridRenderer = $gridRenderer;
    }

    /**
     * @IsGranted("USER_ROLE_READ")
     *
     * @SWG\Tag(name="Account")
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="50",
     *     description="Number of returned lines"
     * )
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="0",
     *     description="Number of start line"
     * )
     * @SWG\Parameter(
     *     name="field",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"id","name","description", "users_count"},
     *     description="Order field"
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"ASC","DESC"},
     *     description="Order"
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
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en",
     *     description="Language code"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns roles collection"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     *
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     *
     * @param Language                 $language
     * @param RequestGridConfiguration $configuration
     *
     * @return Response
     */
    public function __invoke(Language $language, RequestGridConfiguration $configuration): Response
    {
        $data = $this->gridRenderer->render(
            $this->grid,
            $configuration,
            $this->query->getDataSet(),
            $language
        );

        return new SuccessResponse($data);
    }
}
