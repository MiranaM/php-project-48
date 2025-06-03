.PHONY: test lint

test:
	mkdir -p build
	vendor/bin/phpunit --coverage-clover build/coverage.xml


lint:
	vendor/bin/phpcs --standard=PSR12 src tests

coverage: test
	cat build/logs/clover.xml
