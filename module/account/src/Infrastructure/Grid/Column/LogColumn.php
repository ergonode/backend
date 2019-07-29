<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Grid\Column;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\AbstractColumn;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class LogColumn extends AbstractColumn
{
    public const TYPE = 'TEXT';

    /**
     * @var Language
     */
    private $language;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var string
     */
    private $parameterField;

    /**
     * @param string              $logField
     * @param string              $parameterField
     * @param string              $label
     * @param Language            $language
     * @param TranslatorInterface $translator
     */
    public function __construct(string $logField, string $parameterField, string $label, Language $language, TranslatorInterface $translator)
    {
        parent::__construct($logField, $label);
        $this->language = $language;
        $this->translator = $translator;
        $this->parameterField = $parameterField;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param string $id
     * @param array  $row
     *
     * @return null|string
     */
    public function render(string $id, array $row): ?string
    {
        $parameters = [];
        foreach (json_decode($row[$this->parameterField], true) as $key => $parameter) {
            if (is_string($parameter)) {
                $parameters[sprintf('%%%s%%', $key)] = $parameter;
            }
        }

        return $this->translator->trans($row[$id], $parameters, 'log', strtolower($this->language->getCode()));
    }
}
