Version 1.1
===========

* New: it is now possible to use modifiers in the `faker` references, eg:

        faker:unique().aProvider
        faker:optional().aProvider
        faker:valid().aProvider

* New: it is now possible to use complex expressions in the parameters passed to the `faker` provider, eg:

        -
            type: loop
            repeat: 2
            steps:
                -
                    type: tag
                    mode: create
                    parent_tag_id: 2
                    lang: eng-GB
                    keywords:
                        eng-GB: "[faker: randomelement({0:"hello", 1:"world"})]"

        -
            type: reference
            mode: set
            identifier: aaa
            value: 'hello world'
        -
            type: loop
            repeat: 2
            steps:
                -
                    type: tag
                    mode: create
                    parent_tag_id: 2
                    lang: eng-GB
                    keywords:
                        eng-GB: "[faker: shuffle(resolve('reference:aaa'))]"
