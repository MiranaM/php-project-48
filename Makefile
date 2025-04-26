.PHONY: test lint coverage

test:
	XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-clover build/logs/clover.xml

lint:
	vendor/bin/phpcs --standard=PSR12 src tests

coverage: test
	cat build/logs/clover.xml
