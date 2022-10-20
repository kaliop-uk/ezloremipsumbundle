This bundle provides a reference resolver that is powered by the Faker library.

You can use it to dynamically generate random text, email addresses, dates and much more.


## Syntax

It supports the following syntax:

simple case:

    faker:someDataProvider

passing parameters:

    faker:aProviderWithParameters('a', "b", 123, true, {foo: 'bar'})

using modifiers:

    faker:unique().aProvider

    faker:optional().aProvider

    faker:valid().aProvider

    faker:maxDistinct().aProvider

resolving references in provider parameters:

    faker:aProviderWithParameters(resolve('reference.some_reference_id'), resolve('reference.another_reference_id'))

NB: given the limitations of the MigrationBundle reference parser, this will *not* work:

    faker:aProviderWithParameters(['hello', 'world'])

you can use this instead:

    faker:aProviderWithParameters({ 0: 'hello', 1: 'world'})


## Providers

* The full list of default providers is documented at https://github.com/fzaninotto/Faker

* Coming with the bundle are providers that register:

    * the `picture` and `pictureUrl` properties.
        Those behave almost exactly like the native `image` and `imageUrl` ones, except for not supporting the 'category'.
        They retrieve the images from service picsum.photos instead of lorempixel.

    * the `randomXmlText($maxDepth=4, $maxWidth=4)` property.
        It works as the original `randomHtml` property, except that it generates rich text compatible with the XmlText
        field type.

    * the `pdfFile($dir = '/tmp', $pages=5, $title = '', $author = '', $subject = '', $keywords = '')` property.
        It can be used to generate PDF files with random contents, and will return the name of the generated file

    * the `maxDistinct($maxElements = 100, $reset = false)` modifier.
        This can be used when you want to limit a generated property to have a limited set of distinct values.
        It can be f.e. useful when you generate images or files by querying remote services which impose limits on the
        number of calls that can be placed.
        Eg: by using `maxDistinct(500).picture`, your code would only be retrieving 500 different pictures.
        Please note that this modifier does not insure uniqueness of the results, but it can be combined with `unique()`.

* It is also possible to register custom providers from your own code.
