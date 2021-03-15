<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Application\Controller\Api\Template;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Designer\Domain\Entity\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Designer\Infrastructure\Mapper\TemplateResultMapper;

/**
 * @Route(
 *     name="ergonode_designer_template_read",
 *     path="/templates/{template}",
 *     methods={"GET"},
 *     requirements={"template" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class TemplateReadAction
{
    private TemplateResultMapper $mapper;

    public function __construct(TemplateResultMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @IsGranted("DESIGNER_GET_TEMPLATE")
     *
     * @SWG\Tag(name="Designer")
     * @SWG\Parameter(
     *     name="template",
     *     in="path",
     *     type="string",
     *     description="Template id"
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns template"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     */
    public function __invoke(Template $template): Response
    {
        return new SuccessResponse($this->mapper->map($template));
    }
}
