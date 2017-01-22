#!/usr/bin/env bash
CORE_DIRECTORY=$(pwd)
cd web
./../vendor/bin/drush site-install --verbose --yes --db-url=mysql://root:@127.0.0.1/bargain
cd $CORE_DIRECTORY
./vendor/bin/phpunit -c web/core/phpunit.xml.dist web/modules/custom
