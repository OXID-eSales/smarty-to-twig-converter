simple

[{$var}]
[{$var_name}]

Associative arrays

[{$Contacts.fax}]<br />
[{$Contacts.email}]<br />
[{* you can print arrays of arrays as well *}]
[{$Contacts.phone.home}]<br />
[{$Contacts.phone.cell}]<br />

Array indexes

[{$Contacts[0]}]<br />
[{$Contacts[1]}]<br />
[{* you can print arrays of arrays as well *}]
[{$Contacts[2][0]}]<br />
[{$Contacts[2][1]}]<br />

Objects

name:  [{$person->name}]<br />
email: [{$person->email}]<br />