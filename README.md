Money bundle
============

[![Code Quality](https://github.com/Ang3/money-bundle/actions/workflows/php_lint.yml/badge.svg)](https://github.com/Ang3/money-bundle/actions/workflows/php_lint.yml)
[![PHPUnit Tests](https://github.com/Ang3/money-bundle/actions/workflows/phpunit.yml/badge.svg)](https://github.com/Ang3/money-bundle/actions/workflows/phpunit.yml)
[![Symfony Bundle](https://github.com/Ang3/money-bundle/actions/workflows/symfony_bundle.yml/badge.svg)](https://github.com/Ang3/money-bundle/actions/workflows/symfony_bundle.yml)
[![Latest Stable Version](https://poser.pugx.org/ang3/money-bundle/v/stable)](https://packagist.org/packages/ang3/money-bundle)
[![Latest Unstable Version](https://poser.pugx.org/ang3/money-bundle/v/unstable)](https://packagist.org/packages/ang3/money-bundle)
[![Total Downloads](https://poser.pugx.org/ang3/money-bundle/downloads)](https://packagist.org/packages/ang3/money-bundle)

This bundle integrates the package [brick/money](https://github.com/brick/money) into your Symfony apps. 
It provides Doctrine types, form types and others features to manage money and currency inside your project.

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your app directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require ang3/money-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Configure the bundle
----------------------------

Create the file `config/packages/ang3_money.yaml`, and add the contents below:

```yaml
# config/packages/ang3_money.yaml
ang3_money: ~
```

Usage
=====

...

That's it!