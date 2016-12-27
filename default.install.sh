#!/usr/bin/env bash

composer install

alias console=${PWD}/vendor/drupal/console/bin/drupal
alias drush=${PWD}/vendor/drush/drush/drush
chmod -R 777 web/sites/default

cd web

drush si bargain --account-pass=admin --db-url=mysql://root:root@localhost/bargain -y
