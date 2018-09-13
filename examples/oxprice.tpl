[{* OXID examples *}]
[{oxprice price=$basketitem->getUnitPrice() currency=$currency}]
[{oxprice price=$VATitem currency=$currency}]

[{* No currency *}]
[{oxprice price=$basketitem->getUnitPrice()}]

[{* With spaces *}]
[{ oxprice price=$basketitem->getUnitPrice() currency=$currency }]
