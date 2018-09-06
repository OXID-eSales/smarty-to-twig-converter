[{* Basic usage *}]
[{oxifcontent ident="TOBASKET" object="aObject"}]
    foo
[{/oxifcontent}]

[{* Values converting *}]
[{oxifcontent ident=$x object=$y}]
    foo
[{/oxifcontent}]

[{* Nested blocks *}]
[{oxifcontent ident=$x object=$y}]
    foo
    [{oxifcontent ident=$x2 object=$y2}]
        bar
    [{/oxifcontent}]
[{/oxifcontent}]

[{* With spaces *}]
[{ oxifcontent ident="TOBASKET" object="aObject" }]
    foo
[{ /oxifcontent }]
