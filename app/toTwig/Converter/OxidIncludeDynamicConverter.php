<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class OxidIncludeDynamicConverter
 */
class OxidIncludeDynamicConverter extends IncludeConverter
{

    protected $name = 'oxid_include_dynamic';
    protected $description = "Convert smarty {oxid_include_dynamic} to twig function {% include_dynamic %}";
    protected $priority = 100;

    protected $string = '{% include_dynamic :template :with :vars %}';

    /**
     * OxidIncludeDynamicConverter constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->pattern = $this->getOpeningTagPattern('oxid_include_dynamic');
    }
}
