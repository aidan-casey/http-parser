# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.2.1] - 2018-09-10

### Changed
- Middlware to Middleware in Slim Middleware class to resolve namespace bug.

## [1.2.0] - 2018-09-10

### Added
- Slim Middleware for HTTP query conversion. (AidanCasey\HttpParser\Middleware\Slim)

### Changed
- HttpToZend::ConvertHTTPRequest to HttpToZend::ConvertHttpRequest.

## [1.1.0] - 2018-09-04

### Changed
- composer.json to not include version so they will be read off branches.
- CHANGLOG to include links.

## [1.0.0] - 2018-09-04

### Added
- This CHANGELOG.
- README for description of library and usage examples.
- CONTRIBUTING for contributing guidelines.
- HttpToZend class for conversion of request parameters to database statements.
- UnsupportedRequestMethod for any unsupported request types.

[Unreleased]: https://github.com/aidan-casey/http-parser/compare/v1.2.0...HEAD
[1.2.1]: https://github.com/aidan-casey/http-parser/compare/v1.2.0...v1.2.1
[1.2.0]: https://github.com/aidan-casey/http-parser/compare/v1.1.0...v1.2.0
[1.1.0]: https://github.com/aidan-casey/http-parser/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/aidan-casey/http-parser/releases/tag/v1.0.0