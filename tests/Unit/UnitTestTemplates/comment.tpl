[{block name="dd_layout_page_header_icon_menu_languages"}]
    [{* Language Dropdown*}]
    [{oxid_include_widget cl="oxwLanguageList" lang=$oViewConf->getActLanguageId() _parent=$oView->getClassName() nocookie=1 _navurlparams=$oViewConf->getNavUrlParams() anid=$oViewConf->getActArticleId()}]
[{/block}]