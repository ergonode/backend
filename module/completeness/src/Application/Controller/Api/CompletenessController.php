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
use Ergonode\Editor\Domain\Entity\ProductDraft;
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
     * @param CompletenessQueryInterface $query
     */
    public function __construct(CompletenessQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @Route("/drafts/{draft}/completeness", methods={"GET"})
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
     * @param ProductDraft $draft
     * @param Language     $language
     *
     * @return Response
     *
     * @ParamConverter(class="Ergonode\Editor\Domain\Entity\ProductDraft")
     */
    public function getCompleteness(ProductDraft $draft, Language $language): Response
    {
        $result = $this->query->getCompleteness($draft->getId(), $language);

        return $this->createRestResponse($result->toArray());
    }
}
