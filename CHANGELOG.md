# Change Log for Converter of the Smarty templates to Twig

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## 2.0.0 [Unreleased]

### Added
- Support for PHP 8
- Missing property type declarations
- Missing method return type declarations
- More fields converted by default by database conversion
- Smarty `sprintf` filter is now mapped to Twig `format` [PR-5](https://github.com/OXID-eSales/smarty-to-twig-converter/pull/5)
- Two-argument Smarty `replace` filter is now converted to Twig hash syntax (`|replace({'search': 'replace'})`) [PR-5](https://github.com/OXID-eSales/smarty-to-twig-converter/pull/5)

### Removed
- Support for `assign_adv` plugin
- Redundant parameters and return types annotations

### Changed
- `oxcontent` is now converted to `include_content` tag
- Readme reference to old Twig documentation
- `composer.json` changes to align better with semantic versioning

### Fixed
- Use correct addslashes filter [PR-1](https://github.com/OXID-eSales/smarty-to-twig-converter/pull/1)
- Default database config example is using the shop connection now
- String literals containing operator characters (`+`, `-`, `*`, `/`, `%`) are no longer corrupted by the operator-spacing step in expression conversion [PR-5](https://github.com/OXID-eSales/smarty-to-twig-converter/pull/5)
- Quote- and bracket-awareness in filter and function argument splitter [PR-5](https://github.com/OXID-eSales/smarty-to-twig-converter/pull/5)
- Leading parentheses are now fully preserved in value sanitization [PR-5](https://github.com/OXID-eSales/smarty-to-twig-converter/pull/5)
- Mixed-content string literals now correctly recognised in filter and attribute extraction [PR-5](https://github.com/OXID-eSales/smarty-to-twig-converter/pull/5)

## [1.0.1] - 2020-05-20

### Changed
- Ensure compatibility with PHP 7.3/7.4

## [1.0.0] - 2019-11-21

[Unreleased]: https://github.com/OXID-eSales/smarty-to-twig-converter/compare/v1.0.1...HEAD
[1.0.1]: https://github.com/OXID-eSales/smarty-to-twig-converter/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/OXID-eSales/smarty-to-twig-converter/tags/v1.0.0
