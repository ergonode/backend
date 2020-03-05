<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Column\Renderer;

use Ergonode\Grid\Column\Exception\UnsupportedColumnException;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\ColumnInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 */
class LinkColumnRenderer implements ColumnRendererInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private UrlGeneratorInterface $urlGenerator;

    /**
     * @var AuthorizationCheckerInterface
     */
    private AuthorizationCheckerInterface $checker;

    /**
     * @param UrlGeneratorInterface         $urlGenerator
     * @param AuthorizationCheckerInterface $checker
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, AuthorizationCheckerInterface $checker)
    {
        $this->urlGenerator = $urlGenerator;
        $this->checker = $checker;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ColumnInterface $column): bool
    {
        return $column instanceof LinkColumn;
    }

    /**
     * {@inheritDoc}
     *
     * @throws UnsupportedColumnException
     */
    public function render(ColumnInterface $column, string $id, array $row): array
    {
        if (!$this->supports($column)) {
            throw new UnsupportedColumnException($column);
        }

        $links = [];

        $mapFunction = static function ($value) {
            return sprintf('{%s}', $value);
        };

        $keys = array_map($mapFunction, array_keys($row));
        $values = array_values($row);

        foreach ($column->getLinks() as $name => $link) {
            if ($this->isVisible($link, $row)) {
                if (array_key_exists('route', $link)) {
                    $links[$name] = $this->generateLink($link, $keys, $values);
                } elseif (array_key_exists('uri', $link)) {
                    $links[$name] = [
                        'href' => $link['uri'],
                        'method' => Request::METHOD_GET,
                    ];
                } else {
                    throw new \RuntimeException('Unsupported link type');
                }
            }
        }

        return $links;
    }

    /**
     * @param array $link
     * @param array $row
     *
     * @return bool
     */
    private function isVisible(array $link, array $row): bool
    {
        if (array_key_exists('privilege', $link)) {
            $privilege = $link['privilege'];
            if (!$this->checker->isGranted($privilege)) {
                return false;
            }
        }

        if (!array_key_exists('show', $link)) {
            return true;
        }

        $result = true;

        foreach ($link['show'] as $field => $value) {
            if (array_key_exists($field, $row) && $row[$field] !== $value) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * @param array $link
     *
     * @param array $keys
     * @param array $values
     *
     * @return array
     */
    private function generateLink(array $link, array $keys, array $values): array
    {
        if (!array_key_exists('parameters', $link)) {
            $link['parameters'] = [];
        } else {
            foreach ($link['parameters'] as $key => &$field) {
                $field = str_replace($keys, $values, $field);
            }
        }

        if (!array_key_exists('method', $link)) {
            $link['method'] = Request::METHOD_GET;
        }

        return [
            'href' => $this->urlGenerator->generate(
                $link['route'],
                $link['parameters'],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            'method' => $link['method'],
        ];
    }
}
