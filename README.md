php-dyndns
======================

A PHP based DynDNS library

### Supported domain providers
- [Dyn](http://dyn.com)
- [SchlundTech](http://www.schlundtech.de) (Access to the [XML-Gateway](http://www.schlundtech.com/services/xml-gateway) required)

### Requirements
Library requires PHP >= 5.4 and the PHP curl extension

### Installation

Clone repository
```
git clone https://github.com/andreas-weber/php-dyndns.git /path/to/your/folder
```
Install dependencies
```
cd /path/to/your/folder
composer update
```
Run tests
```
phpunit
```

### Roadmap
- Implement Strato provider
- Add provider tests

### Attributions
- Thanks to [Martin Lowinski](https://github.com/martinlowinski) for doing the hard work implementing a functional version of the [Schlundtech XML-Gateway](http://www.schlundtech.com/services/xml-gateway) in his [repository](https://github.com/martinlowinski/php-dyndns), which was the base and inspired me developing this library.
