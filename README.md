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

# Events

| Event       | Description                      | Publisher     | Consumer       | Serializer | Example                                                                                                                                                          |
|-------------|----------------------------------|---------------|----------------|------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| mail.create | initialization of sending email  | subscriptions | mailer         | Json       | ```{"eventId":"019078f8-2704-7380-b327-999845c4bc6b","eventType":"mail.create","timestamp":172001649,"data":{"email":"mail1@mail.com","currency":{"iso3":"UAH","rate":5.012,"updatedAt":1720016496}}}```                                                                                                                                                          |
| mail.sent   | email sent                       | mailer        | subscriptions  | Json       | ```{"eventId":"019078f8-2704-72ed-865f-e276f6883bf5","eventType":"mail.sent","timestamp":1720016496,"data":{"email":"mail1@mail.com","timestamp":1720016496}}``` |


# License

This project is licensed under the MIT license - see
the [LICENSE.md](https://gitlab.com/dima.novoseltsev/currency-rates-api/-/blob/main/LICENSE.md) file for details.

[CHANGELOG]: ./CHANGELOG.md
[version-badge]: https://img.shields.io/badge/version-1.6.0-blue.svg
