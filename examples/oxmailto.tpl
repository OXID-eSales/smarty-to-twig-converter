[{* Basic usage *}]
[{oxmailto address='me@example.com'}]

[{* Values converting *}]
[{oxmailto address=$oxcmp_shop->oxshops__oxinfoemail->value}]

[{* With parameters *}]
[{oxmailto address=$oxcmp_shop->oxshops__oxinfoemail->value encode="javascript"}]

[{* Nested quotes *}]
[{oxmailto address='me@example.com' subject='Subject of email' extra="class='email'"}]

[{* With spaces *}]
[{ oxmailto address='me@example.com' }]
