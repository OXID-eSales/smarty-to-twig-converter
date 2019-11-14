<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class InsertConverter
 */
class InsertConverter extends IncludeConverter
{

    protected $name = 'insert';
    protected $description = 'Convert smarty insert to twig include';

    protected $string = '{% include :template :with :vars %}';
    protected $attrName = 'name';

    /**
     * InsertConverter constructor.
     */
    public function __construct()
    {
        parent::__construct();

        /**
         * $pattern is supposed to detect structure like this:
         * [{insert name="oxid_content" ident="foo"}]
         **/
        $this->pattern = $this->getOpeningTagPattern('insert');
    }
}
