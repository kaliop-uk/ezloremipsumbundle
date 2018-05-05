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
 

## Requirements

* eZPublish 2014.11 or later or eZPlatform
* eZMigrationBundle
* Faker from FZaninotto

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

## Thanks

Many thanks to Crevillo for the suggestion to look at AliceBundle and to FZaninotto for all the heavy lifting
