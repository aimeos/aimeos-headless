#!/usr/bin/env bash

if ! [ -x "$(command -v docker-compose)" ]; then
    shopt -s expand_aliases
    alias docker-compose='docker compose'
fi

UNAMEOUT="$(uname -s)"

WHITE='\033[1;37m'
NC='\033[0m'

# Verify operating system is supported...
case "${UNAMEOUT}" in
    Linux*)             MACHINE=linux;;
    Darwin*)            MACHINE=mac;;
    *)                  MACHINE="UNKNOWN"
esac

if [ "$MACHINE" == "UNKNOWN" ]; then
    echo "Unsupported operating system [$(uname -s)]. Laravel Sail supports macOS, Linux, and Windows (WSL2)." >&2

    exit 1
fi

# Source the ".env" file so Laravel's environment variables are available...
if [ -f ./.env ]; then
    source ./.env
fi

# Define environment variables...
export APP_PORT=${APP_PORT:-80}
export APP_SERVICE=${APP_SERVICE:-"laravel.test"}
export DB_PORT=${DB_PORT:-3306}
export WWWUSER=${WWWUSER:-$UID}
export WWWGROUP=${WWWGROUP:-$(id -g)}

export SAIL_SHARE_DASHBOARD=${SAIL_SHARE_DASHBOARD:-4040}
export SAIL_SHARE_SERVER_HOST=${SAIL_SHARE_SERVER_HOST:-"laravel-sail.site"}
export SAIL_SHARE_SERVER_PORT=${SAIL_SHARE_SERVER_PORT:-8080}
export SAIL_SHARE_SUBDOMAIN=${SAIL_SHARE_SUBDOMAIN:-""}

# Function that outputs Sail is not running...
function sail_is_not_running {
    echo -e "${WHITE}Sail is not running.${NC}" >&2
    echo "" >&2
    echo -e "${WHITE}You may Sail using the following commands:${NC} './vendor/bin/sail up' or './vendor/bin/sail up -d'" >&2

    exit 1
}

if [ -z "$SAIL_SKIP_CHECKS" ]; then
    # Ensure that Docker is running...
    if ! docker info > /dev/null 2>&1; then
        echo -e "${WHITE}Docker is not running.${NC}" >&2

        exit 1
    fi

    # Determine if Sail is currently up...
    PSRESULT="$(docker-compose ps -q)"
    if docker-compose ps | grep $APP_SERVICE | grep 'Exit'; then
        echo -e "${WHITE}Shutting down old Sail processes...${NC}" >&2

        docker-compose down > /dev/null 2>&1

        EXEC="no"
    elif [ -n "$PSRESULT" ]; then
        EXEC="yes"
    else
        EXEC="no"
    fi
else
    EXEC="yes"
fi

