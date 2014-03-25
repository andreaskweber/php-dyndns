php-dyndns
======================

A PHP based DynDNS library

### Requirements
Library requires PHP >= 5.4 and the PHP curl extension

### Supported domain providers
- [Dyn](http://dyn.com)
- [SchlundTech](http://www.schlundtech.de) (Access to the [XML-Gateway](http://www.schlundtech.com/services/xml-gateway) required)

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
