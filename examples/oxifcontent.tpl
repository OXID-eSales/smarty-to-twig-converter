[{* Basic usage *}]
[{oxifcontent ident="TOBASKET" object="aObject"}]
    foo
[{/oxifcontent}]

[{* Values converting *}]
[{oxifcontent ident=$x object="y"}]
    foo
[{/oxifcontent}]

[{* Assignment *}]
[{oxifcontent ident="TOBASKET" object="aObject" assign=$var}]
    foo
[{/oxifcontent}]

[{* With spaces *}]
[{ oxifcontent ident="TOBASKET" object="aObject" }]
    foo
[{ /oxifcontent }]
