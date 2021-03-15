<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Test\Behat\Context;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\FeatureNode;
use Behatch\Context\JsonContext;

class ExtendJsonContext extends JsonContext
{
    private FeatureNode $feature;

    /**
     * @BeforeScenario
     */
    public function beforeScenario(BeforeScenarioScope $scope): void
    {
        $this->feature = $scope->getFeature();
    }

    /**
     * @override @Then the JSON should be valid according to the schema :filename
     *
     * {@inheritDoc}
     */
    public function theJsonShouldBeValidAccordingToTheSchema($filename): void
    {
        $directory = dirname(realpath($this->feature->getFile()));
        $path = $directory.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.$filename;

        $newFilename = realpath($path);

        if ($newFilename && is_file($newFilename)) {
            $filename = $newFilename;
        }

        parent::theJsonShouldBeValidAccordingToTheSchema($filename);
    }
}
