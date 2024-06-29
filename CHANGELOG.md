# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.5.0] - 2024-06-29
- migrate to microservices architecture

## [1.4.0] - 2024-06-26
- add architecture test with `phpat`

## [1.3.0] - 2024-06-19
- add `coinbase.com` as exchange rate provider
- add chain for currency providers and retries
- add log response from providers

## [1.2.0] - 2024-06-14
- transition to hexagonal architecture
- add k6 load tests

## [1.1.0] - 2024-06-07
- add linters: `PHPStan` and `PHP_CodeSniffer`
- run tests and linters in GitHub Actions

## [1.0.0] - 2024-05-18
- add api for get current rate and subscription on rate
- send email with rate
