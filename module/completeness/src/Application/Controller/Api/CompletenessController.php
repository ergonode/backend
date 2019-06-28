<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Application\Controller\Api;

use Ergonode\Completeness\Domain\Query\CompletenessQueryInterface;
use Ergonode\Core\Application\Controller\AbstractApiController;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Editor\Domain\Provider\DraftProvider;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 */
class CompletenessController extends AbstractApiController
{
    /**
     * @var CompletenessQueryInterface
     */
    private $query;

    /**
     * @var DraftProvider
     */
    private $draftProvider;

    /**
     * @param CompletenessQueryInterface $query
     * @param DraftProvider              $draftProvider
     */
    public function __construct(CompletenessQueryInterface $query, DraftProvider $draftProvider)
    {
        $this->query = $query;
        $this->draftProvider = $draftProvider;
    }

    /**
     * @Route(
     *     "/products/{product}/draft/completeness", methods={"GET"}, requirements = {"product" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
     * )
     *
     * @SWG\Tag(name="Editor")
     * @SWG\Parameter(
     *     name="draft",
     *     in="path",
     *     type="string",
     *     description="Product draft id",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Get draft grid",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Form validation error",
     * )
     *
     * @param AbstractProduct $product
     * @param Language        $language
     *
     * @return Response
     *
     * @ParamConverter(class="Ergonode\Product\Domain\Entity\AbstractProduct")
     *
     * @throws \Exception
     */
    public function getCompleteness(AbstractProduct $product, Language $language): Response
    {
        $draft = $this->draftProvider->provide($product);

        $result = $this->query->getCompleteness($draft->getId(), $language);

        return $this->createRestResponse($result->toArray());
    }
}
