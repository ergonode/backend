<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\ValueObject;

/**
 */
class Language
{
    public const AR = 'AR';
    public const BG = 'BG';
    public const BS = 'BS';
    public const CS = 'CS';
    public const DA = 'DA';
    public const DE = 'DE';
    public const EL = 'EL';
    public const EN = 'EN';
    public const ES = 'ES';
    public const ET = 'ET';
    public const FI = 'FI';
    public const FR = 'FR';
    public const HE = 'HE';
    public const HI = 'HI';
    public const HR = 'HR';
    public const HU = 'HU';
    public const IT = 'IT';
    public const JA = 'JA';
    public const KK = 'KK';
    public const LT = 'LT';
    public const LV = 'LV';
    public const MK = 'MK';
    public const NL = 'NL';
    public const NO = 'NO';
    public const PL = 'PL';
    public const PT = 'PT';
    public const RO = 'RO';
    public const RU = 'RU';
    public const SI = 'SI';
    public const SK = 'SK';
    public const SL = 'SL';
    public const SQ = 'SQ';
    public const SR = 'SR';
    public const SV = 'SV';
    public const TR = 'TR';
    public const UA = 'UA';
    public const UK = 'UK';
    public const ZH = 'ZH';

    public const AVAILABLE = [
        self::AR,
        self::BG,
        self::BS,
        self::CS,
        self::DA,
        self::DE,
        self::EL,
        self::EN,
        self::ES,
        self::ET,
        self::FI,
        self::FR,
        self::HE,
        self::HI,
        self::HR,
        self::HU,
        self::IT,
        self::JA,
        self::KK,
        self::LT,
        self::LV,
        self::MK,
        self::NL,
        self::NO,
        self::PL,
        self::PT,
        self::RO,
        self::RU,
        self::SI,
        self::SK,
        self::SL,
        self::SQ,
        self::SR,
        self::SV,
        self::TR,
        self::UA,
        self::UK,
        self::ZH,
    ];

    /**
     * @var string
     */
    private string $code;

    /**
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = strtoupper(trim($code));
        if (!self::isValid($this->code)) {
            throw new \InvalidArgumentException(\sprintf('Code "%s" is not valid language code', $code));
        }
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getCode();
    }


    /**
     * @param string $code
     *
     * @return Language
     */
    public static function fromString(string $code): self
    {
        return new self($code);
    }

    /**
     * @param Language $language
     *
     * @return bool
     */
    public function isEqual(Language $language): bool
    {
        return $language->code === $this->code;
    }

    /**
     * @param string $code
     *
     * @return bool
     */
    public static function isValid(?string $code): bool
    {
        return \in_array($code, self::AVAILABLE, true);
    }
}
