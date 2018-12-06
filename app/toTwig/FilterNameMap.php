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
        'colon' => 'getTranslatedColon',
        'smartwordwrap' => 'smartWordWrap',
        'date_format' => 'dateFormat'
        ];

    public static function getConvertedFilterName(string $filterName): string
    {
        return isset(self::nameMap[$filterName]) ? self::nameMap[$filterName] : $filterName;
    }
}
