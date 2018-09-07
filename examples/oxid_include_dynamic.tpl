[{* Basic usage *}]
[{oxid_include_dynamic file="form/formparams.tpl"}]

[{* Example from OXID*}]
[{oxid_include_dynamic file="widget/product/compare_links.tpl" testid="_`$iIndex`" type="compare" aid=$product->oxarticles__oxid->value anid=$altproduct in_list=$product->isOnComparisonList() page=$oView->getActPage()}]

[{* With spaces *}]
[{ oxid_include_dynamic file="form/formparams.tpl" }]
