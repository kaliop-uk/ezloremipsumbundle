Version 1.3
===========

* New: the bundle now includes a custom Faker provider that registers the properties `noun`, `adjective` and `animal`.
    Those are not yet multilingual and will return only english words.

* New: the bundle now includes a custom Faker provider that registers the `pdfText` property. It can be used to generate
    pdf files which contain random rich text. 

* Minor tweaks to the xmlText generator


Version 1.2
===========

* New: the bundle now includes a custom Faker provider that registers the `randomXmlText($maxDepth=4, $maxWidth=4)` property.
    It works as the original `randomHtml` provider, except that it generates rich text compatible with the XmlText
    field type.


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

* New: it is now possible register custom Faker providers via the parameter `ez_loremipsum_bundle.faker.providers`

* New: the bundle now includes a custom Faker provider that registers the `picture` and `pictureUrl` properties.
    Those behave almost exactly like the native `image` and `imageUrl` ones, except for not supporting the 'category'.
    They retrieve the images from service picsum.photos instead of lorempixel. 
