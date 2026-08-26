#!/bin/bash
set -e

# Install dependencies PHP & Node untuk build Vite assets
composer install --optimize-autoloader --no-dev --no-interaction
npm install
npm run build
