<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Provider;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\Repository\UnitRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;

/**
 */
class AttributeParametersProvider
{
    /**
     * @var UnitRepositoryInterface
     */
    private UnitRepositoryInterface $unitRepository;

    /**
     * @param UnitRepositoryInterface $unitRepository
     */
    public function __construct(UnitRepositoryInterface $unitRepository)
    {
        $this->unitRepository = $unitRepository;
    }


    /**
     * @param AbstractAttribute $attribute
     *
     * @return array
     */
    public function provide(AbstractAttribute $attribute): array
    {
        $parameters = $attribute->getParameters();
        if (isset($parameters['options'])) {
            unset($parameters['options']);
        }
        if (isset($parameters['unit'])) {
            $unit = $this->unitRepository->load(new UnitId($parameters['unit']));
            if ($unit) {
                $parameters['unit'] = $unit->getSymbol();
            }
        }

        return $parameters;
    }
}
