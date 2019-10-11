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

/**
 */
class LinkColumnRenderer implements ColumnRendererInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
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
            if (array_key_exists('route', $link)) {
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

                $links[$name] = [
                    'href' => $this->urlGenerator->generate(
                        $link['route'],
                        $link['parameters'],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ),
                    'method' => $link['method'],
                ];
            } elseif (array_key_exists('uri', $link)) {
                $links[$name] = [
                    'href' => $link['uri'],
                    'method' => Request::METHOD_GET,
                ];
            } else {
                throw new \RuntimeException('Unsupported link type');
            }
        }

        return $links;
    }
}
