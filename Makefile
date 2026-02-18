.PHONY: help up down restart build logs clean install migrate seed

# Colors for terminal output
BLUE := \033[0;34m
GREEN := \033[0;32m
RESET := \033[0m

help: ## Show this help message
	@echo '${BLUE}Available commands:${RESET}'
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  ${GREEN}%-15s${RESET} %s\n", $$1, $$2}'

up: ## Start all containers
	@echo '${BLUE}Starting containers...${RESET}'
	docker-compose up -d
	@echo '${GREEN}Containers started!${RESET}'
	@echo 'Frontend: http://localhost:5173'
	@echo 'Backend: http://localhost:8000'

up-dev: ## Start all containers (legacy dev alias)
	@echo '${BLUE}Starting containers (development defaults)...${RESET}'
	docker-compose up -d
	@echo '${GREEN}Containers started!${RESET}'
	@echo 'Frontend: http://localhost:5173'
	@echo 'Backend: http://localhost:8000'

down: ## Stop all containers
	@echo '${BLUE}Stopping containers...${RESET}'
	docker-compose down
	@echo '${GREEN}Containers stopped!${RESET}'

down-dev: ## Stop containers (legacy dev alias)
	@echo '${BLUE}Stopping containers (development defaults)...${RESET}'
	docker-compose down
	@echo '${GREEN}Containers stopped!${RESET}'

restart: ## Restart all containers
	@echo '${BLUE}Restarting containers...${RESET}'
	docker-compose restart
	@echo '${GREEN}Containers restarted!${RESET}'

build: ## Build/rebuild containers
	@echo '${BLUE}Building containers...${RESET}'
	docker-compose build --no-cache
	@echo '${GREEN}Build complete!${RESET}'

logs: ## Show logs from all containers
	docker-compose logs -f

logs-backend: ## Show backend logs
	docker-compose logs -f backend

logs-frontend: ## Show frontend logs
	docker-compose logs -f frontend

logs-db: ## Show database logs
	docker-compose logs -f db

clean: ## Stop containers and remove volumes
	@echo '${BLUE}Cleaning up...${RESET}'
	docker-compose down -v
	@echo '${GREEN}Cleanup complete!${RESET}'

install: ## Install dependencies in containers
	@echo '${BLUE}Installing backend dependencies...${RESET}'
	docker-compose exec backend composer install
	@echo '${BLUE}Installing frontend dependencies...${RESET}'
	docker-compose exec frontend npm install
	@echo '${GREEN}Dependencies installed!${RESET}'

migrate: ## Run database migrations
	@echo '${BLUE}Running migrations...${RESET}'
	docker-compose exec backend php artisan migrate
	@echo '${GREEN}Migrations complete!${RESET}'

migrate-fresh: ## Fresh migrations (drops all tables)
	@echo '${BLUE}Running fresh migrations...${RESET}'
	docker-compose exec backend php artisan migrate:fresh
	@echo '${GREEN}Fresh migrations complete!${RESET}'

seed: ## Seed the database
	@echo '${BLUE}Seeding database...${RESET}'
	docker-compose exec backend php artisan db:seed
	@echo '${GREEN}Seeding complete!${RESET}'

seed-admin: ## Seed admin user
	@echo '${BLUE}Seeding admin user...${RESET}'
	docker-compose exec backend php artisan db:seed --class=AdminSeeder
	@echo '${GREEN}Admin seeder complete!${RESET}'

fresh: ## Fresh migrations with seed
	@echo '${BLUE}Running fresh migrations with seed...${RESET}'
	docker-compose exec backend php artisan migrate:fresh --seed
	@echo '${GREEN}Fresh setup complete!${RESET}'

shell-backend: ## Open shell in backend container
	docker-compose exec backend sh

shell-frontend: ## Open shell in frontend container
	docker-compose exec frontend sh

shell-db: ## Open PostgreSQL shell
	docker-compose exec db psql -U $${DB_USERNAME:-postgres} -d $${DB_DATABASE:-finalcarrera}

cache-clear: ## Clear Laravel cache
	@echo '${BLUE}Clearing cache...${RESET}'
	docker-compose exec backend php artisan cache:clear
	docker-compose exec backend php artisan config:clear
	docker-compose exec backend php artisan route:clear
	docker-compose exec backend php artisan view:clear
	@echo '${GREEN}Cache cleared!${RESET}'

ps: ## Show running containers
	docker-compose ps

init: ## Initial setup (build, start, migrate, seed)
	@echo '${BLUE}Running initial setup...${RESET}'
	@make build
	@make up
	@echo '${BLUE}Waiting for containers to be ready...${RESET}'
	@sleep 10
	@make seed-admin
	@echo '${GREEN}Initial setup complete!${RESET}'
	@echo 'Frontend: http://localhost:5173'
	@echo 'Backend: http://localhost:8000'
	@echo 'Admin: http://localhost:8000/admin/login'
