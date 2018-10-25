[{* Basic usage *}]
[{block name="title"}]Default Title[{/block}]

[{* Short-hand *}]
[{block "title"}]Default Title[{/block}]

[{* Prepend *}]
[{block name="title" prepend}]
    Page Title
[{/block}]

[{* Extends *}]
[{extends file="parent.tpl"}]

[{* $smarty.block.parent *}]
[{$smarty.block.parent}]

[{* Extends with parent call *}]
[{extends file="parent.tpl"}]
[{block name="title"}]
    You will see now - [{$smarty.block.parent}] - here
[{/block}]
