
{include file='page_header.tpl'}

{* body of template goes here, the $tpl_name variable
   is replaced with a value eg 'contact.tpl'
*}

{include file="$tpl_name.tpl" "value"}

{include file='links.tpl' title="Newest's links" links=$link_array}

{* body of template goes here *}
{include file='footer.tpl' foo='bar'}

{* absolute filepath *}
{include file='/usr/local/include/templates/header.tpl'}

{* absolute filepath (same thing) *}
{include file='file:/usr/local/include/templates/header.tpl'}

{* windows absolute filepath (MUST use "file:" prefix) *}
{include file='file:C:/www/pub/templates/header.tpl'}

{* include from template resource named "db" *}
{include file='db:header.tpl'}

{* include a $variable template - eg $module = 'contacts' *}
{include file="$module.tpl"}

{* wont work as its single quotes ie no variable substitution *}
{include file='$module.tpl'}

{* include a multi $variable template - eg amber/links.view.tpl *}
{include file="$style_dir/$module.$view.tpl"}
