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
    protected string $name = 'oxid_include_dynamic';
    protected string $description = "Convert smarty {oxid_include_dynamic} to twig function {% include_dynamic %}";
    protected int $priority = 100;

    protected string $string = '{% include_dynamic :template :with :vars %}';

    /**
     * OxidIncludeDynamicConverter constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->pattern = $this->getOpeningTagPattern('oxid_include_dynamic');
    }
}
