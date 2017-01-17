#!/bin/bash

# Run either PHPUnit tests or PHP_CodeSniffer tests on Travis CI, depending
# on the passed in parameter.

case "$1" in
    cs)
        ./vendor/bin/phpcs
        exit $?
        ;;
    kernal)
        cd $TRAVIS_BUILD_DIR/web
        sleep 3
        ./../vendor/bin/drush site-install --verbose --yes --db-url=sqlite://tmp/site.sqlite;
        ./../vendor/bin/drush runserver http://127.0.0.1:8080 &
        echo "kernal";
#        exit $?
esac
