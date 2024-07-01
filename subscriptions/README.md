# [currency-rates-api](https://gitlab.com/dima.novoseltsev/currency-rates-api)

[![version][version-badge]][CHANGELOG]
[![pipeline status][pipeline-badge]][PIPELINES]

[![coverage][coverage-badge]][JOBS]
![php-version]
![mysql-version]

# 💱 Subscriptions Api

The migration will be carried out in the Docker container named `app-setup`.

Emails are sent once a day using cron jobs. A separate container named `cron` was created specifically for these tasks. You can view the list of commands and logs in the `docker/cron` directory.

# Quick Start (via  docker)
```bash
docker-compose pull
docker-compose down -v --remove-orphans
docker-compose up -d --build
docker-compose logs -f app-setup
```

That's all - your application is available at http://127.0.0.1:9832

# Documentation
For detailed documentation, check out http://127.0.0.1:9832/doc

# Testing
### Unit Tests
Unit tests, crucial for maintaining code quality, ensure the integrity of the codebase and its components. They focus on specific code units, assessing their functionality in isolation and aiding in early issue detection. To run these tests, execute the command
```bash
docker-compose exec -u root fpm bash
./vendor/bin/codecept run unit $* --coverage --coverage-html
```

### Functional Tests
Functional tests assess the overall performance and behavior of the application, evaluating how different components interact. These tests help ensure that the software functions as intended and meets the specified requirements. To execute the functional tests, run the following command: 
```bash
docker-compose exec -u root fpm bash
php tests/bin/yii migrate --interactive=0
./vendor/bin/codecept run functional $*
```

# License

This project is licensed under the MIT license - see
the [LICENSE.md](https://gitlab.com/dima.novoseltsev/currency-rates-api/-/blob/main/LICENSE.md) file for details.

[CHANGELOG]: ./CHANGELOG.md
[PIPELINES]: https://gitlab.com/dima.novoseltsev/currency-rates-api/pipelines
[JOBS]: https://gitlab.com/dima.novoseltsev/currency-rates-api/-/jobs
[version-badge]: https://img.shields.io/badge/version-1.0.0-blue.svg
[pipeline-badge]: https://gitlab.com/dima.novoseltsev/currency-rates-api/badges/main/pipeline.svg
[coverage-badge]: https://gitlab.com/dima.novoseltsev/currency-rates-api/badges/main/coverage.svg
[php-version]:https://img.shields.io/static/v1?label=php&message=8.3&color=green
[mysql-version]:https://img.shields.io/static/v1?label=mysql&message=8.0&color=green
