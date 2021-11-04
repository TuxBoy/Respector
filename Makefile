.PHONY: composer
composer:
	composer valid

.PHONY: phpstan
phpstan:
	php vendor/bin/phpstan analyse -c phpstan.neon src --no-progress

.PHONY: analyse
analyse:
	make composer
	make phpstan

.PHONY: fix
fix:
	vendor/bin/phpcs
	vendor/bin/phpcbf

.PHONY: tests
tests:
	php vendor/bin/phpunit --testdox --colors
