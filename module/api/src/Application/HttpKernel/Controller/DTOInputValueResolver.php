<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Application\HttpKernel\Controller;

use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\SharedKernel\Application\Api\DTOInputInterface;
use Ergonode\SharedKernel\Application\Serializer\Exception\DeserializationException;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Resolves DTOInputInterface::class input controller argument from request body.
 */
class DTOInputValueResolver implements ArgumentValueResolverInterface
{
    /**
     * @var string[]
     */
    private array $formatsMap;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    /**
     * @param string[] $formatsMap
     */
    public function __construct(
        array $formatsMap,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->formatsMap = $formatsMap;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return null !== $argument->getType()
            && class_exists($argument->getType())
            && is_subclass_of($argument->getType(), DTOInputInterface::class);
    }

    /**
     * @return DTOInputInterface[]|\Traversable
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $contentType = $request->headers->get('CONTENT_TYPE');

        if (!$contentType) {
            throw new BadRequestHttpException('The "Content-Type" header must exist.');
        }
        $format = $this->formatsMap[$contentType] ?? null;

        if (!$format) {
            $message = 'The "Content-Type" unknown. Accepted: '.implode(', ', array_keys($this->formatsMap));
            throw new UnsupportedMediaTypeHttpException($message);
        }

        if (!$request->getContent()) {
            throw new BadRequestHttpException('Missing request body');
        }

        /** @var string $type */
        $type = $argument->getType();
        if ($argument->isVariadic()) {
            $type .= '[]';
        }

        try {
            $dto = $this->serializer->deserialize($request->getContent(), $type, $format);
        } catch (DeserializationException $exception) {
            throw new BadRequestHttpException(($exception->getPrevious() ?? $exception)->getMessage());
        }

        $violationList = $this->validator->validate($dto);
        if ($violationList->count()) {
            throw new ViolationsHttpException($violationList);
        }

        if (!$argument->isVariadic()) {
            $dto = [$dto];
        }

        foreach ($dto as $item) {
            yield $item;
        }
    }
}
