<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig;

/**
 * Class FilterNameMap
 *
 * @package toTwig
 */
class FilterNameMap
{

    const NAME_MAP = [
        'smartwordwrap' => 'smart_wordwrap',
        'date_format' => 'date_format',
        'oxaddparams' => 'add_url_parameters',
        'oxaddslashes' => 'add_slashes',
        'oxenclose' => 'enclose',
        'oxfilesize' => 'file_size',
        'oxformattime' => 'format_time',
        'oxformdate' => 'format_date',
        'oxmultilangassign' => 'translate',
        'oxmultilangsal' => 'translate_salutation',
        'oxnubmerformat' => 'format_currency',
        'oxtruncate' => 'truncate',
        'oxwordwrap' => 'wordwrap',
        'count' => 'length',
        'strip_tags' => 'striptags',
        'oxupper' => 'upper',
        'oxlower' => 'lower',
        'oxescape' => 'escape'
    ];

    /**
     * @param string $filterName
     *
     * @return string
     */
    public static function getConvertedFilterName(string $filterName): string
    {
        return isset(self::NAME_MAP[$filterName]) ? self::NAME_MAP[$filterName] : $filterName;
    }
}
