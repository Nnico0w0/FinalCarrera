#!/bin/bash

# Exit on error, undefined variables, and pipe failures
set -euo pipefail

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored messages
print_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_header() {
    echo -e "\n${BLUE}================================================${NC}"
    echo -e "${BLUE}  $1${NC}"
    echo -e "${BLUE}================================================${NC}\n"
}

# Check if Docker is installed
check_docker() {
    print_info "Checking Docker installation..."
    if ! command -v docker &> /dev/null; then
        print_error "Docker is not installed. Please install Docker first."
        echo "Visit: https://docs.docker.com/get-docker/"
        exit 1
    fi
    print_success "Docker is installed"
}

# Check if Docker Compose is installed
check_docker_compose() {
    print_info "Checking Docker Compose installation..."
    if ! command -v docker-compose &> /dev/null && ! docker compose version &> /dev/null; then
        print_error "Docker Compose is not installed. Please install Docker Compose first."
        echo "Visit: https://docs.docker.com/compose/install/"
        exit 1
    fi
    print_success "Docker Compose is installed"
}

# Check if Docker daemon is running
check_docker_running() {
    print_info "Checking if Docker daemon is running..."
    if ! docker info &> /dev/null; then
        print_error "Docker daemon is not running. Please start Docker first."
        exit 1
    fi
    print_success "Docker daemon is running"
}

# Copy .env.example to .env if it doesn't exist
setup_env_file() {
    print_info "Setting up environment file..."
    if [ ! -f .env ]; then
        cp .env.example .env
        print_success "Created .env file from .env.example"
        print_warning "Please update the .env file with your Stripe API keys if needed"
    else
        print_warning ".env file already exists, skipping..."
    fi
}

# Stop and remove existing containers (cleanup)
cleanup_containers() {
    print_info "Cleaning up existing containers..."
    print_warning "This will remove existing containers and volumes (database data will be lost)"
    docker-compose down -v 2>/dev/null || true
    print_success "Cleanup completed"
}

# Build Docker images
build_images() {
    print_info "Building Docker images (this may take a few minutes)..."
    if docker-compose build; then
        print_success "Docker images built successfully"
    else
        print_error "Failed to build Docker images"
        exit 1
    fi
}

# Start Docker containers
start_containers() {
    print_info "Starting Docker containers..."
    if docker-compose up -d; then
        print_success "Docker containers started successfully"
    else
        print_error "Failed to start Docker containers"
        exit 1
    fi
}

# Wait for containers to be ready
wait_for_containers() {
    print_info "Waiting for containers to be ready (this may take up to 2 minutes)..."
    
    print_info "Checking backend container health..."
    local retries=0
    local max_retries=40
    
    while [ $retries -lt $max_retries ]; do
        if docker-compose exec -T backend php artisan --version &> /dev/null; then
            echo "" # New line after dots
            print_success "Backend container is ready"
            return 0
        fi
        retries=$((retries + 1))
        # Show progress dot every 3 retries (6 seconds)
        if [ $((retries % 3)) -eq 0 ]; then
            echo -n "."
        fi
        sleep 2
    done
    
    echo "" # New line after dots
    print_error "Backend container did not become ready in time"
    print_info "You can check logs with: docker-compose logs backend"
    exit 1
}

# Run database migrations
run_migrations() {
    print_info "Running database migrations..."
    if docker-compose exec -T backend php artisan migrate --force; then
        print_success "Database migrations completed"
    else
        print_error "Failed to run database migrations"
        exit 1
    fi
}

# Seed the database
seed_database() {
    print_info "Seeding database with initial data..."
    if docker-compose exec -T backend php artisan db:seed --force; then
        print_success "Database seeded successfully"
    else
        print_warning "Failed to seed database (this might be normal if seeders don't exist)"
    fi
    
    print_info "Seeding admin user..."
    if docker-compose exec -T backend php artisan db:seed --class=AdminSeeder --force; then
        print_success "Admin user seeded successfully"
    else
        print_warning "Failed to seed admin user"
    fi
}

# Display access information
display_info() {
    print_header "Setup Complete!"
    
    echo -e "${GREEN}Your application is ready!${NC}\n"
    echo -e "Access URLs:"
    echo -e "  ${BLUE}Frontend:${NC}  http://localhost:5173"
    echo -e "  ${BLUE}Backend:${NC}   http://localhost:8000"
    echo -e "  ${BLUE}Admin:${NC}     http://localhost:8000/admin/login"
    echo -e "  ${BLUE}API:${NC}       http://localhost:8000/api/v1/"
    
    echo -e "\nUseful commands:"
    echo -e "  ${YELLOW}make help${NC}           - Show all available make commands"
    echo -e "  ${YELLOW}make logs${NC}           - View container logs"
    echo -e "  ${YELLOW}make down${NC}           - Stop containers"
    echo -e "  ${YELLOW}make up${NC}             - Start containers"
    echo -e "  ${YELLOW}docker-compose ps${NC}   - Check container status"
    
    echo -e "\n${YELLOW}Note:${NC} Remember to configure your Stripe API keys in .env file for payment functionality\n"
}

# Show help message
show_help() {
    echo "FinalCarrera Project Setup Script"
    echo ""
    echo "Usage: ./setup.sh [OPTIONS]"
    echo ""
    echo "This script automates the initial setup of the FinalCarrera project."
    echo ""
    echo "Options:"
    echo "  -h, --help     Show this help message"
    echo ""
    echo "What this script does:"
    echo "  1. Checks for Docker and Docker Compose"
    echo "  2. Creates .env file from .env.example"
    echo "  3. Builds and starts Docker containers"
    echo "  4. Runs database migrations"
    echo "  5. Seeds the database with initial data"
    echo "  6. Displays access URLs and useful commands"
    echo ""
    echo "Requirements:"
    echo "  - Docker"
    echo "  - Docker Compose"
    echo "  - Docker daemon running"
    echo ""
}

# Main setup process
main() {
    # Check for help flag
    if [[ "${1:-}" == "-h" ]] || [[ "${1:-}" == "--help" ]]; then
        show_help
        exit 0
    fi
    
    print_header "FinalCarrera Project Setup"
    
    # Pre-flight checks
    check_docker
    check_docker_compose
    check_docker_running
    
    # Setup process
    setup_env_file
    cleanup_containers
    build_images
    start_containers
    wait_for_containers
    run_migrations
    seed_database
    
    # Display final information
    display_info
}

# Run main function with all arguments
main "$@"
