<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Controller\Api\Option;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;

/**
 * @Route("/attributes/{attribute}/options", methods={"GET"})
 */
class OptionsReadAction
{
    private OptionQueryInterface $query;

    public function __construct(OptionQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @IsGranted("ATTRIBUTE_GET_OPTION_COLLECTIONS")
     *
     * @SWG\Tag(name="Attribute")
     * @SWG\Parameter(
     *     name="attribute",
     *     in="path",
     *     type="string",
     *     description="Attribute id",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns options collections",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     */
    public function __invoke(AbstractAttribute $attribute): Response
    {
        $collection = $this->query->getAll($attribute->getId());

        return new SuccessResponse($collection);
    }
}
