<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Builder;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Importer\Infrastructure\Provider\AttributeProposalProvider;
use Ergonode\Importer\Infrastructure\Configuration\ImportConfiguration;
use Ergonode\Importer\Infrastructure\Configuration\Column\ProposalColumn;
use Ergonode\Importer\Infrastructure\Configuration\Column\AttributeColumn;
use Ergonode\Importer\Infrastructure\Configuration\Column\ConfigurationColumnInterface;

/**
 */
class ImportConfigurationBuilder
{
    /**
     * @var AttributeRepositoryInterface;
     */
    private AttributeRepositoryInterface $repository;

    /**
     * @var AttributeProposalProvider
     */
    private AttributeProposalProvider $provider;

    /**
     * @param AttributeRepositoryInterface $repository
     * @param AttributeProposalProvider    $provider
     */
    public function __construct(AttributeRepositoryInterface $repository, AttributeProposalProvider $provider)
    {
        $this->repository = $repository;
        $this->provider = $provider;
    }

    /**
     * @param array $headers
     * @param array $lines
     *
     * @return ImportConfiguration
     *
     * @throws \Exception
     */
    public function propose(array $headers, array $lines): ImportConfiguration
    {
        $result = new ImportConfiguration();
        foreach ($headers as $name) {
            $result->add($this->calculate($name, $lines[$name]));
        }

        return $result;
    }

    /**
     * @param string $name
     * @param array  $values
     *
     * @return ConfigurationColumnInterface
     *
     * @throws \Exception
     */
    private function calculate(string $name, array $values): ConfigurationColumnInterface
    {
        $attributeCode = new AttributeCode($name);
        $attributeId = AttributeId::fromKey($attributeCode->getValue());

        $attribute = $this->repository->load($attributeId);

        if (null === $attribute) {
            $calculator = $this->provider->provide($name, $values);
            $attributeType = $calculator->getTypeProposal();

            return new ProposalColumn(
                $name,
                $attributeCode->getValue(),
                $attributeType
            );
        }

        return new AttributeColumn(
            $name,
            $attributeCode->getValue()
        );
    }
}
