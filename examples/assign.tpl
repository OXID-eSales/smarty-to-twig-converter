{% set name = "Bob" %}

{% set name = "Bob" %} {* short-hand *}

{% set name = "Bob" %}

{% set name = "Bob" %} {* short-hand *}

{% set running_total = running_total + some_array[row].some_value %}

{% set foo = "bar" %}

{% set foo = bar %}

{% set foo = bar %}

{% set foo = "bar" %}

{* [{assign var=foo value={{ counter }}}]  // plugin result *} // Counter is not implemented yet

{% set foo = substr(bar, 2, 5) %}  // PHP function result

{% set foo = bar|strlen %}  // using modifier

{% set foo = buh + bar|strlen %}  // more complex expression