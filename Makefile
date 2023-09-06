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
	@docker compose exec -t psql pg_dumpall -c -U postgres > data/dumps/db.sql

psql-restore:
	@cat data/dumps/db.sql | docker compose exec -T psql psql -U postgres

enter-nginx-container:
	@docker compose exec -it nginx /bin/sh

enter-php-container:
	@docker compose exec -it php /bin/sh

enter-psql-container:
	@docker compose exec -it psql /bin/sh
