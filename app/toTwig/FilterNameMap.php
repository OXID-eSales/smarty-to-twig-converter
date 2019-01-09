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
    const nameMap = [
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
    ];

    public static function getConvertedFilterName(string $filterName): string
    {
        return isset(self::nameMap[$filterName]) ? self::nameMap[$filterName] : $filterName;
    }
}
