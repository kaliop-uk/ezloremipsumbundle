ezloreimpsumbundle
======================

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
    composer require kaliop/ezloreipsumbundle
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

## Thanks

Many thanks to Crevillo for the suggestion to look at AliceBundle and to FZaninotto for all the heavy lifting
