<?php

namespace toTwig\Converter;

/**
 * Class OxidIncludeDynamicConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
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
        /**
         * $pattern is supposed to detect structure like this:
         * [{oxid_include_dynamic file="form/formparams.tpl"}]
         **/
        $this->pattern = $this->getOpeningTagPattern('oxid_include_dynamic');
    }
}
