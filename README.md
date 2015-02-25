[![Build Status](https://travis-ci.org/tholu/php-cidr-match.svg?branch=master)](https://travis-ci.org/tholu/php-cidr-match)

# CIDR match

CIDRmatch is a library to match an IP to an IP range in CIDR notation (IPv4 and IPv6).

## Usage

```
$cidrMatch = new CIDRmatch();
$cidrMatch->match($ip, $cidr);
```

