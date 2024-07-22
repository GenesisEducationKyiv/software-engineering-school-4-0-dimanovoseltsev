# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.3.0] - 2024-07-14
- add temporal saga on create subscription

## [1.2.0] - 2024-07-05
- remove event `mail.create`
- move cron send message to mailer
- add events: `subscription.create` and `subscription.delete`

## [1.1.0] - 2024-07-03

- change structure event `mail.sent`

## [1.0.0] - 2024-06-29

- init
- subscribe to event `mail.sent`
