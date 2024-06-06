#!/bin/bash
php tests/bin/yii migrate --interactive=0
./vendor/bin/codecept run functional $*
