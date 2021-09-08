<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Controller\Api\LanguageTree;

use Ergonode\Core\Domain\Entity\LanguageTree;
use Ergonode\Core\Domain\Repository\LanguageTreeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     path="/tree",
 *     methods={"GET"}
 * )
 */
class LanguageTreeReadAction
{
    private LanguageTreeRepositoryInterface $repository;

    public function __construct(LanguageTreeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @IsGranted("ERGONODE_ROLE_CORE_GET_LANGUAGE_TREE")
     *
     * @SWG\Tag(name="Language")
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
     *     description="Returns Language tree"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     */
    public function __invoke(Language $language): ?LanguageTree
    {
        return $this->repository->load();
    }
}
