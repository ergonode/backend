<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Installer;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Core\Domain\Command\CreateUnitCommand;

/**
 */
class CoreInstaller implements InstallerInterface
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param AttributeRepositoryInterface $repository
     * @param CommandBusInterface          $commandBus
     */
    public function __construct(AttributeRepositoryInterface $repository, CommandBusInterface $commandBus)
    {
        $this->repository = $repository;
        $this->commandBus = $commandBus;
    }

    /**
     * @throws \Exception
     */
    public function install(): void
    {
        foreach ($this->getUnits() as $name => $symbol) {
            $command = new CreateUnitCommand($name, $symbol);
            $this->commandBus->dispatch($command);
        }
    }

    /**
     * @return string[]
     */
    public function getUnits(): array
    {
        return [
            'Weber' => 'Wb',
            'Watt' => 'W',
            'Second' => 's',
            'Sievert' => 'Sv',
            'Mole' => 'mol',
            'Coulomb' => 'C',
            'Ampere' => 'A',
            'Farad' => 'F',
            'Tesla' => 'T',
            'Lumen' => 'lm',
            'Siemens' => 'S',
            'Volt' => 'V',
            'Ohm' => 'Ω',
            'Henry' => 'H',
            'Newton' => 'N',
            'Radian' => 'rad',
            'Lux' => 'm',
            'Pascal' => 'Pa',
            'Hertz' => 'Hz',
            'Gray' => 'Gy',
            'Katal' => 'kat',
            'Becquerel' => 'Bq',
            'Metre' => 'm',
            'Kilogram' => 'Kg',
            'Candela' => 'cd',
            'Joule' => 'J',
            'Degreee Celsius' => '°C',
            'Steradian' => 'sr',
            'Kelvin' => 'K',
        ];
    }
}
