# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased] - yyyy-mm-dd

### Added

### Changed

### Fixed

## [0.3] - 2017-10-05

### Changed

* [DRACO-3065](http://tickets.turner.com/browse/DRACO-3065) Support Symfony 3
  and Drupal 8.4.

## [0.2] - 2017-08-03

Added invalid XML document in XSD exceptions.

### Added

* [DRACO-2883](http://tickets.turner.com/browse/DRACO-2883): Include generated
  (but invalid) XML in XSD exceptions. The constructor of
  [XsdValidationException](src/Exception/XsdValidationException.php) has been
  changed to add the original \DOMDocument, and a get method added to retrieve
  it later.

## [0.1] - 2017-07-20

Initial release of the Validating XML Encoder.

### Added

* Initial validating XML encoder.
* Complete test coverage.
