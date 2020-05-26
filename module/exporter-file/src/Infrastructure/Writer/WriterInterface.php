<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Writer;

use Ergonode\Product\Domain\Entity\AbstractProduct;

/**
 */
interface WriterInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function support(string $type): bool;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param array $attributes
     *
     * @return string[]
     */
    public function start(array $attributes): array;

    /**
     * @param array $attributes
     *
     * @return string[]
     */
    public function end(array $attributes): array;

    /**
     * @param AbstractProduct $product
     * @param array           $languages
     * @param array           $attributes
     *
     * @return string[]
     */
    public function write(AbstractProduct $product, array $languages, array $attributes): array;
}
