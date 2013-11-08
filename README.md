PHP Smarty to Twig Converter
==========================
toTwig is an utility to convert smarty template engine to twig template engine.

Installation
------------

### Locally

Download the
[`toTwig.phar`](https://raw.github.com/sankarsuda/toTwig/master/toTwig.phar) file and
store it somewhere on your computer.

### Globally (manual)

You can run these commands to easily acces `toTwig` from anywhere on your system:

	$ sudo wget https://raw.github.com/sankarsuda/toTwig/master/toTwig.phar -O /usr/local/bin/toTwig

or with curl:

	$ sudo curl https://raw.github.com/sankarsuda/toTwig/master/toTwig.phar -o /usr/local/bin/toTwig

then:

	$ sudo chmod a+x /usr/local/bin/toTwig

Then, just run `toTwig`

Update
------

### Locally

The `self-update` command tries to update toTwig itself:

	$ php toTwig.phar self-update

### Globally (manual)

You can update toTwig through this command:

	$ sudo toTwig self-update

Usage
-----

The `convert` command tries to fix as much coding standards
problems as possible on a given file or directory:

	php toTwig.phar convert /path/to/dir
	php toTwig.phar convert /path/to/file

The `--converters` option lets you choose the exact converters to
apply (the converter names must be separated by a comma):

	php toTwig.phar convert /path/to/dir --converters=for,if,misc

You can also blacklist the converters you don't want if this is more convenient,
using `-name`:

	php toTwig.phar convert /path/to/dir --converters=-for,-if

A combination of `--dry-run`, `--verbose` and `--diff` will
display summary of proposed changes, leaving your files unchanged.

All converters apply by default.

Choose from the list of available converters:

 * **include**  Convert smarty include to twig include

 * **assign**   Convert smarty {assign} to twig {% set foo = 'foo' %}

 * **variable** Convert smarty variable {$var.name} to twig {{ var.name }}

 * **comment**  Convert smarty comments {* *} to twig {# #}

 * **misc**     Convert smarty general tags like {ldelim} {rdelim} {literal}

 * **if**       Convert smarty if/else/elseif to twig

 * **for**      Convert foreach/foreachelse to twig


The `--config` option customizes the files to analyse, based
on some well-known directory structures:

	# For the Symfony 2.1 branch
	php toTwig.phar convert /path/to/sf21 --config=sf21

Choose from the list of available configurations:

 * **default** A default configuration

The `--dry-run` option displays the files that need to be
fixed but without actually modifying them:

	php toTwig.phar convert /path/to/code --dry-run

Instead of using command line options to customize the converter, you can save the
configuration in a `.php_st` file in the root directory of
your project. The file must return an instance of
`sankar\ST\ConfigInterface`, which lets you configure the converters, the files,
and directories that need to be analyzed:

	<?php

	$finder = sankar\ST\Finder\DefaultFinder::create()
		->exclude('somefile')
		->in(__DIR__)
	;

	return sankar\ST\Config\Config::create()
		->converters(array('if', 'for'))
		->finder($finder)
	;

Contribute
----------

The tool comes with quite a few built-in converters and finders, but everyone is
more than welcome to [contribute](https://github.com/sankarsuda/toTwig) more
of them.

### Converter

A *converter* is a class that tries to convert one tag (a `Converter` class must
extends `ConverterAbstract`).

### Configs

A *config* knows about the files and directories that must be
scanned by the tool when run in the directory of your project. It is useful
for projects that follow a well-known directory structures (like for Symfony
projects for instance).
