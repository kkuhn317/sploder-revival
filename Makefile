.PHONY: help build dev dev.watch dev.down dev.bootstrap dev.bash.site dev.bash.db dev.backup.db dev.hook clean test

ifeq ($(OS),Windows_NT)
  OPEN_CMD = start
else
  OPEN_CMD = xdg-open
endif

LOCAL_URL = http://127.0.0.1:8010
CONTAINER_CMD = docker

help:
	@echo "Available commands:"
	@echo "  make build           - build the sploder-revival docker image"
	@echo "  make dev             - executes the docker-compose-dev file with the PostgreSQL boostrap"
	@echo "  make dev.hook        - install the pre-commit hook for formatting"
	@echo "  make dev.watch       - same as dev, but does not detach the docker container"
	@echo "  make dev.down        - stops the docker container if running"
	@echo "  make dev.bootsrap    - restores the database dump into the PostgreSQL container"
	@echo "  make dev.bash.site   - enter the sploder revival container"
	@echo "  make dev.bash.db     - enter the db container"
	@echo "  make dev.backup.db   - creates a schema backup of the database into the mounted folder"
	@echo "  make clean           - cleans docker images and temporary files"
	@echo "  make test            - runs the unit tests for the project"
build:
	composer install
	${CONTAINER_CMD} build . -t sploder-revival
dev:
	$(MAKE) dev.down
	@if [ "$(WATCH)" = "true" ]; then \
		(sleep 2; ${OPEN_CMD} ${LOCAL_URL}; ) & ${CONTAINER_CMD} compose -f docker-compose-dev.yaml up; \
	else \
		${CONTAINER_CMD} compose -f docker-compose-dev.yaml up -d && ${OPEN_CMD} ${LOCAL_URL}; \
	fi
dev.hook:
	cp .hooks/pre-commit .git/hooks/pre-commit
	chmod u+x .git/hooks/pre-commit
dev.watch:
	$(MAKE) dev WATCH=true
dev.down:
	${CONTAINER_CMD} compose -f docker-compose-dev.yaml down
dev.bootstrap:
	@echo "---BOOTSTRAP START---";
	$(MAKE) dev.down
	${CONTAINER_CMD} compose -f docker-compose-dev.yaml up -d
	until docker exec sploder_postgres pg_isready -U postgres; do \
		echo "Waiting for PostgreSQL to be ready..."; \
		sleep 1; \
	done
	${CONTAINER_CMD} exec -it sploder_postgres /bin/bash -c "chmod +x /bootstrap/bootstrap.sh && /bootstrap/bootstrap.sh"
	@echo "---BOOTSTRAP COMPLETE---";
	$(MAKE) dev.down
dev.bash.site:
	${CONTAINER_CMD} exec -it sploder_revival /bin/bash
dev.bash.db:
	${CONTAINER_CMD} exec -it sploder_postgres /bin/bash
dev.backup.db:
	$(MAKE) dev.down
	${CONTAINER_CMD} compose -f docker-compose-dev.yaml up -d
	${CONTAINER_CMD} exec -it sploder_postgres /bin/bash -c "pg_dump -U sploder -d sploder --format=p --schema-only --create > /bootstrap/sploder.sql"
	$(MAKE) dev.down
clean:
	${CONTAINER_CMD} container  rm --force sploder-revival
	${CONTAINER_CMD} image rm --force sploder-revival
	${CONTAINER_CMD} image prune -a -f
	${CONTAINER_CMD} container prune -f
	${CONTAINER_CMD} volume prune -f
test:
	./vendor/bin/phpunit
