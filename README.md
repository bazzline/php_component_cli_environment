# Command Line Environment for PHP

This project aims to deliver an free as in freedom, easy to use and lean as in fat free environment to create executable command line php files.

This project does not want to be competitive to a big console environment like the once mentioned below in the [link section](#links).

The versioneye status is:
[![Dependency Status](https://www.versioneye.com/user/projects/565b5ffe1b08f2000c0000df/badge.svg?style=flat)](https://www.versioneye.com/user/projects/565b5ffe1b08f2000c0000df)

Take a look on [openhub.net](https://www.openhub.net/p/php_component_cli_environment).

# Usage

## Create Executable Command Line File

```
./bin/net_bazzline_create_executable_command_line_file bin/hello
```

## Write Your Code

```php
#open bin/hello
#replace line "$usage			= basename(__FILE__) . ' [-v|--verbose]';" with the line below
$usage			= basename(__FILE__) . ' <your name> [-v|--verbose]';
#replace the line "//put in your business logic" with the code below
//begin of dependencies
$arguments  = $environment->getArguments();
$values     = $arguments->getValues();
//end of dependencies

//begin of argument validation
$valuesNotAreValid = (count($values) == 0);

if ($valuesNotAreValid) {
    throw new InvalidArgumentException(
        'invalid number of arguments provided'
    );
}
//end of argument validation

//begin of business logic
$name = ucfirst($values[0]);

$environment->outputIfVerbosityIsEnabled('provided values are: ' . implode(' ', $values));
$environment->output('Hello ' . $name);
//end of business logic

#execute ./bin/hello world
```

# Example

* [hello](https://github.com/bazzline/php_component_cli_environment/tree/master/example/hello)

# Install

## By Hand

```
mkdir -p vendor/net_bazzline/php_component_cli_environment
cd vendor/net_bazzline/php_component_cli_environment
git clone https://github.com/bazzline/php_component_cli_environment .
```

## With [Packagist](https://packagist.org/packages/net_bazzline/php_component_cli_environment)

```
    composer require net_bazzline/php_component_cli_environment:dev-master
```

# API

[API](http://www.bazzline.net/b4a1177a56e548d35388d421a8b12a9437a3bf50/index.html) is available at [bazzline.net](http://www.bazzline.net).


# History

* upcomming
    * @todo
        * add flag to not generate dependency section   //version 0.2.0
        * add flag to not generate validation section   //version 0.2.0
        * add "containsOneOf($string, $selects)" to Text
        * write tests
* [0.1.2](https://github.com/bazzline/php_component_cli_environment/tree/0.1.2) - released at 01.12.2015
    * updated dependency
* [0.1.1](https://github.com/bazzline/php_component_cli_environment/tree/0.1.1) - released at 29.11.2015
    * added link to openhub
    * added link to version eye
    * fixed broken [api](#api) link
    * fixed "tab" in usage generation
    * replaced "//put in your business logic" with "//begin of business logic\n//end of business logic"
    * updated dependency
* [0.1.0](https://github.com/bazzline/php_component_cli_environment/tree/0.1.0) - released at 10.10.2015
    * initial release

# Links

* [hoaproject console](https://github.com/hoaproject/Console)
* [symfony console](https://github.com/symfony/console)
* [windwalker console](https://github.com/ventoviro/windwalker-console)
* [duncan3dc console](https://github.com/duncan3dc/console)
* [webmozart console](https://github.com/webmozart/console)
* [zend console](https://github.com/zendframework/zend-console)
