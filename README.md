[![CI](https://github.com/tholu/php-cidr-match/actions/workflows/ci.yml/badge.svg)](https://github.com/tholu/php-cidr-match/actions/workflows/ci.yml)

# CIDR match

CIDRmatch is a library to match an IP to an IP range in CIDR notation (IPv4 and IPv6).

**NOTE:** Symfony2 already does everything this library here does with its IpUtils module.
Unfortunately I discovered this only after I finished working on this library.

## Usage

```
$cidrMatch = new CIDRmatch();
$cidrMatch->match($ip, $cidr);
```


## Tests

```
./vendor/bin/phpunit tests/CIDRmatchTest
```
