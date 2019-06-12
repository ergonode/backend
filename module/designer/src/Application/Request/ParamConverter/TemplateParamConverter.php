<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Application\Request\ParamConverter;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 */
class TemplateParamConverter implements ParamConverterInterface
{
    /**
     * @var TemplateRepositoryInterface
     */
    private $templateRepository;

    /**
     * @param TemplateRepositoryInterface $templateRepository
     */
    public function __construct(TemplateRepositoryInterface $templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }

    /**
     * @param Request        $request
     * @param ParamConverter $configuration
     *
     * @return void
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
         $template = $request->get('template');

        if (null === $template) {
            throw new BadRequestHttpException('Route attribute is missing');
        }

        if (!TemplateId::isValid($template)) {
            throw new BadRequestHttpException('Invalid uuid format');
        }

        $template = $this->templateRepository->load(new TemplateId($template));

        if (null === $template) {
            throw new NotFoundHttpException(\sprintf('%s object not found.', $configuration->getClass()));
        }

        $request->attributes->set($configuration->getName(), $template);
    }

    /**
     * @param ParamConverter $configuration
     *
     * @return bool
     */
    public function supports(ParamConverter $configuration): bool
    {
        return Template::class === $configuration->getClass();
    }
}
