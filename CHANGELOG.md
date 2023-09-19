# Change Log for Converter of the Smarty templates to Twig

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [2.0.0] - Unreleased

### Added
- Support for PHP 8
- Missing property type declarations
- Missing method return type declarations
- More fields converted by default by database conversion

### Removed
- Support for `assign_adv` plugin
- Redundant parameters and return types annotations

### Changed
- `oxcontent` is now converted to `include_content` tag

### Fixed
- Use correct addslashes filter [PR-1](https://github.com/OXID-eSales/smarty-to-twig-converter/pull/1)
- Default database config example is using the shop connection now

## [1.0.1] - 2020-05-20

### Changed
- Ensure compatibility with PHP 7.3/7.4

## [1.0.0] - 2019-11-21

[1.0.1]: https://github.com/OXID-eSales/smarty-to-twig-converter/compare/v1.0.0...v1.0.1
