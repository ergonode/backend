<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Controller;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;

/**
 */
abstract class AbstractApiController extends AbstractFOSRestController
{
    /**
     * @param mixed $data
     * @param array $groups
     * @param int   $code
     *
     * @return Response
     */
    public function createRestResponse($data, array $groups = [], int $code = Response::HTTP_OK): Response
    {
        $view = $this->view($data, $code);

        if (!empty($groups)) {
            $view->setContext((new Context())->setGroups($groups));
        }

        return $this->handleView($view);
    }
}
