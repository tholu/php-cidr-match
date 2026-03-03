PHPUNIT := vendor/bin/phpunit
PHPUNIT_CONFIG := tests/phpunit.xml

.PHONY: help test

help:
	@echo "Available targets:"
	@echo "  make test    Run the PHPUnit test suite"

test:
	$(PHPUNIT) -c $(PHPUNIT_CONFIG)
