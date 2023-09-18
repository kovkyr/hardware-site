include .env

help:
	@echo ""
	@echo "Usage: make COMMAND"
	@echo ""
	@echo "Commands:"
	@echo "  docker-up               Create containers and database if not exists, then start containers"
	@echo "  docker-halt             Stop containers"
	@echo "  docker-destroy          Destroy containers and database including dump"
	@echo ""
	@echo "  psql-dump               Dump database"
	@echo "  psql-restore            Restore database dump"
	@echo ""
	@echo "  enter-nginx-container   Enter nginx container"
	@echo "  enter-php-container     Enter php container"
	@echo "  enter-psql-container    Enter psql container"
	@echo ""

docker-up:
	@mkdir -p ./data/postgresql-data
	@mkdir -p ./data/dumps
	@docker compose up -d

docker-halt:
	@docker compose down

docker-destroy:
	@docker compose down
	@rm -rf ./data

psql-dump:
	@docker compose exec -t psql pg_dump $(DB_NAME) -c -U $(DB_USER) > data/dumps/db.sql

psql-restore:
	@docker compose exec -T psql psql $(DB_NAME) -U $(DB_USER) < data/dumps/db.sql

shell-nginx:
	@docker compose exec -it nginx /bin/bash

shell-php:
	@docker compose exec -it php /bin/bash

shell-psql:
	@docker compose exec -it psql /bin/bash
