.PHONY: laradock-init-env laradock-up laradock-down ws-ssh

ws-ssh:
	(cd laradock && docker-compose exec --user=laradock workspace bash)

code-analyze:
	(cd laradock && docker-compose exec --user=laradock workspace ./vendor/bin/phpstan analyse)

test:
	(cd laradock && docker-compose exec --user=laradock workspace composer test)

laradock-init-env:
	(cd laradock && cp env-example .env && echo "\nDB_HOST=mysql" >> .env)

laradock-up:
	(cd laradock && docker-compose up -d nginx mysql)

laradock-down:
	(cd laradock && docker-compose down)

