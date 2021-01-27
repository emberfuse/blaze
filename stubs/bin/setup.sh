#!/bin/bash

function main() {
    installProject
    # if [ $? -eq 0 ]; then
    #     php artisan preflight:project
    # fi
}

function installProject() {
    installDependencies
    prepareProjectFiles
    installAndCompileAssets
}

function installDependencies() {
    printf "Installing dependencies\n"
    COMPOSER_MEMORY_LIMIT=-1 composer install
}

function prepareProjectFiles() {
    if [[ ! -f .env && -f .env.example ]]; then
        printf "Copying environment variables\n"
        cp .env.example .env
        printf "Generate new application key\n"
        php artisan key:generate
    fi
    printf "Setting permissions\n"
    chmod +x artisan
}

function installAndCompileAssets() {
    printf "Installing assets\n"
    npm i
    printf "Compiling assets\n"
    npm run dev
}

main
