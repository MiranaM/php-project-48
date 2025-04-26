.PHONY: test lint

test:
	XDEBUG_MODE=coverage vendor/bin/phpunit tests --coverage-clover build/logs/clover.xml

lint:
	vendor/bin/phpcs --standard=PSR12 src tests

coverage: test
	cat build/logs/clover.xml
