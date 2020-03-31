<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

/**
 */
final class Version20180610093112 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');

        $this->addSql(
            'CREATE TABLE language (
                      id UUID NOT NULL, 
                      iso VARCHAR(5) NOT NULL, 
                      name VARCHAR(64),
                      system BOOLEAN NOT NULL DEFAULT false, 
                      PRIMARY KEY(id)
              )'
        );
        $this->addSql(
            'CREATE TABLE translation
                    (
                        translation_id UUID NOT NULL,
                        language VARCHAR(5) NOT NULL,
                        PRIMARY KEY(translation_id, language)
                    )'
        );

        foreach ($this->getLanguages() as $iso => $name) {
            $this->addSql(
                'INSERT INTO language (id, iso, name) VALUES (?, ?, ?)',
                [Uuid::uuid4()->toString(), $name, $iso]
            );
        }

        $this->addSql('UPDATE language SET system = true WHERE iso in (\'EN\', \'pl-PL\')');

        $this->addSql('ALTER TABLE language rename column system to active');
    }

    /**
     * @return array
     */
    private function getLanguages(): array
    {
        return [
            'af' => 'af',
            'af_ZA' => 'af-ZA',
            'ar' => 'ar',
            'ar_AE' => 'ar-AE',
            'ar_BH' => 'ar-BH',
            'ar_DZ' => 'ar-DZ',
            'ar_EG' => 'ar-EG',
            'ar_IQ' => 'ar-IQ',
            'ar_JO' => 'ar-JO',
            'ar_KW' => 'ar-KW',
            'ar_LB' => 'ar-LB',
            'ar_LY' => 'ar-LY',
            'ar_MA' => 'ar-MA',
            'ar_OM' => 'ar-OM',
            'ar_QA' => 'ar-QA',
            'ar_SA' => 'ar-SA',
            'ar_SY' => 'ar-SY',
            'ar_TN' => 'ar-TN',
            'ar_YE' => 'ar-YE',
            'az' => 'az',
            'az_AZ' => 'az-AZ',
            'be' => 'be',
            'be_BY' => 'be-BY',
            'bg' => 'bg',
            'bg_BG' => 'bg-BG',
            'bs_BA' => 'bs-BA',
            'ca' => 'ca',
            'ca_ES' => 'ca-ES',
            'cs' => 'cs',
            'cs_CZ' => 'cs-CZ',
            'cy' => 'cy',
            'cy_GB' => 'cy-GB',
            'da' => 'da',
            'da_DK' => 'da-DK',
            'de' => 'de',
            'de_AT' => 'de-AT',
            'de_CH' => 'de-CH',
            'de_DE' => 'de-DE',
            'de_LI' => 'de-LI',
            'de_LU' => 'de-LU',
            'dv' => 'dv',
            'dv_MV' => 'dv-MV',
            'el' => 'el',
            'el_GR' => 'el-GR',
            'en' => 'en',
            'en_AU' => 'en-AU',
            'en_BZ' => 'en-BZ',
            'en_CA' => 'en-CA',
            'en_CB' => 'en-CB',
            'en_GB' => 'en-GB',
            'en_IE' => 'en-IE',
            'en_JM' => 'en-JM',
            'en_NZ' => 'en-NZ',
            'en_PH' => 'en-PH',
            'en_TT' => 'en-TT',
            'en_US' => 'en-US',
            'en_ZA' => 'en-ZA',
            'en_ZW' => 'en-ZW',
            'eo' => 'eo',
            'es' => 'es',
            'es_AR' => 'es-AR',
            'es_BO' => 'es-BO',
            'es_CL' => 'es-CL',
            'es_CO' => 'es-CO',
            'es_CR' => 'es-CR',
            'es_DO' => 'es-DO',
            'es_EC' => 'es-EC',
            'es_ES' => 'es-ES',
            'es_GT' => 'es-GT',
            'es_HN' => 'es-HN',
            'es_MX' => 'es-MX',
            'es_NI' => 'es-NI',
            'es_PA' => 'es-PA',
            'es_PE' => 'es-PE',
            'es_PR' => 'es-PR',
            'es_PY' => 'es-PY',
            'es_SV' => 'es-SV',
            'es_UY' => 'es-UY',
            'es_VE' => 'es-VE',
            'et' => 'et',
            'et_EE' => 'et-EE',
            'eu' => 'eu',
            'eu_ES' => 'eu-ES',
            'fa' => 'fa',
            'fa_IR' => 'fa-IR',
            'fi' => 'fi',
            'fi_FI' => 'fi-FI',
            'fo' => 'fo',
            'fo_FO' => 'fo-FO',
            'fr' => 'fr',
            'fr_BE' => 'fr-BE',
            'fr_CA' => 'fr-CA',
            'fr_CH' => 'fr-CH',
            'fr_FR' => 'fr-FR',
            'fr_LU' => 'fr-LU',
            'fr_MC' => 'fr-MC',
            'gl' => 'gl',
            'gl_ES' => 'gl-ES',
            'gu' => 'gu',
            'gu_IN' => 'gu-IN',
            'he' => 'he',
            'he_IL' => 'he-IL',
            'hi' => 'hi',
            'hi_IN' => 'hi-IN',
            'hr' => 'hr',
            'hr_BA' => 'hr-BA',
            'hr_HR' => 'hr-HR',
            'hu' => 'hu',
            'hu_HU' => 'hu-HU',
            'hy' => 'hy',
            'hy_AM' => 'hy-AM',
            'id' => 'id',
            'id_ID' => 'id-ID',
            'is' => 'is',
            'is_IS' => 'is-IS',
            'it' => 'it',
            'it_CH' => 'it-CH',
            'it_IT' => 'it-IT',
            'ja' => 'ja',
            'ja_JP' => 'ja-JP',
            'ka' => 'ka',
            'ka_GE' => 'ka-GE',
            'kk' => 'kk',
            'kk_KZ' => 'kk-KZ',
            'kn' => 'kn',
            'kn_IN' => 'kn-IN',
            'ko' => 'ko',
            'ko_KR' => 'ko-KR',
            'ky' => 'ky',
            'ky_KG' => 'ky-KG',
            'lt' => 'lt',
            'lt_LT' => 'lt-LT',
            'lv' => 'lv',
            'lv_LV' => 'lv-LV',
            'mi' => 'mi',
            'mi_NZ' => 'mi-NZ',
            'mk' => 'mk',
            'mk_MK' => 'mk-MK',
            'mn' => 'mn',
            'mn_MN' => 'mn-MN',
            'mr' => 'mr',
            'mr_IN' => 'mr-IN',
            'ms' => 'ms',
            'ms_BN' => 'ms-BN',
            'ms_MY' => 'ms-MY',
            'mt' => 'mt',
            'mt_MT' => 'mt-MT',
            'nb' => 'nb',
            'nb_NO' => 'nb-NO',
            'nl' => 'nl',
            'nl_BE' => 'nl-BE',
            'nl_NL' => 'nl-NL',
            'nn_NO' => 'nn-NO',
            'ns' => 'ns',
            'ns_ZA' => 'ns-ZA',
            'pa' => 'pa',
            'pa_IN' => 'pa-IN',
            'pl' => 'pl',
            'pl-PL' => 'pl-PL',
            'ps' => 'ps',
            'ps_AR' => 'ps-AR',
            'pt' => 'pt',
            'pt_BR' => 'pt-BR',
            'pt_PT' => 'pt-PT',
            'qu' => 'qu',
            'qu_BO' => 'qu-BO',
            'qu_EC' => 'qu-EC',
            'qu_PE' => 'qu-PE',
            'ro' => 'ro',
            'ro_RO' => 'ro-RO',
            'ru' => 'ru',
            'ru_RU' => 'ru-RU',
            'sa' => 'sa',
            'sa_IN' => 'sa-IN',
            'se' => 'se',
            'se_FI' => 'se-FI',
            'se_NO' => 'se-NO',
            'se_SE' => 'se-SE',
            'sk' => 'sk',
            'sk_SK' => 'sk-SK',
            'sl' => 'sl',
            'sl_SI' => 'sl-SI',
            'sq' => 'sq',
            'sq_AL' => 'sq-AL',
            'sr_BA' => 'sr-BA',
            'sr_SP' => 'sr-SP',
            'sv' => 'sv',
            'sv_FI' => 'sv-FI',
            'sv_SE' => 'sv-SE',
            'sw' => 'sw',
            'sw_KE' => 'sw-KE',
            'ta' => 'ta',
            'ta_IN' => 'ta-IN',
            'te' => 'te',
            'te_IN' => 'te-IN',
            'th' => 'th',
            'th_TH' => 'th-TH',
            'tl' => 'tl',
            'tl_PH' => 'tl-PH',
            'tn' => 'tn',
            'tn_ZA' => 'tn-ZA',
            'tr' => 'tr',
            'tr_TR' => 'tr-TR',
            'tt' => 'tt',
            'tt_RU' => 'tt-RU',
            'ts' => 'ts',
            'uk' => 'uk',
            'uk_UA' => 'uk-UA',
            'ur' => 'ur',
            'ur_PK' => 'ur-PK',
            'uz' => 'uz',
            'uz_UZ' => 'uz-UZ',
            'vi' => 'vi',
            'vi_VN' => 'vi-VN',
            'xh' => 'xh',
            'xh_ZA' => 'xh-ZA',
            'zh' => 'zh',
            'zh_CN' => 'zh-CN',
            'zh_HK' => 'zh-HK',
            'zh_MO' => 'zh-MO',
            'zh_SG' => 'zh-SG',
            'zh_TW' => 'zh-TW',
            'zu' => 'zu',
            'zu_ZA' => 'zu-ZA',
        ];
    }
}
