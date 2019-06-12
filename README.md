PHP Smarty to Twig Converter
==========================
toTwig is an utility to convert smarty template engine to twig template engine. It supports custom oxid tags.

Installation
------------

Clone the [`toTwig`](https://github.com/OXID-eSales/oxideshop-to-twig-converter)
repository.

You can install `toTwig` anywhere on your system:

    $ cd <preferrableToTwigInstallationDirectory>
	$ git clone https://github.com/OXID-eSales/oxideshop-to-twig-converter.git .
	$ sudo chmod a+x ./toTwig

Then, just run `toTwig`

	$ ./toTwig

Update
-----

It is enough to use git pull in toTwig installation directory:

    $ cd <toTwigDirectory>
    $ git pull

Usage
-----

The `convert` command tries to fix as much coding standards
problems as possible on a given file or directory:

	`php toTwig convert /path/to/dir`
	`php toTwig convert /path/to/file`

The `--converters` option lets you choose the exact converters to
apply (the converter names must be separated by a comma):

	`php toTwig convert /path/to/dir --converters=for,if,misc`

You can also blacklist the converters you don't want if this is more convenient,
using `-name`:

	`php toTwig convert /path/to/dir --converters=-for,-if`

A combination of `--dry-run`, `--verbose` and `--diff` will
display summary of proposed changes, leaving your files unchanged.

All converters apply by default.

Choose from the list of available converters:

 * **assign**

 * **assign_adv**

 * **capture**

 * **comment**

 * **counter**

 * **cycle**

 * **if**

 * **include**

 * **insert**

 * **mailto**

 * **math**

 * **misc**

 * **oxcontent**

 * **oxeval**

 * **oxgetseourl**

 * **oxhasrights**

 * **oxid_include_dynamic**

 * **oxid_include_widget**

 * **oxifcontent**

 * **oxinputhelp**

 * **oxmailto**

 * **oxmultilang**

 * **oxprice**

 * **oxscript**

 * **oxstyle**

 * **oxvariantselect**

 * **section**

 * **sectionelse**

 * **strip**

 * **variable**
 
 //todo add converters


The `--config` option customizes the files to analyse, based
on some well-known directory structures:

	`# For the Symfony 2.1 branch`
	`php toTwig.phar convert /path/to/sf21 --config=sf21`

Choose from the list of available configurations:

  * **default* * A default configuration

The `--dry-run` option displays the files that need to be
fixed but without actually modifying them:

	`php toTwig convert /path/to/code --dry-run`

Instead of using command line options to customize the converter, you can save the
configuration in a `.php_st` file in the root directory of
your project. The file must return an instance of
`toTwig\ConfigInterface`, which lets you configure the converters, the files,
and directories that need to be analyzed:

	<?php

	$finder = toTwig\Finder\DefaultFinder::create()
		->exclude('somefile')
		->in(__DIR__)
	;

	return toTwig\Config\Config::create()
		->converters(array('if', 'for'))
		->finder($finder)
	;

Creating you own Converters
-----

A *converter * is a class that tries to convert one tag (a `Converter` class must
extends `ConverterAbstract`). To create new converter simply create new YourConverter.php
in app/toTwig/Converter/ directory. Converters are loaded automatically.

Configs
-----

A *config * knows about the files and directories that must be
scanned by the tool when run in the directory of your project. It is useful
for projects that follow a well-known directory structures (like for Symfony
projects for instance).

Running database conversion tests
-----

To run database conversion tests, sqlite is required. You can do this by running following commands:

    $ sudo apt-get install sqlite3
    $ sudo apt-get install php7.2-sqlite
