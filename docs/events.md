# Events

| Event       | Description                      | Publisher     | Consumer       | Serializer | Example                                                                                                                                                          |
|-------------|----------------------------------|---------------|----------------|------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| mail.create | initialization of sending email  | subscriptions | mailer         | Json       | ```{"eventId":"019078f8-2704-7380-b327-999845c4bc6b","eventType":"mail.create","timestamp":172001649,"data":{"email":"mail1@mail.com","currency":{"iso3":"UAH","rate":5.012,"updatedAt":1720016496}}}```                                                                                                                                                          |
| mail.sent   | email sent                       | mailer        | subscriptions  | Json       | ```{"eventId":"019078f8-2704-72ed-865f-e276f6883bf5","eventType":"mail.sent","timestamp":1720016496,"data":{"email":"mail1@mail.com","timestamp":1720016496}}``` |