if [ $# -gt 0 ]; then
    # Proxy PHP commands to the "php" binary on the application container...
    if [ "$1" == "php" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            docker-compose exec \
                -u sail \
                "$APP_SERVICE" \
                php "$@"
        else
            sail_is_not_running
        fi

    # Proxy vendor binary commands on the application container...
    elif [ "$1" == "bin" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            docker-compose exec \
                -u sail \
                "$APP_SERVICE" \
                ./vendor/bin/"$@"
        else
            sail_is_not_running
        fi

    # Proxy Composer commands to the "composer" binary on the application container...
    elif [ "$1" == "composer" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            docker-compose exec \
                -u sail \
                "$APP_SERVICE" \
                composer "$@"
        else
            sail_is_not_running
        fi

    # Proxy Artisan commands to the "artisan" binary on the application container...
    elif [ "$1" == "artisan" ] || [ "$1" == "art" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            docker-compose exec \
                -u sail \
                "$APP_SERVICE" \
                php artisan "$@"
        else
            sail_is_not_running
        fi

    # Proxy the "debug" command to the "php artisan" binary on the application container with xdebug enabled...
    elif [ "$1" == "debug" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            docker-compose exec \
                -u sail \
                -e XDEBUG_SESSION=1 \
                "$APP_SERVICE" \
                php artisan "$@"
        else
            sail_is_not_running
        fi

    # Proxy the "test" command to the "php artisan test" Artisan command...
    elif [ "$1" == "test" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            docker-compose exec \
                -u sail \
                "$APP_SERVICE" \
                php artisan test "$@"
        else
            sail_is_not_running
        fi

    # Proxy the "dusk" command to the "php artisan dusk" Artisan command...
    elif [ "$1" == "dusk" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            docker-compose exec \
                -u sail \
                -e "APP_URL=http://${APP_SERVICE}" \
                -e "DUSK_DRIVER_URL=http://selenium:4444/wd/hub" \
                "$APP_SERVICE" \
                php artisan dusk "$@"
        else
            sail_is_not_running
        fi

    # Proxy the "dusk:fails" command to the "php artisan dusk:fails" Artisan command...
    elif [ "$1" == "dusk:fails" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            docker-compose exec \
                -u sail \
                -e "APP_URL=http://${APP_SERVICE}" \
                -e "DUSK_DRIVER_URL=http://selenium:4444/wd/hub" \
                "$APP_SERVICE" \
                php artisan dusk:fails "$@"
        else
            sail_is_not_running
        fi

    # Initiate a Laravel Tinker session within the application container...
    elif [ "$1" == "tinker" ] ; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            docker-compose exec \
                -u sail \
                "$APP_SERVICE" \
                php artisan tinker
        else
            sail_is_not_running
        fi

    # Proxy Node commands to the "node" binary on the application container...
    elif [ "$1" == "node" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            docker-compose exec \
                -u sail \
                "$APP_SERVICE" \
                node "$@"
        else
            sail_is_not_running
        fi

    # Proxy NPM commands to the "npm" binary on the application container...
    elif [ "$1" == "npm" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            docker-compose exec \
                -u sail \
                "$APP_SERVICE" \
                npm "$@"
        else
            sail_is_not_running
        fi

    # Proxy NPX commands to the "npx" binary on the application container...
    elif [ "$1" == "npx" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            docker-compose exec \
                -u sail \
                "$APP_SERVICE" \
                npx "$@"
        else
            sail_is_not_running
        fi

    # Proxy YARN commands to the "yarn" binary on the application container...
    elif [ "$1" == "yarn" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            docker-compose exec \
                -u sail \
                "$APP_SERVICE" \
                yarn "$@"
        else
            sail_is_not_running
        fi

    # Initiate a MySQL CLI terminal session within the "mysql" container...
    elif [ "$1" == "mysql" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            docker-compose exec \
                mysql \
                bash -c 'MYSQL_PWD=${MYSQL_PASSWORD} mysql -u ${MYSQL_USER} ${MYSQL_DATABASE}'
        else
            sail_is_not_running
        fi

    # Initiate a MySQL CLI terminal session within the "mariadb" container...
    elif [ "$1" == "mariadb" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            docker-compose exec \
                mariadb \
                bash -c 'MYSQL_PWD=${MYSQL_PASSWORD} mysql -u ${MYSQL_USER} ${MYSQL_DATABASE}'
        else
            sail_is_not_running
        fi

    # Initiate a PostgreSQL CLI terminal session within the "pgsql" container...
    elif [ "$1" == "psql" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            docker-compose exec \
                 pgsql \
                 bash -c 'PGPASSWORD=${PGPASSWORD} psql -U ${POSTGRES_USER} ${POSTGRES_DB}'
        else
            sail_is_not_running
        fi

    # Initiate a Bash shell within the application container...
    elif [ "$1" == "shell" ] || [ "$1" == "bash" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            docker-compose exec \
                -u sail \
                "$APP_SERVICE" \
                bash "$@"
        else
            sail_is_not_running
        fi

    # Initiate a root user Bash shell within the application container...
    elif [ "$1" == "root-shell" ] ; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            docker-compose exec \
                "$APP_SERVICE" \
                bash "$@"
        else
            sail_is_not_running
        fi

    # Initiate a Redis CLI terminal session within the "redis" container...
    elif [ "$1" == "redis" ] ; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            docker-compose exec \
                redis \
                redis-cli
        else
            sail_is_not_running
        fi

    # Share the site...
    elif [ "$1" == "share" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            docker run --init --rm -p $SAIL_SHARE_DASHBOARD:4040 -t beyondcodegmbh/expose-server:latest share http://host.docker.internal:"$APP_PORT" \
            --server-host="$SAIL_SHARE_SERVER_HOST" \
            --server-port="$SAIL_SHARE_SERVER_PORT" \
            --auth="$SAIL_SHARE_TOKEN" \
            --subdomain="$SAIL_SHARE_SUBDOMAIN" \
            "$@"
        else
            sail_is_not_running
        fi

    # Pass unknown commands to the "docker-compose" binary...
    else
        docker-compose "$@"
    fi
else
    docker-compose ps
fi
