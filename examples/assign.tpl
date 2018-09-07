[{assign var="name" value="Bob"}]

[{assign "name" "Bob"}] [{* short-hand *}]

[{assign var="name" value="Bob" nocache}]

[{assign "name" "Bob" nocache}] [{* short-hand *}]

[{assign var=running_total value=$running_total+$some_array[$row].some_value}]

[{assign var="foo" value="bar" scope="root"}]

[{assign var="foo" value=$bar}]

[{assign var="foo" $bar}]

[{assign var="foo" "bar" scope="global"}]

[{* [{assign var=foo value=[{counter}]}]  // plugin result *}] // Counter is not implemented yet

[{assign var=foo value=substr($bar,2,5)}]  // PHP function result

[{assign var=foo value=$bar|strlen}]  // using modifier

[{assign var=foo value=$buh+$bar|strlen}]  // more complex expression