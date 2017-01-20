#!/usr/bin/env bash
CORE_DIRECTORY=$(pwd)
cd web
./../vendor/bin/drush site-install --verbose --yes --db-url=sqlite://tmp/site.sqlite
cd $CORE_DIRECTORY
./vendor/bin/phpunit -c web/core/phpunit.xml.dist web/modules/custom
