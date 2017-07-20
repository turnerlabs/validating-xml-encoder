[![CircleCI](https://circleci.com/gh/turnerlabs/validating-xml-encoder.svg?style=svg)](https://circleci.com/gh/turnerlabs/validating-xml-encoder)

This is a XML encoder that validates the result against an XSD. Works well with
the [Symfony Serialization Component](https://symfony.com/doc/current/components/serializer.html)
and the [Drupal Serializer](https://www.drupal.org/docs/8/api/serialization-api/serialization-api-overview).

```php
<?php

$encoder = new \TurnerLabs\ValidatingXmlEncoder\Encoder('rootNodeName', $pathToXsd));
// If the encoded result of $data is not valid according to the XSD,
// \TurnerLabs\ValidatingXmlEncoder\Exception\XsdValidationException is thrown.
$xml = $encoder->encode($data);
```
