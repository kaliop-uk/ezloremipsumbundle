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

resolving references in provider parameters:

    faker:aProviderWithParameters(resolve('reference.some_reference_id'), resolve('reference.another_reference_id'))

NB: given the limitations of the MigrationBundle reference parser, this will *not* work:

    faker:aProviderWithParameters(['hello', 'world'])

you can use this instead:

    faker:aProviderWithParameters({ 0: 'hello', 1: 'world'})
    
## Providers

The full list of available providers is documented at https://github.com/fzaninotto/Faker
