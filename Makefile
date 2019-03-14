.PHONY: ws-ssh

laradock-init-env:
	(cd laradock && \
		cp env-example .env && \
		echo "\nDB_HOST=mysql" >> .env)

ws-ssh:
	(cd laradock && \
	 docker-compose exec --user=laradock workspace bash)