Kaliop eZ-LoremIpsum Bundle
===========================

A bundle for eZPublish5 / eZPlatform dedicated to mass-creation of sample/test content.


## How it works, in 30 lines of yaml

        -
            type: loop
            repeat: 5
            steps:
                -
                    type: content
                    mode: create
                    content_type: folder
                    parent_location: 2
                    attributes:
                        name: "Folder [loop:iteration] - [faker:sentence(3)]"
                    references:
                        -
                            attribute: location_id
                            identifier: parent_folder_loc
                            overwrite: true
                -
                    type: loop
                    repeat: 3
                    steps:
                        -
                            type: content
                            mode: create
                            content_type: folder
                            parent_location: reference:parent_folder_loc
                            attributes:
                                name: "Folder [faker:unixTime]"

As you can probably guess from the above, this code will create a total of 20 contents of type 'Folder', nested in 2
levels, with each top-level folder having 3 children. The names of the top-level folders include 3 random latin words
each.


## Requirements

* eZPublish 2014.11 or later or eZPlatform
* eZMigrationBundle
* Faker from FZaninotto
* TCPDF from tecnick.com

(all of the above are handled automatically using composer)


## Installation

1. get the bundle via composer

    ```
    composer require kaliop/ezloremipsumbundle
    ```

2. activate it in your Kernel's `registerBundles()` method

    ```
    public function registerBundles()
    {
        ...
        new \Kaliop\eZLoremIpsumBundle\EzLoremIpsumBundle()
    ```

## Usage

All this bundle does is to make available to the Kaliop Migration Bundle a new reference resolver, called `faker`.

This means that in order to create massive amounts of contents, you will just need to set up and run a 'migration'.
Migrations are fully documented at: https://github.com/kaliop-uk/ezmigrationbundle/ and
https://github.com/kaliop-uk/ezmigrationbundle/tree/master/Resources/doc/DSL

The 'faker' reference resolver is designed generated random pieces of data. It purposes to  support all of the features
of the Faker library. In no particular order, it can be used to generate:
- phrases
- paragraphs
- names
- addresses
- phone numbers
- dates and times
- emails
- domain names
- numbers
- images and files

The full list of supported data is documented at https://github.com/fzaninotto/Faker

A more detailed description of the supported syntax is given in the [DSL Language description](Resources/doc/DSL/Faker.md)

*NB* there is a known bug when using the `image` provider and there is a problem downloading an image file from the
remote service. The recommended workaround is to use the `picture` provider instead.


## Running tests

The bundle uses PHPUnit to run functional tests.

#### Running tests in a working eZPublish / eZPlatform installation

To run the tests:

    export KERNEL_DIR=app (or 'ezpublish' for ezpublish 5.4/cp setups)
    export SYMFONY_ENV=behat (or whatever your environment is)

    bin/phpunit --stderr -c vendor/kaliop/ezworkflowenginebundle/phpunit.xml.dist

*NB* the tests do *not* mock interaction with the database, but create/modify/delete many types of data in it.
As such, there are good chances that running tests will leave stale/broken data.
It is recommended to run the tests suite using a dedicated eZPublish installation or at least a dedicated database.

#### Setting up a dedicated test environment for the bundle

A safer choice to run the tests of the bundle is to set up a dedicated environment, similar to the one used when the test
suite is run on GitHub Actions.
The advantages are multiple: on one hand you can start with any version of eZPublish you want; on the other you will
be more confident that any tests you add or modify will also pass on GitHub.
The disadvantages are that you will need Docker and Docker-compose, and that the environment you will use will look
quite unlike a standard eZPublish setup! Also, it will take a considerable amount of disk space and time to build.

Steps to set up a dedicated test environment and run the tests in it:

    git clone --depth 1 https://github.com/tanoconsulting/euts.git teststack
    # if you have a github auth token, it is a good idea to copy it now to teststack/docker/data/.composer/auth.json

    # this config sets up a test environment with eZPlatform 2.5 running on php 7.4 / debian bullseye
    export TESTSTACK_CONFIG_FILE=Tests/environment/.euts.2.5.env

    ./teststack/teststack build
    ./teststack/teststack runtests
    ./teststack/teststack.sh stop

You can also run a single test case:

    ./teststack/teststack runtests ./Tests/phpunit/01_CommandsTest.php

Note: this will take some time the 1st time your run it, but it will be quicker on subsequent runs.
Note: make sure to have enough disk space available.

In case you want to run manually commands, such as the symfony console:

    ./teststack/teststack console cache:clear

Or easily get to a database shell prompt:

    ./teststack/teststack dbconsole

Or command-line shell prompt to the Docker container where tests are run:

    ./teststack/teststack shell

The tests in the Docker container run using the version of debian/php/eZPlatform kernel specified in the file
`Tests/environment/.euts.2.5.env`, as specified in env var `TESTSTACK_CONFIG_FILE`.
If no value is set for that environment variable, a file named `.euts.env` is looked for.
If no such file is present, some defaults are used, you can check the documentation in ./teststack/README.md to find out
what they are.
If you want to test against a different version of eZ/php/debian, feel free to:
- create the `.euts.env` file, if it does not exist
- add to it any required var (see file `teststack/.euts.env.example` as guidance)
- rebuild the test stack
- run tests the usual way

You can even keep multiple test stacks available in parallel, by using different env files, eg:
- create a file `.euts.env.local` and add to it any required env var, starting with a unique `COMPOSE_PROJECT_NAME`
- build the new test stack via `./teststack/teststack. -e .euts.env.local build`
- run the tests via: `./teststack/teststack -e .euts.env.local runtests`


## Thanks

Many thanks to Crevillo for the suggestion to look at AliceBundle and to FZaninotto for all the heavy lifting

[![License](https://poser.pugx.org/kaliop/ezloremipsumbundle/license)](https://packagist.org/packages/kaliop/ezloremipsumbundle)
[![Latest Stable Version](https://poser.pugx.org/kaliop/ezloremipsumbundle/v/stable)](https://packagist.org/packages/kaliop/ezloremipsumbundle)
[![Total Downloads](https://poser.pugx.org/kaliop/ezloremipsumbundle/downloads)](https://packagist.org/packages/kaliop/ezloremipsumbundle)

[![Build Status](https://github.com/kaliop-uk/ezloremipsumbundle/actions/workflows/ci.yml/badge.svg)](https://github.com/kaliop-uk/ezloremipsumbundle/actions/workflows/ci.yml)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kaliop-uk/ezloremipsumbundle/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/kaliop-uk/ezloremipsumbundle/?branch=main)
[![Code Coverage](https://codecov.io/gh/kaliop-uk/ezloremipsumbundle/branch/main/graph/badge.svg)](https://codecov.io/gh/kaliop-uk/ezloremipsumbundle)
