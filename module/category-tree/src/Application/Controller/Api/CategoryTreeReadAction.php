<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Application\Controller\Api;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\CategoryTree\Domain\Entity\CategoryTree;
use Ergonode\Core\Domain\ValueObject\Language;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/trees/{tree}", methods={"GET"})
 */
class CategoryTreeReadAction
{
    /**
     * @IsGranted("CATEGORY_TREE_READ")
     *
     * @SWG\Tag(name="Tree")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code"
     * )
     * @SWG\Parameter(
     *     name="tree",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="tree ID"
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Language"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns category tree"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     *
     * @ParamConverter(class="Ergonode\CategoryTree\Domain\Entity\CategoryTree")
     *
     * @param CategoryTree $tree
     * @param Language     $language
     *
     * @return Response
     */
    public function __invoke(CategoryTree $tree, Language $language): Response
    {
        return new SuccessResponse($tree);
    }
}
