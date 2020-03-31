<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Tests\Domain\ValueObject;

use PHPUnit\Framework\TestCase;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class LanguageTest extends TestCase
{
    /**
     * @dataProvider validLanguage
     *
     * @param string $code
     */
    public function testValidLanguageCreation(string $code): void
    {
        $language = new Language($code);
        $this->assertSame($code, $language->getCode());
    }

    /**
     * @dataProvider invalidLanguage
     *
     * @param string $hex
     *
     */
    public function testInvalidLanguageCreation(string $hex): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Language($hex);
    }

    /**
     */
    public function testLanguageEquality(): void
    {
        $language1 = new Language('en');
        $language2 = new Language('en');
        $language3 = new Language('ru');

        $this->assertTrue($language1->isEqual($language2));
        $this->assertTrue($language2->isEqual($language1));
        $this->assertFalse($language1->isEqual($language3));
        $this->assertFalse($language2->isEqual($language3));
        $this->assertFalse($language3->isEqual($language1));
        $this->assertFalse($language3->isEqual($language2));
    }

    /**
     * @return array
     */
    public function validLanguage(): array
    {
        return [
            ['af'],
            ['af-ZA'],
            ['ar'],
            ['ar-AE'],
            ['ar-BH'],
            ['ar-DZ'],
            ['ar-EG'],
            ['ar-IQ'],
            ['ar-JO'],
            ['ar-KW'],
            ['ar-LB'],
            ['ar-LY'],
            ['ar-MA'],
            ['ar-OM'],
            ['ar-QA'],
            ['ar-SA'],
            ['ar-SY'],
            ['ar-TN'],
            ['ar-YE'],
            ['az'],
            ['az-AZ'],
            ['az-AZ'],
            ['be'],
            ['be-BY'],
            ['bg'],
            ['bg-BG'],
            ['bs-BA'],
            ['ca'],
            ['ca-ES'],
            ['cs'],
            ['cs-CZ'],
            ['cy'],
            ['cy-GB'],
            ['da'],
            ['da-DK'],
            ['de'],
            ['de-AT'],
            ['de-CH'],
            ['de-DE'],
            ['de-LI'],
            ['de-LU'],
            ['dv'],
            ['dv-MV'],
            ['el'],
            ['el-GR'],
            ['en'],
            ['en-AU'],
            ['en-BZ'],
            ['en-CA'],
            ['en-CB'],
            ['en-GB'],
            ['en-IE'],
            ['en-JM'],
            ['en-NZ'],
            ['en-PH'],
            ['en-TT'],
            ['en-US'],
            ['en-ZA'],
            ['en-ZW'],
            ['eo'],
            ['es'],
            ['es-AR'],
            ['es-BO'],
            ['es-CL'],
            ['es-CO'],
            ['es-CR'],
            ['es-DO'],
            ['es-EC'],
            ['es-ES'],
            ['es-ES'],
            ['es-GT'],
            ['es-HN'],
            ['es-MX'],
            ['es-NI'],
            ['es-PA'],
            ['es-PE'],
            ['es-PR'],
            ['es-PY'],
            ['es-SV'],
            ['es-UY'],
            ['es-VE'],
            ['et'],
            ['et-EE'],
            ['eu'],
            ['eu-ES'],
            ['fa'],
            ['fa-IR'],
            ['fi'],
            ['fi-FI'],
            ['fo'],
            ['fo-FO'],
            ['fr'],
            ['fr-BE'],
            ['fr-CA'],
            ['fr-CH'],
            ['fr-FR'],
            ['fr-LU'],
            ['fr-MC'],
            ['gl'],
            ['gl-ES'],
            ['gu'],
            ['gu-IN'],
            ['he'],
            ['he-IL'],
            ['hi'],
            ['hi-IN'],
            ['hr'],
            ['hr-BA'],
            ['hr-HR'],
            ['hu'],
            ['hu-HU'],
            ['hy'],
            ['hy-AM'],
            ['id'],
            ['id-ID'],
            ['is'],
            ['is-IS'],
            ['it'],
            ['it-CH'],
            ['it-IT'],
            ['ja'],
            ['ja-JP'],
            ['ka'],
            ['ka-GE'],
            ['kk'],
            ['kk-KZ'],
            ['kn'],
            ['kn-IN'],
            ['ko'],
            ['ko-KR'],
            ['ky'],
            ['ky-KG'],
            ['lt'],
            ['lt-LT'],
            ['lv'],
            ['lv-LV'],
            ['mi'],
            ['mi-NZ'],
            ['mk'],
            ['mk-MK'],
            ['mn'],
            ['mn-MN'],
            ['mr'],
            ['mr-IN'],
            ['ms'],
            ['ms-BN'],
            ['ms-MY'],
            ['mt'],
            ['mt-MT'],
            ['nb'],
            ['nb-NO'],
            ['nl'],
            ['nl-BE'],
            ['nl-NL'],
            ['nn-NO'],
            ['ns'],
            ['ns-ZA'],
            ['pa'],
            ['pa-IN'],
            ['pl'],
            ['pl-PL'],
            ['ps'],
            ['ps-AR'],
            ['pt'],
            ['pt-BR'],
            ['pt-PT'],
            ['qu'],
            ['qu-BO'],
            ['qu-EC'],
            ['qu-PE'],
            ['ro'],
            ['ro-RO'],
            ['ru'],
            ['ru-RU'],
            ['sa'],
            ['sa-IN'],
            ['se'],
            ['se-FI'],
            ['se-NO'],
            ['se-SE'],
            ['se-SE'],
            ['se-SE'],
            ['sk'],
            ['sk-SK'],
            ['sl'],
            ['sl-SI'],
            ['sq'],
            ['sq-AL'],
            ['sr-BA'],
            ['sr-BA'],
            ['sr-SP'],
            ['sr-SP'],
            ['sv'],
            ['sv-FI'],
            ['sv-SE'],
            ['sw'],
            ['sw-KE'],
            ['ta'],
            ['ta-IN'],
            ['te'],
            ['te-IN'],
            ['th'],
            ['th-TH'],
            ['tl'],
            ['tl-PH'],
            ['tn'],
            ['tn-ZA'],
            ['tr'],
            ['tr-TR'],
            ['tt'],
            ['tt-RU'],
            ['ts'],
            ['uk'],
            ['uk-UA'],
            ['ur'],
            ['ur-PK'],
            ['uz'],
            ['uz-UZ'],
            ['uz-UZ'],
            ['vi'],
            ['vi-VN'],
            ['xh'],
            ['xh-ZA'],
            ['zh'],
            ['zh-CN'],
            ['zh-HK'],
            ['zh-MO'],
            ['zh-SG'],
            ['zh-TW'],
            ['zu'],
            ['zu-ZA'],
        ];
    }

    /**
     * @return array
     */
    public function invalidLanguage(): array
    {
        return [
            ['pl_pl'],
            ['PLPL'],
            [''],
            ['XX-UU'],
            ['ENGLISH'],
            ['any incorrect phrase'],
        ];
    }
}
