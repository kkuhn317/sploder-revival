.PHONY: help build dev dev.watch dev.down dev.bootstrap clean

ifeq ($(OS),Windows_NT)
  OPEN_CMD = start
else
  OPEN_CMD = xdg-open
endif

LOCAL_URL = http://127.0.0.1:8010

help:
	@echo "Available commands:"
	@echo "  make build           - build the sploder-revival docker image"
	@echo "  make dev             - executes the docker-compose-dev file with the PostgreSQL boostrap"
	@echo "  make dev.watch       - same as dev, but does not detach the docker container"
	@echo "  make dev.down        - stops the docker container if running"
	@echo "  make dev.bootsrap    - restores the database dump into the PostgreSQL container"
	@echo "  make clean           - cleans docker images and temporary files"
build:
	docker build . -t sploder-revival
dev:
	$(MAKE) dev.down
	@if [ "$(WATCH)" = "true" ]; then \
		(sleep 1; ${OPEN_CMD} ${LOCAL_URL}; ) & docker compose -f docker-compose-dev.yaml up; \
	else \
		docker compose -f docker-compose-dev.yaml up -d && ${OPEN_CMD} ${LOCAL_URL}; \
	fi
dev.watch:
	$(MAKE) dev WATCH=true
dev.down:
	docker compose -f docker-compose-dev.yaml down
dev.bootstrap:
	$(MAKE) dev.down
	docker compose -f docker-compose-dev.yaml up -d
	docker exec -it sploder_postgres /bin/bash -c "pg_restore -U sploder_owner -d sploder --clean --create /docker-entrypoint-initdb.d/backup.bak"
	echo "bootstrap complete, run `make dev` or `make dev.watch` to begin development"
	$(MAKE) dev.down

clean:
	docker container  rm --force sploder-revival
	docker image rm --force sploder-revival
	docker image prune -a -f
	docker container prune -f
	docker volume prune -f
