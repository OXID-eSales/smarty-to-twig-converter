[{* Few examples from assign (compatibility) *}]
[{assign_adv var="name" value="Bob"}]
[{assign_adv var="name" value=$bob}]
[{assign_adv var="where" value=$oView->getListFilter()}]
[{assign_adv var="template_title" value="MY_WISH_LIST"|oxmultilangassign}]

[{* Example for assign_dev function *}]
[{assign_adv var="invite_array" value="array('0' => '$sender_name', '1' => '$shop_name')"}]

[{* With spaces *}]
[{ assign_adv var="name" value="Bob" }]
