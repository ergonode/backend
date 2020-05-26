<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\Controller\Api\ExportProfile;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_export_profile_read",
 *     path="/export-profile/{exportProfile}",
 *     methods={"GET"},
 *     requirements={"exportProfile"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ExportProfileReadAction
{
    /**
     * @var ExportProfileRepositoryInterface
     */
    private ExportProfileRepositoryInterface $repository;

    /**
     * @param ExportProfileRepositoryInterface $repository
     */
    public function __construct(ExportProfileRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @IsGranted("EXPORT_PROFILE_READ")
     *
     * @SWG\Tag(name="Export Profile")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en",
     *     description="Language code"
     * )
     * @SWG\Parameter(
     *     name="exportProfile",
     *     in="path",
     *     type="string",
     *     description="Export Profile Id",
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns Export Profile"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     *
     * @param AbstractExportProfile $exportProfile
     *
     * @ParamConverter(class="Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile")
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(AbstractExportProfile $exportProfile): Response
    {
        $result = $this->repository->load($exportProfile->getId());
        if ($result) {
            return new SuccessResponse($result);
        }
        throw new  NotFoundHttpException();
    }
}
