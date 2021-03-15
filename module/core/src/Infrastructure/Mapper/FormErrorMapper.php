<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Mapper;

use Symfony\Component\Form\FormInterface;

class FormErrorMapper
{
    private FormErrorMapperMessageProvider $provider;

    public function __construct(FormErrorMapperMessageProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return array
     */
    public function map(FormInterface $form): array
    {
        $result = [];
        foreach ($form->getErrors() as $error) {
            if ($error->getOrigin()) {
                $result[$error->getOrigin()->getName()] = $this->provider->getMessage($error);
            } else {
                $result['form'][] = $this->provider->getMessage($error);
            }
        }

        foreach ($form->all() as $element) {
            if ($element->isSubmitted() && !$element->isValid()) {
                $result = array_merge($result, $this->getErrors($element));
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    private function getErrors(FormInterface $form): array
    {
        $result = [];
        $name = $this->parseName($form->getName());

        foreach ($form->getErrors() as $error) {
            if ('' !== $name) {
                $result[$name][] = $this->provider->getMessage($error);
            } else {
                $result[] = $this->provider->getMessage($error);
            }
        }

        /** @var FormInterface $element */
        foreach ($form->all() as $element) {
            if ($element->isSubmitted() && !$element->isValid()) {
                if ('' !== $name) {
                    if (!isset($result[$name])) {
                        $result[$name] = [];
                    }
                    $result[$name] = array_merge($result[$name], $this->getErrors($element));
                } else {
                    $result = array_merge($result, $this->getErrors($element));
                }
            }
        }

        return $result;
    }

    private function parseName(string $name): string
    {
        if (ctype_digit($name)) {
            return sprintf('%s%s', 'element-', $name);
        }

        return $name;
    }
}
