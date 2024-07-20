# [currency-rates-api](https://gitlab.com/dima.novoseltsev/currency-rates-api)

[![version][version-badge]][CHANGELOG]


# 💱 Currency Rates Api

This project utilizes a microservice architecture to provide real-time exchange rate data and a subscription service for users. The architecture is designed for scalability, reliability, and ease of maintenance, comprising several independent services that communicate through well-defined APIs.

# APIs

For detailed documentation, check out

| Api           | Endpoint              | Documentation             |
|---------------|-----------------------|---------------------------|
| Currencies    | http://127.0.0.1:9822 | http://127.0.0.1:9822/doc |
| Subscriptions | http://127.0.0.1:9832 | http://127.0.0.1:9832/doc |
| Customers     | http://127.0.0.1:9842 | http://127.0.0.1:9842/doc |
| Mailer        | http://127.0.0.1:9852 | http://127.0.0.1:9852/doc |

# Events

| Event               | Description                  | Publisher     | Consumer      | Serializer | Example                                                                                                                                                                                                                       |
|---------------------|------------------------------|---------------|---------------|------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| mail.sent           | email sent                   | mailer        | subscriptions | Json       | `{"eventId":"019078f8-2704-72ed-865f-e276f6883bf5","eventType":"mail.sent","timestamp":1720016496,"data":{"email":"mail1@mail.com","timestamp":1720016496}}`                                                                  |
| currency.update     | after create/update currency | mailer        | mailer        | Json       | `{"eventId":"019079d6-5873-71b2-b2f4-ec81f737c0b0","eventType":"currency.update","timestamp":1720031008,"data":{"rate":5.1,"iso3":"UAH","updatedAt":1720031008}}`                                                             |
| subscription.create | after create subscription    | subscriptions | mailer        | Json       | `{"eventId":"019079d6-5873-71b2-b2f4-1e88062ba8ad","eventType":"subscription.create","timestamp":1720031008,"data":{"id":1,"email":"mail@mail.com","createdAt": 1720031008,"updatedAt":1720031008, "lastSendAt":1720031008}}` |
| subscription.delete | after delete subscription    | subscriptions | mailer        | Json       | `{"eventId":"019079d6-5873-71b2-b2f4-20a1b1a5e1ab","eventType":"subscription.delete","timestamp":1720031008,"data":{"id":1,"email":"mail@mail.com","createdAt": 1720031008,"updatedAt":1720031008, "lastSendAt":1720031008}}` |


# License

This project is licensed under the MIT license - see
the [LICENSE.md](https://gitlab.com/dima.novoseltsev/currency-rates-api/-/blob/main/LICENSE.md) file for details.

[CHANGELOG]: ./CHANGELOG.md
[version-badge]: https://img.shields.io/badge/version-1.9.0-blue.svg
