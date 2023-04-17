# Test task: list of regions

## About

Retrieves and displays data about different regions from database in a sortable table in browser.

## Deployment

1. Cd to folder:

`cd docker-compose`

2. Copy .env template to .env:

`cp .env.example .env`

3. Set REGION_API_URL correctly - change your IP/domain if required, like:

`https://yourdomain.com:8080/api/region`

4. Change port for nginx in docker-compose.yml if required (defaults to 8080).

5. Start the project:

`docker compose up -d`

6. Copy your databasedump  to `docker-compose` folder. Name it `dump.sql`.

7. Restore the database:

`make restore-db`

8. Install composer dependencies and NPM modules:

`make npm-i`

`make composer-i`

9. Restart some containers once again.

`docker-compose up -d`

10. All running! You're awesome!

## Issues

1. Time. I only had a few hours to do this.
2. I first wanted to make a simple ORM with query options, limits, and responses, but see pt.1. For now, I've dropped all that code.
3. For now, the frontend uses PrimeVue. It's actually fast and I'm often using it in production, but IDK if this was what you wanted to see - see p.1. Anyway, I don't have any problems writing my own manual filter logic.
4. Response logic. I wanted to create an HTML page and mount a Vue app  a.k.a. a Custom Element (like Web component), but didn't have time (yet) to implement the View part.

Why a.k.a and not a geniune Custom element: e.g. see https://github.com/vuejs/core/issues/4662. In short: styles are difficult to connect, plugins aren't working, etc.