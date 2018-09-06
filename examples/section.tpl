[{section name=picRow start=1 loop=10}]
    foo
[{/section}]

[{ section name=picRow start=1 loop=10 }]
    foo
[{ /section }]

[{section name=picRow start=1 loop=10}]
    [{picRow}]
[{/section}]

[{ section name=picRow start=1 loop=10 }]
    [{picRow}]
[{ /section }]

[{section name=picRow start=1 loop=$iPicCount+1 step=1}]
    [{assign var="iIndex" value=$smarty.section.picRow.index}]
[{/section}]

[{ section name=picRow start=1 loop=$iPicCount+1 step=1 }]
    [{assign var="iIndex" value=$smarty.section.picRow.index}]
[{ /section }]

[{section name=picRow loop=$iPicCount+1 step=1}]
    [{assign var="iIndex" value=$smarty.section.picRow.index}]
[{/section}]

[{ section name=picRow loop=$iPicCount+1 step=1 }]
    [{assign var="iIndex" value=$smarty.section.picRow.index}]
[{ /section }]