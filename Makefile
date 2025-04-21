.PHONY: test lint

test:
	vendor/bin/phpunit tests

lint:
	vendor/bin/phpcs --standard=PSR12 src tests

test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml
