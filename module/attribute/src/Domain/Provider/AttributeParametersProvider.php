<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Provider;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\Entity\Unit;
use Ergonode\Core\Domain\Repository\UnitRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;

class AttributeParametersProvider
{
    private UnitRepositoryInterface $unitRepository;

    public function __construct(UnitRepositoryInterface $unitRepository)
    {
        $this->unitRepository = $unitRepository;
    }

    /**
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
            if (!$unit instanceof Unit) {
                throw new \LogicException(
                    sprintf(
                        'Expected an instance of %s. %s received.',
                        Unit::class,
                        get_debug_type($unit)
                    )
                );
            }
            $parameters['unit'] = $unit->getSymbol();
        }

        return $parameters;
    }
}
