Converting Smarty templates to Twig
===================================

Converting tool located at
[GitHub](https://github.com/OXID-eSales/oxideshop-to-twig-converter)
allows to convert existing Smarty template files to Twig syntax. The
tool besides standard Smarty syntax is adjusted to handle custom OXID
modifications and extensions.

### Usage

The convert command tries to fix as much coding standards problems as
possible on a given file, directory or database.

### path and ext parameters

Converter can work with files and directories:

`php toTwig convert --path=/path/to/dir`
`php toTwig convert --path=/path/to/file`

By default files with `.html.twig` extension will be created. To specify
different extensions use `--ext` parameter:

`php toTwig convert --path=/path/to/dir --ext=.js.twig`

### database and database-columns parameters

It also can work with databases:

`php toTwig convert --database="mysql://user:password@localhost/db"`

The `--database` parameter gets [database doctrine-like
URL](https://www.doctrine-project.org/projects/doctrine-dbal/en/2.9/reference/configuration.html#connecting-using-a-url).
Converter by default converts following tables columns:
`oxactions.OXLONGDESC`, `oxactions.OXLONGDESC_1`,
`oxcontents.OXCONTENT`, `oxcontents.OXCONTENT_1`.

The `--database-columns` option lets you choose tables columns to be
converted (the table column names has to be specified in
table\_a.column\_b format and separated by comma):

`php toTwig convert --database="..." --database-columns=oxactions.OXLONGDESC,oxcontents.OXCONTENT`

You can also blacklist the table columns you don't want using
-table\_a.column\_b:

`php toTwig convert --database="..." --database-columns=-oxactions.OXLONGDESC_1,-oxcontents.OXCONTENT_1`

### converters parameter

The `--converters` option lets you choose the exact converters to apply
(the converter names must be separated by a comma):

`php toTwig convert --path=/path/to/dir --ext=.html.twig --converters=for,if,misc`

You can also blacklist the converters you don't want if this is more
convenient, using -name:

`php toTwig convert --path=/path/to/dir --ext=.html.twig --converters=-for,-if`

### dry-run, verbose and diff parameters

A combination of `--dry-run`, `--verbose` and `--diff` will display
summary of proposed changes, leaving your files unchanged.

All converters apply by default.

The `--dry-run` option displays the files that need to be fixed but
without actually modifying them:

`php toTwig convert --path=/path/to/code --ext=.html.twig --dry-run`

### config-path parameter

Instead of building long line commands it is possible to inject PHP
configuration code. Two example files are included in main directory:
`config_file.php` and `config_database.php`. To include config file use
--config-path parameter:

`php toTwig convert --config-path=config_file.php`

Config script should return instance of `toTwig\Config\ConfigInterface`.
It can be created using `toTwig\Config\Config::create()` static method.


### Running database conversion tests


To run database conversion tests, sqlite is required. You can do this by running following commands:

    $ sudo apt-get install sqlite3
    $ sudo apt-get install php7.2-sqlite

### Known issues


-   In Twig by default all variables are escaped. Some of variables
    should be filtered with ``raw` filter to avoid this.
-   Variable scope. In Twig variables declared in templates have scopes
    limited by block (`{% block %}`, `{% for %}` and so on). Some
    variables should be declared outside these blocks if they are used
    outside.
-   Redeclaring blocks - it’s forbidden in Twig.
-   Access to array item `$myArray.$itemIndex` should be manually
    translated to `myArray[itemIndex]`
-   Problem with checking non existing (null) properties. E.g. we want
    to check the value of non-existing property
    `oxarticles__oxunitname`. Twig checks with `isset` if this property
    exists and it’s not, so Twig assumes that property name is function
    name and tries to call it.
-   Uses of regex string in templates - the tool can break or work
    incorrectly on so complex cases - it’s safer to manually copy&paste
    regular expression.
-   `[{section}]` - `loop` is array or integer - different behaviors.
    The tool is not able to detect variable type.

### Converted plugins and syntax pieces

Here is list of plugins and syntax pieces with basic examples how it is
converted. Note that these examples are only to show how it is converted
and doesn't cover all possible cases as additional parameters, block
nesting, repetitive calls (as for counter and cycle functions) etc.

### Core Smarty

#### assign =\> set

Converter name: `assign`

Smarty:\
`[{assign var="name" value="Bob"}]`

Twig:\
`{% set name = "Bob" %}`

#### block =\> block

Converter name: `block`

Smarty:\
`[{block name="title"}]Default Title[{/block}]`

Twig:\
`{% block title %}Default Title{% endblock %}`

#### capture =\> set

Converter name: `CaptureConverter`

Smarty:\
`[{capture name="foo" append="var"}] bar [{/capture}]`

Twig:\
`{% set foo %}{{ var }} bar {% endset %}`

#### Comments

Converter name: `comment`

Smarty:\
`[{* foo *}]`

Twig:\
`{# foo #}`

#### counter =\> set

Converter name: `counter`

Smarty:\
`[{counter}]`

Twig:\
`{% set defaultCounter = ( defaultCounter ` default(0) ) + 1 %}`

#### cycle =\> smarty\_cycle

Converter name: `cycle`

Smarty:\
`[{cycle values="val1,val2,val3"}]`

Twig:\
`{{ smarty_cycle(["val1", "val2", "val3"]) }}`

#### foreach =\> for

Converter name: `for`

Smarty:\
`[{foreach $myColors as $color}]foo[{/foreach}]`

Twig:\
`{% for color in myColors %}foo{% endfor %}`

#### if =\> if

Converter name: `if`

Smarty:\
`[{if !$foo or $foo->bar or $foo`bar:foo["hello"]}]foo[{/if}]`

Twig:\
`{% if not foo or foo.bar or foo`bar(foo["hello"]) %}foo{% endif %}`

#### include =\> include

Converter name: `include`

Smarty:\
`[{include file='page_header.tpl'}]`

Twig:\
`{% include 'page_header.tpl' %}`

#### insert =\> include

Converter name: `insert`

Smarty:\
`[{insert name="oxid_tracker" title="PRODUCT_DETAILS"`oxmultilangassign product=$oDetailsProduct cpath=$oView->getCatTreePath()}]`

Twig:\
`{% include "oxid_tracker" with {title: "PRODUCT_DETAILS"`oxmultilangassign, product: oDetailsProduct, cpath: oView.getCatTreePath()} %}`

#### mailto =\> mailto

Converter name: `mailto`

Smarty:\
`[{mailto address='me@example.com'}]`

Twig:\
`{{ mailto('me@example.com') }}`

#### math =\> core Twig math syntax

Converter name: `math`

Smarty:\
`[{math equation="x + y" x=1 y=2}]`

Twig:\
`{{ 1 + 2 }}`

#### Variable conversion

Converter name: `variable`

  |Smarty                          |Twig
  |------------------------------- |-----------------------------
  |[{$var}]                        |{{ var }}|
  |[{$contacts.fax}]               |{{ contacts.fax }}|
  |[{$contacts[0]}]                |{{ contacts[0] }}|
  |[{$contacts[2][0]}]             |{{ contacts[2][0] }}|
  |[{$person->name}]               |{{ person.name }}|
  |[{$oViewConf->getUrl($sUrl)}]   |{{ oViewConf.getUrl(sUrl) }}
  |[{($a && $b) &vert;&vert; $c}]  |{{ (a and b) or c }}|

#### Other

Converter name: `misc`

  |Smarty                          |Twig|
  |------------------------------- |----------------------------------------|
  |[{ldelim}]foo[{ldelim}]         |foo|
  |[{literal}]foo[{/literal}]      |{# literal #}foo{# /literal #}|
  |[{strip}]foo[{/strip}]          |{% spaceless %}foo{% endspaceless %}|

### OXID custom extensions

#### assign\_adv =\> set assign\_advanced

Converter name: `assign_adv`

Smarty:\
`[{ assign_adv var="name" value="Bob" }]`

Twig:\
`{% set name = assign_advanced("Bob") %}`

#### oxcontent =\> include content

Converter name: `oxcontent`

Smarty:\
`[{oxcontent ident='oxregisteremail'}]`

Twig:\
`{% include 'content::ident::oxregisteremail' %}`

#### oxeval =\> include(template\_from\_string())

Converter name: `OxevalConverter`

Smarty:\
`[{oxeval var=$variable}]`

Twig:\
`{{ include(template_from_string(variable)) }}`

#### oxgetseourl =\> seo\_url

Converter name: `oxgetseourl`

Smarty:\
`[{oxgetseourl ident=$oViewConf->getSelfLink()`cat:"cl=basket"}]`

Twig:\
`{{ seo_url({ ident: oViewConf.getSelfLink()`cat("cl=basket") }) }}`

#### oxhasrights =\> hasrights

Converter name: `oxhasrights`

Smarty:\
`[{oxhasrights object=$edit readonly=$readonly}]foo[{/oxhasrights}]`

Twig:\
`{% hasrights { "object": "edit", "readonly": "readonly", } %}foo{% endhasrights %}`

#### oxid\_include\_dynamic =\> include\_dynamic

Converter name: `oxid_include_dynamic`

Smarty:\
`[{oxid_include_dynamic file="form/formparams.tpl"}]`

Twig:\
`{% include_dynamic "form/formparams.tpl" %}`

#### oxid\_include\_widget =\> include\_widget

Converter name: `oxid_include_widget`

Smarty:\
`[{oxid_include_widget cl="oxwCategoryTree" cnid=$oView->getCategoryId() deepLevel=0 noscript=1 nocookie=1}]`

Twig:\
`{{ include_widget({ cl: "oxwCategoryTree", cnid: oView.getCategoryId(), deepLevel: 0, noscript: 1, nocookie: 1 }) }}`

#### oxifcontent =\> ifcontent

Converter name: `oxifcontent`

Smarty:\
`[{oxifcontent ident="TOBASKET" object="aObject"}]foo[{/oxifcontent}]`

Twig:\
`{% ifcontent ident "TOBASKET" set aObject %}foo{% endifcontent %}`

#### oxinputhelp =\> include "inputhelp.tpl"

Converter name: `oxinputhelp`

Smarty:\
`[{oxinputhelp ident="foo"}]`

Twig:\
`{% include "inputhelp.tpl" with {'sHelpId': getSHelpId(foo), 'sHelpText': getSHelpText(foo)} %}`

#### oxmailto =\> oxmailto

Converter name: `oxmailto`

Smarty:\
`[{oxmailto address='me@example.com'}]`

Twig:\
`{{ mailto('me@example.com') }}`

#### oxmultilang =\> translate

Converter name: `oxmultilang`

Smarty:\
`[{oxmultilang ident="ERROR_404"}]`

Twig:\
`{{ translate({ ident: "ERROR_404" }) }}`

#### oxprice =\> format\_price

Converter name: `oxprice`

Smarty:\
`[{oxprice price=$basketitem->getUnitPrice() currency=$currency}]`

Twig:\
`{{ format_price(basketitem.getUnitPrice(), { currency: currency }) }}`

#### oxscript =\> script

Converter name: `oxscript`

Smarty:\
`[{oxscript include="js/pages/details.min.js" priority=10}]`

Twig:\
`{{ script({ include: "js/pages/details.min.js", priority: 10, dynamic: __oxid_include_dynamic }) }}`

#### oxstyle =\> style

Converter name: `oxstyle`

Smarty:\
`[{oxstyle include="css/libs/chosen/chosen.min.css"}]`

Twig:\
`{{ style({ include: "css/libs/chosen/chosen.min.css" }) }}`

#### section =\> for

Converter name: `section`

Smarty:\
`[{section name=picRow start=1 loop=10}]foo[{/section}]`

Twig:\
`{% for picRow in 1..10 %}foo{% endfor %}`

#### Filters

  |Smarty                 |Twig|
  |---------------------- |---------------------------|
  |smartwordwrap          |smart_wordwrap|
  |date_format            |date_format|
  |oxaddparams            |add_url_parameters|
  |oxaddslashes           |add_slashes|
  |oxenclose              |enclose|
  |oxfilesize             |file_size|
  |oxformattime           |format_time|
  |oxformdate             |format_date|
  |oxmultilangassign      |translate|
  |oxmultilangsal         |translate_salutation|
  |oxnubmerformat         |format_currency|
  |oxtruncate             |truncate|
  |oxwordwrap             |wordwrap|

