<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Core\Domain\ValueObject\LanguagePrivileges;
use Faker\Provider\Base as BaseProvider;

class LanguagePrivilegesFaker extends BaseProvider
{
    private const ACTIVE_LANGUAGE_CODES = [
        'en_GB',
        'pl_PL',
    ];

    private const ALL_LANGUAGE_CODES = [
        'af_ZA',
        'ar_AE',
        'ar_BH',
        'ar_DZ',
        'ar_EG',
        'ar_IQ',
        'ar_JO',
        'ar_KW',
        'ar_LB',
        'ar_LY',
        'ar_MA',
        'ar_OM',
        'ar_QA',
        'ar_SA',
        'ar_SY',
        'ar_TN',
        'ar_YE',
        'az_AZ',
        'be_BY',
        'bg_BG',
        'bs_BA',
        'ca_ES',
        'cs_CZ',
        'cy_GB',
        'da_DK',
        'de_AT',
        'de_CH',
        'de_DE',
        'de_LI',
        'de_LU',
        'dv_MV',
        'el_GR',
        'en_AU',
        'en_BZ',
        'en_CA',
        'en_CB',
        'en_GB',
        'en_GH',
        'en_IE',
        'en_IL',
        'en_JM',
        'en_NG',
        'en_NZ',
        'en_PH',
        'en_TT',
        'en_US',
        'en_ZA',
        'en_ZW',
        'es_AR',
        'es_BO',
        'es_CL',
        'es_CO',
        'es_CR',
        'es_DO',
        'es_EC',
        'es_ES',
        'es_GT',
        'es_HN',
        'es_MX',
        'es_NI',
        'es_PA',
        'es_PE',
        'es_PR',
        'es_PY',
        'es_SV',
        'es_UY',
        'es_VE',
        'et_EE',
        'eu_ES',
        'fa_IR',
        'fi_FI',
        'fo_FO',
        'fr_BE',
        'fr_CA',
        'fr_CH',
        'fr_FR',
        'fr_LU',
        'fr_MA',
        'fr_MC',
        'gl_ES',
        'ga_GB',
        'gd_GB',
        'gu_IN',
        'he_IL',
        'hi_IN',
        'hr_BA',
        'hr_HR',
        'hu_HU',
        'hy_AM',
        'id_ID',
        'is_IS',
        'it_CH',
        'it_IT',
        'ja_JP',
        'ka_GE',
        'kk_KZ',
        'kn_IN',
        'ko_KR',
        'ky_KG',
        'lt_LT',
        'lv_LV',
        'mi_NZ',
        'mk_MK',
        'mn_MN',
        'mr_IN',
        'ms_BN',
        'ms_MY',
        'mt_MT',
        'nb_NO',
        'nl_BE',
        'nl_NL',
        'nn_NO',
        'ns_ZA',
        'pa_IN',
        'pl_PL',
        'ps_AR',
        'pt_AO',
        'pt_BR',
        'pt_PT',
        'qu_BO',
        'qu_EC',
        'qu_PE',
        'ro_RO',
        'ru_RU',
        'sa_IN',
        'se_FI',
        'se_NO',
        'se_SE',
        'sk_SK',
        'sl_SI',
        'sq_AL',
        'sr_BA',
        'sr_SP',
        'sv_FI',
        'sv_SE',
        'sw_KE',
        'ta_IN',
        'te_IN',
        'th_TH',
        'tl_PH',
        'tn_ZA',
        'tr_TR',
        'tt_RU',
        'ts_ZA',
        'uk_UA',
        'ur_PK',
        'uz_UZ',
        'vi_VN',
        'xh_ZA',
        'zh_CN',
        'zh_HK',
        'zh_MO',
        'zh_SG',
        'zh_TW',
        'zu_ZA',
    ];

    /**
     * @return array|LanguagePrivileges[]
     */
    public function languagePrivilegesCollection(bool $all = false): array
    {
        $languageCodes = self::ACTIVE_LANGUAGE_CODES;
        if ($all) {
            $languageCodes = array_merge($languageCodes, self::ALL_LANGUAGE_CODES);
        }

        $result = [];
        foreach ($languageCodes as $languageCode) {
            $result[$languageCode] = new LanguagePrivileges(true, true);
        }

        return $result;
    }
}
