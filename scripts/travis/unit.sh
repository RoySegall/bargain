#!/usr/bin/env bash
cd web
./../vendor/bin/drush site-install --verbose --yes --db-url=sqlite://tmp/site.sqlite
./../vendor/bin/phpunit -c ../phpunit.xml.dist web/modules/custom/
