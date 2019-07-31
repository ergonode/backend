<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\AttributeUnit\Application\Controller\Api;

use Ergonode\AttributeUnit\Domain\Query\UnitQueryInterface;
use Ergonode\Core\Application\Controller\AbstractApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class DictionaryController extends AbstractApiController
{
    /**
     * @var UnitQueryInterface
     */
    private $unitQuery;

    /**
     * @param UnitQueryInterface $unitQuery
     */
    public function __construct(UnitQueryInterface $unitQuery)
    {
        $this->unitQuery = $unitQuery;
    }

    /**
     * @Route("/units", methods={"GET"})
     *
     * @IsGranted("ATTRIBUTE_READ")
     *
     * @SWG\Tag(name="Dictionary")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns collection of units",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @return Response
     */
    public function getUnits(): Response
    {
        $languages = $this->unitQuery->getDictionary();

        return $this->createRestResponse($languages);
    }
}
