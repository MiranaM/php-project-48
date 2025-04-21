.PHONY: test lint

test:
	vendor/bin/phpunit tests --coverage-clover build/logs/clover.xml

lint:
	vendor/bin/phpcs --standard=PSR12 src tests
