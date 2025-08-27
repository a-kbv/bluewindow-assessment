# BlueWindow Assessment - Brand Toplist API

A Symfony-based API application for managing brand toplists with geolocation support using Cloudflare headers.

### Requirements
- Docker
- Docker Compose

### Starting the Application

1. **Start all services**
   ```bash
   docker-compose up -d
   ```

1. **Nginx** will start and listen on port 8080
2. **PHP 8.3** container will build with all necessary extensions
3. **PostgreSQL** will initialize with database `bluewindow_db`
4. All services will be networked together automatically

## Commands

```bash
# start services
docker-compose up -d

# stop services
docker-compose down

# view logs
docker-compose logs -f

# execute commands in containers
docker-compose exec php php -v
docker-compose exec postgres psql -U bluewindow -d bluewindow_db

# connect to PostgreSQL
docker-compose exec postgres psql -U bluewindow -d bluewindow_db
```
