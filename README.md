# sploder-revival

PHP Backend code used to power the Sploder Revival.

## Flash & Security Notice

In order to use the Sploder Revival (in a usable state, not via [Ruffle](https://ruffle.rs/)), you will need to install a working version of Flash Player. We recommend downloading either of these:

- [CleanFlash](https://gitlab.com/cleanflash/installer) A modified Chinese Flash Player without adware. Flash in China still receives security updates for Windows and macOS.
- [Adobe Flash Player](https://archive.org/details/flashplayer_old) Official version of Flash Player available in all other regions. No longer updated after December of 2020.

As well as a Flash/CleanFlash compatible browser:

- [Waterfox Classic](https://classic.waterfox.net/)
- [Pale Moon](https://www.palemoon.org/)
- [K-Meleon](http://kmeleonbrowser.org/forum/read.php?19,154431)

Note that Flash has been discontinued since January 2021, and that using a Flash compatible browser and Flash itself should be exercised with caution outside of Sploder Revival usage.

## Prerequisites

To run the Sploder Revival, you will need to ensure you have the following installed:

Also, to ensure formatting is done correctly, please run `make dev.hook` to install the necessary hooks as well as `composer install`.

### Docker Installation Method

- [Composer](https://getcomposer.org/download/)
- [Make](https://www.gnu.org/software/make/)
- [Docker](https://www.docker.com/get-started/)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Docker Buildx](https://docs.docker.com/reference/cli/docker/buildx/)

### Manual Installation Method

- [Composer](https://getcomposer.org/download/)
- [PHP](https://www.php.net/manual/en/install.php)
- [PHP imagick](https://www.php.net/manual/en/book.imagick.php)
- [PHP mbstring](https://www.php.net/manual/en/book.mbstring.php)
- [PHP gd](https://www.php.net/manual/en/book.image.php)
- [PHP pdo](https://www.php.net/manual/en/book.pdo.php)
- [PHP pdo_pgsql](https://www.php.net/manual/en/ref.pdo-pgsql.php)
- [PHP pdo_sqlite](https://www.php.net/manual/en/ref.pdo-sqlite.php)
- [PHP xml](https://www.php.net/manual/en/simplexml.examples-basic.php)
- [Apache2](https://httpd.apache.org/)

## Running

### Docker

The provided Makefile provides an easy to use interface for running the development docker compose files.

First, you need to build the image. Anytime you make changes to anything in `./src` you will need to rebuild.

```shell
make build # build the sploder-image
```

After this, you will want to make sure you bootstrap the database for local development. This only needs to be ran once, or can be re-ran if you manually delete the database information.

```shell
make dev.bootstrap # RUN ONCE - bootstraps the backup database
```

For development, the following commands will let you run the instance locally
```shell
make dev # Runs the dev docker compose, detaching the container
make dev.watch # Runs the dev docker compose
```

TODO - Production docker-compose file does not yet exist

### Manual

If executing a manual deployment, be sure to copy `./src/.env.example` to `./src/.env` and populate the values with the specified configuration.
