# Badge.Team Hatchery 2.0

[![Build Status](https://travis-ci.com/badgeteam/HatcheryNG.svg)](https://travis-ci.com/badgeteam/HatcheryNG)
[![Known Vulnerabilities](https://snyk.io/test/github/badgeteam/Hatchery/badge.svg)](https://snyk.io/test/github/badgeteam/Hatchery)
[![Github Actions Laravel](https://github.com/badgeteam/HatcheryNG/workflows/Laravel%20feature%20testing%20and%20static%20analysis/badge.svg)](https://github.com/badgeteam/HatcheryNG/actions)
[![Github Actions Node](https://github.com/badgeteam/HatcheryNG/workflows/Node%20-%20yarn%20build/badge.svg)](https://github.com/badgeteam/HatcheryNG/actions)
[![CircleCI](https://circleci.com/gh/badgeteam/HatcheryNG.svg?style=svg)](https://circleci.com/gh/badgeteam/HatcheryNG)

Simple micropython software repository for Badges.

[Live Site](https://badge.team) \| 
[API Playground](https://badge.team/api) \|
[Documentation](https://docs.badge.team/hatchery/) \|
[GitHub](https://github.com/badgeteam/)

## Installation

-   Requires PHP 7.3 or later
-   Requires Python 3.6 or later
-   Requires Node.js 6.0 or later
-   Requires Redis 3.2 or later
-   Requires Git 2.8 or later

For deployment on a server.

```bash
cp .env.example .env
```

Edit your database, mail and other settings..

Or copy the local dev environment config.

```bash
cp .env.dev .env
```

Install and configure required items.

```bash
pip install pyflakes
composer install
php artisan key:generate
php artisan migrate
yarn
yarn production
```

Installing and configuring the async websocket server.

```bash
yarn global add laravel-echo-server
laravel-echo-server init
```

Compiling and installing the patched minigzip.

```bash
wget http://zlib.net/zlib-1.2.11.tar.gz
tar xvf zlib-1.2.11.tar.gz
cd zlib-1.2.11
./configure
echo -e "#define MAX_WBITS  13\n$(cat zconf.h)" > zconf.h
make
sudo make install
```

If you would like to have Verilog support.

Install [Icarus Verilog](https://iverilog.fandom.com/wiki/Installation_Guide) 0.9 or later.

TODO more info ;)

### Services

You'll need a be running [Laravel Horizon](https://laravel.com/docs/7.x/horizon#deploying-horizon) service.

For the websocket server.
```bash
laravel-echo-server start
```

### Running the development server locally

After going through the steps

```bash
php artisan serve
```

If you don't want to install things and do the above steps, Docker makes all the above as easy as:

```bash
docker-compose up # -d for daemon mode
docker exec -it hatchery_laravel_1 php artisan migrate --seed
docker exec -it hatchery_laravel_1 yarn watch
```

Enjoy your Hatchery at <http://localhost:8000>

## [API](docs/API.md)

See: <https://badge.team/api>

## Running tests

### Static analysis

```bash
vendor/bin/phpstan analyse
```

### Unit and Feature testing

Run all the tests

```bash
vendor/bin/pest --no-coverage
```

Run a test suite (for a list of availabe suites, see `/phpunit.xml`)

```bash
vendor/bin/pest --testsuite <suite_name>
```

Run a specific test file

```bash
vendor/bin/pest tests/<optional_folders>/TestFileName
```

Run a specific test case

```bash
vendor/bin/pest --filter <test_case_name>
```

Generate code coverage as HTML

```bash
vendor/bin/pest --coverage-html docs/coverage
```

This will create the code coverage docs in `docs/coverage/index.html`

Not: Clear caches before testing!

```bash
php artisan route:clear && php artisan config:clear
```

#### Testing with Codeception

```bash
vendor/bin/codecept build
vendor/bin/codecept run
```
## License

Hatchery is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Fbadgeteam%2FHatchery.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2Fbadgeteam%2FHatchery?ref=badge_large)
