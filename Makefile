.PHONY: help build build.prod install.composer dev dev.watch dev.down dev.bootstrap dev.bash.site dev.bash.db dev.backup.db dev.hook prod prod.down prod.bootstrap prod.bash.site prod.bash.db prod.backup.db prod.logs clean clean.prod test

ifeq ($(OS),Windows_NT)
  OPEN_CMD = start
else
  OPEN_CMD = xdg-open
endif

# Environment-specific variables
DEV_URL = http://127.0.0.1:8010
PROD_URL = http://127.0.0.1:8020
DEV_COMPOSE = docker-compose-dev.yaml
PROD_COMPOSE = docker-compose-prod.yaml
DEV_SITE_CONTAINER = sploder_revival
PROD_SITE_CONTAINER = sploder_revival_prod
DEV_DB_CONTAINER = sploder_postgres
PROD_DB_CONTAINER = sploder_postgres_prod
CONTAINER_CMD = docker

# Helper functions
define compose_up
	${CONTAINER_CMD} compose -f $(1) up $(if $(2),$(2),-d)
endef

define compose_down
	${CONTAINER_CMD} compose -f $(1) down
endef

define exec_container
	${CONTAINER_CMD} exec -it $(1) $(2)
endef

define wait_for_db
	RETRY_LIMIT=30; \
	RETRY_COUNT=0; \
	until ${CONTAINER_CMD} exec $(1) pg_isready -U postgres || [ $$RETRY_COUNT -ge $$RETRY_LIMIT ]; do \
		echo "Waiting for PostgreSQL to be ready..."; \
		RETRY_COUNT=$$((RETRY_COUNT+1)); \
		sleep 1; \
	done; \
	if [ $$RETRY_COUNT -ge $$RETRY_LIMIT ]; then \
		echo "PostgreSQL did not become ready within the timeout period."; \
		exit 1; \
	fi
endef

define backup_db
	$(call compose_up,$(1))
	@if [ "$(3)" = "schema" ]; then \
		echo "Creating schema-only backup..."; \
		$(call exec_container,$(2),/bin/bash -c "pg_dump -U sploder -d sploder --format=p --schema-only --create > /bootstrap/sploder.sql"); \
	else \
		echo "Creating full backup..."; \
		$(call exec_container,$(2),/bin/bash -c "pg_dump -U sploder -d sploder --format=p --create > /bootstrap/sploder-backup-$$(date +%Y%m%d_%H%M%S).sql"); \
	fi
	$(call compose_down,$(1))
endef

define build_image
	composer install
	${CONTAINER_CMD} build . -t $(1)
endef

define clean_env
	${CONTAINER_CMD} container rm --force $(1) $(2) 2>/dev/null || true
	${CONTAINER_CMD} image rm --force $(3) 2>/dev/null || true
	${CONTAINER_CMD} image prune -a -f
	${CONTAINER_CMD} container prune -f
	${CONTAINER_CMD} volume prune -f
endef

define bootstrap_env
	@echo "---BOOTSTRAP START---";
	$(call compose_down,$(1))
	$(call compose_up,$(1))
	$(call wait_for_db,$(2))
	$(call exec_container,$(2),/bin/bash -c "chmod +x /bootstrap/bootstrap.sh && /bootstrap/bootstrap.sh $(3)")
	@echo "---BOOTSTRAP COMPLETE---";
	$(call compose_down,$(1))
endef

help:
	@echo "Available commands:"
	@echo ""
	@echo "Build commands:"
	@echo "  make build            - build the sploder-revival docker image for development"
	@echo "  make build.prod       - build the sploder-revival docker image for production"
	@echo "  make install.composer - installs composer for php"
	@echo ""
	@echo "Development commands:"
	@echo "  make dev              - executes the docker-compose-dev file with the PostgreSQL boostrap"
	@echo "  make dev.hook         - install the pre-commit hook for formatting"
	@echo "  make dev.watch        - same as dev, but does not detach the docker container"
	@echo "  make dev.down         - stops the docker container if running"
	@echo "  make dev.bootsrap     - restores the database dump into the PostgreSQL container"
	@echo "  make dev.bash.site    - enter the sploder revival container"
	@echo "  make dev.bash.db      - enter the db container"
	@echo "  make dev.backup.db    - creates a schema backup of the database into the mounted folder"
	@echo ""
	@echo "Production commands:"
	@echo "  make prod             - start production environment"
	@echo "  make prod.down        - stop production environment"
	@echo "  make prod.bootstrap   - restores the database dump into the production PostgreSQL container"
	@echo "  make prod.bash.site   - enter the production sploder revival container"
	@echo "  make prod.bash.db     - enter the production db container"
	@echo "  make prod.backup.db   - creates a full backup of the production database"
	@echo "  make prod.logs        - view production container logs"
	@echo ""
	@echo "Cleanup commands:"
	@echo "  make clean            - cleans development docker images and temporary files"
	@echo "  make clean.prod       - cleans production docker images and temporary files"
	@echo ""
	@echo "Utility commands:"
	@echo "  make test             - runs the unit tests for the project"
build:
	$(call build_image,sploder-revival)
install.composer:
	php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
	php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }"
	php composer-setup.php
	php -r "unlink('composer-setup.php');"
dev:
	$(call compose_down,${DEV_COMPOSE})
	@if [ "$(WATCH)" = "true" ]; then \
		(sleep 2; ${OPEN_CMD} ${DEV_URL}; ) & $(call compose_up,${DEV_COMPOSE},); \
	else \
		$(call compose_up,${DEV_COMPOSE}) && ${OPEN_CMD} ${DEV_URL}; \
	fi
dev.hook:
	cp .hooks/pre-commit .git/hooks/pre-commit
	chmod u+x .git/hooks/pre-commit
dev.watch:
	$(MAKE) dev WATCH=true
dev.down:
	$(call compose_down,${DEV_COMPOSE})
dev.bootstrap:
	$(call bootstrap_env,${DEV_COMPOSE},${DEV_DB_CONTAINER},dev)
dev.bash.site:
	$(call exec_container,${DEV_SITE_CONTAINER},/bin/bash)
dev.bash.db:
	$(call exec_container,${DEV_DB_CONTAINER},/bin/bash)
dev.backup.db:
	$(call compose_down,${DEV_COMPOSE})
	$(call backup_db,${DEV_COMPOSE},${DEV_DB_CONTAINER},schema)

# Production commands
prod:
	$(call compose_down,${PROD_COMPOSE})
	$(call compose_up,${PROD_COMPOSE}) && ${OPEN_CMD} ${PROD_URL}
prod.down:
	$(call compose_down,${PROD_COMPOSE})
prod.bootstrap:
	$(call bootstrap_env,${PROD_COMPOSE},${PROD_DB_CONTAINER},prod)
prod.bash.site:
	$(call exec_container,${PROD_SITE_CONTAINER},/bin/bash)
prod.bash.db:
	$(call exec_container,${PROD_DB_CONTAINER},/bin/bash)
prod.backup.db:
	$(call compose_down,${PROD_COMPOSE})
	$(call backup_db,${PROD_COMPOSE},${PROD_DB_CONTAINER},full)
prod.logs:
	${CONTAINER_CMD} compose -f ${PROD_COMPOSE} logs -f
clean:
	$(call clean_env,${DEV_SITE_CONTAINER},${DEV_DB_CONTAINER},sploder-revival)
clean.prod:
	$(call clean_env,${PROD_SITE_CONTAINER},${PROD_DB_CONTAINER},sploder-revival)
test:
	./vendor/bin/phpunit
