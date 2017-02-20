# Bargain

Bargain is a POC for a money transaction between people.

# setting up

You'll need a Drupal environment: PHP 5.6+(7 and above is recomended), MySQL, 
Apache, Drush and composer.

# OpenSSL keys.
```
openssl genrsa -out private.key 2048
openssl rsa -in private.key -pubout > public.key
```

# Installing
Just fire up the default.install.sh file and wait for stuff to get down.

# Set up the SSL
Go to `web/admin/config/people/simple_oauth` and set the path for the private 
and public key.

# Access token acquiring
Since our this is a decouple project you need to set up a client: 
`web/admin/config/people/simple_oauth/oauth2_client`.

In the password set `1234` as an example.

To get the access token you need to do a POST to `oauth/token`:

```JSON
{
  "grant_type": "password",
  "client_id": "CLIEND_UUID(9f6e6413-8128-40a5-b619-a3433aaf726f)",
  "client_secret": "1234",
  "username": "admin",
  "password": "admin"
}
```

You'll get:

```JSON
{
  "token_type": "Bearer",
  "expires_in": 299,
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjdhMzdlMzNlYzk2MWFkZWZkZGU3Mjg0ODViNmU2ZGIyMmNlNzhiOGI3MzUzYjU3MjliMzQzNGEwYWI0NTg3ZjdjYmJiYmU0YjM4ZDYxYzdjIn0.eyJhdWQiOiI5ZjZlNjQxMy04MTI4LTQwYTUtYjYxOS1hMzQzM2FhZjcyNmYiLCJqdGkiOiI3YTM3ZTMzZWM5NjFhZGVmZGRlNzI4NDg1YjZlNmRiMjJjZTc4YjhiNzM1M2I1NzI5YjM0MzRhMGFiNDU4N2Y3Y2JiYmJlNGIzOGQ2MWM3YyIsImlhdCI6MTQ4NDU1ODM5NiwibmJmIjoxNDg0NTU4Mzk2LCJleHAiOjE0ODQ1NTg2OTUsInN1YiI6IjEiLCJzY29wZXMiOlsiYXV0aGVudGljYXRlZCJdfQ.qC2SQ3LdFM-67qXAiInSHvIMbSCoBy-4R__l_M_1ZkaHgTV95qiKFDCwkLXk01ZC8W7Dhz_bL7SiieFvQNIM5EQsberwneuK4Fcjo5n5LFOmRJcZ6uvhrGjsX_QEqfYsN9NU2dYIugoabauHC0Y_xokp5InxhlHPS6Q_2CEkrmv4uT0hoeep1bJymViiVkJfMEIAPPMqtyeN5xe9XPz9WzKdMa9ccfkrq2vBfP23z6GF_OcSBbpG9FCUllsCEAlKcY3iyPAQbJ2XEg4ENVWSCr7B7Uob2UODHvfymPDSLJ6lp58a8sxhIp4Yx2A1a20vtZ987LCLzqRL-ICRqp576A",
  "refresh_token": "cnRp0nI9BMDIeZldOf21mHqmNgCeMVX0mlN4N62PTpmt9nmvlLfJM0aAMyuGQQVabCJWWw0lxpuctE+d2OroFXH80iCAFzfC/YU4VmkHPGCgTXzkFz7WPWGyHH8AAYqQW+tQFkja+8WU+aIwUFarsUTDv1doaZmtdJ4K+CO85mq1IRyLe4GXQhiBdaaMd3OO7U2UCPSrEtLKgOojTEfFkSJwK2Lgmd59yYCeQUbAe6//XWDoE/LX2abrCAiW118iBDgiKVZR/gzo9v5p/VDGZ0s1vsDgViXGeUNsO97W4tgzJ9kJveML7Ub8vUuwm+LAfJXzeF5Y5RkUELW9gHwskqAyzyoX3bgJDkY5gPYtQZXTntaOKi3JHKRfMTl3pWyg6SAeqz3d+0Mt0NDX1SDTLcflost2GfUyIl22iVuozg/piWzpzZf8vF1o8TAhVR7F4YLE21NrC2gZ3I2BY1AZCbX7kB27AhZ3yJXYDnbiur0i/D1UGlNa+MNohrGheYKhHQu/vnWYcReMbRISwB/Ioy6iDZBH0T3R+UMuuui4GcgJ7Q55hrECZiugYLnFnvR0hnKvNOHKxHaeZmD/b2MXdL4VvNGx81j6WxeqOdJE4kFjCdIZJ62cCmi3heX0apItIjl7DaZi4ZxwdePc63Hi4+nAfmqQxmba8ftaTfsthpw="
}
```

When accessing the backend, the headers should be:
```json
{
  "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjdhMzdlMzNlYzk2MWFkZWZkZGU3Mjg0ODViNmU2ZGIyMmNlNzhiOGI3MzUzYjU3MjliMzQzNGEwYWI0NTg3ZjdjYmJiYmU0YjM4ZDYxYzdjIn0.eyJhdWQiOiI5ZjZlNjQxMy04MTI4LTQwYTUtYjYxOS1hMzQzM2FhZjcyNmYiLCJqdGkiOiI3YTM3ZTMzZWM5NjFhZGVmZGRlNzI4NDg1YjZlNmRiMjJjZTc4YjhiNzM1M2I1NzI5YjM0MzRhMGFiNDU4N2Y3Y2JiYmJlNGIzOGQ2MWM3YyIsImlhdCI6MTQ4NDU1ODM5NiwibmJmIjoxNDg0NTU4Mzk2LCJleHAiOjE0ODQ1NTg2OTUsInN1YiI6IjEiLCJzY29wZXMiOlsiYXV0aGVudGljYXRlZCJdfQ.qC2SQ3LdFM-67qXAiInSHvIMbSCoBy-4R__l_M_1ZkaHgTV95qiKFDCwkLXk01ZC8W7Dhz_bL7SiieFvQNIM5EQsberwneuK4Fcjo5n5LFOmRJcZ6uvhrGjsX_QEqfYsN9NU2dYIugoabauHC0Y_xokp5InxhlHPS6Q_2CEkrmv4uT0hoeep1bJymViiVkJfMEIAPPMqtyeN5xe9XPz9WzKdMa9ccfkrq2vBfP23z6GF_OcSBbpG9FCUllsCEAlKcY3iyPAQbJ2XEg4ENVWSCr7B7Uob2UODHvfymPDSLJ6lp58a8sxhIp4Yx2A1a20vtZ987LCLzqRL-ICRqp576A"
}
```

# Endpoints

## `/api`
Will return a list of all the endpoint available and a small description about 
them.

Example:
```json
[
  "chat_room_message_rest": {
    "path": "/messages/{bargain_chat_room}",
    "label": "Chat room message",
    "description": "Display all the messages in the current room.",
    "methods": "get,post,patch"
  },

  "chat_rooms_rest": {
    "path": "/messages",
    "label": "Chat rooms",
    "description": "Display list of the rooms which the user can access.",
    "methods": "get"
  },

  "rest_bargains": {
    "path": "/bargains/{type}",
    "label": "Rest bargains",
    "description": "Display all the bargains.",
    "methods": "get"
  }
]
```

## `/messages`

**Description:** Return all the chat rooms the user can have access to. A chat
room will open between the seller and the buyer all the both of them, along side
an administrator, will have access to the page.

Example:
```JSON
[
  {
    "id": "2",
    "uuid": "c9e8bc50-3e41-4de6-a1a3-439912c4a3e3",
    "langcode": "en",
    "user_id": {
      "id": "1",
      "label": "admin"
    },
    "buyer": {
      "id": "11",
      "label": "demo2"
    },
    "name": "www",
    "status": "1",
    "created": "1486736561",
    "changed": "1486736561"
  },
]
```

**Note**: `user_id` is the seller.

## `/messages/{bargain_chat_room}`

`bargain_chat_room`: The room UUID.

**Description:** Will return the messages in the room.

Example:
```JSON
[
  {
    "id": "4",
    "uuid": "8cbd6c93-5fa4-4cb3-a9a7-9def90866dc1",
    "langcode": "en",
    "room": "1",
    "user_id": {
      "id": "1",
      "label": "admin"
    },
    "status": "read",
    "text": "<p>sadasdsadasd</p> ",
    "created": "1486743674",
    "changed": "1486750169"
  },
  {
    "id": "1",
    "uuid": "3a1e1de7-1fe9-429f-8081-3e0a0478b6b9",
    "langcode": "en",
    "room": "1",
    "user_id": {
      "id": "4",
      "label": "demo"
    },
    "status": "sent",
    "text": "<p>Foo</p>",
    "created": "1486675868",
    "changed": "1486675868"
  }
]
```

### Adding a message status

**Request type:** Post

**Payload example:**
```JSON
{
  "text": "Message"
}
```

### Updating a message status

**Request type:** Patch

**Payload example:**
```JSON
{
  "status": "read",
  "message_uuid": "3a1e1de7-1fe9-429f-8081-3e0a0478b6b9"
}
```

The status of the message will be changes from `resd` to `sent` When the user 
will read the message. We will have to change it.

## `/bargains/{type}`

`type`: The type of the bargain: call/seek

The bargain end point will tell us who call for selling his money and who seek 
to buy money.

**Example:**

```json
[
  {
    "id": "1",
    "uuid": "fee7d0ae-0779-4d88-b3fd-0e128430522a",
    "langcode": "en",
    "type": "call",
    "user": "1",
    "name": "Foo",
    "status": "1",
    "created": "1486663167",
    "changed": "1486663167",
    "coin": "ILS",
    "amount": "10",
    "exchange_rate": "2.5"
  }
]
```

`coin`: The type of the coin: ILS, EURO etc. etc.

`amount`: How much units the user is selling/seeking

`exchange_rate`: How much does the user would like to pay/sell the coins

## `/rest_user`
By default, will return the user object of the current logged in user.

**Example:**
```json
{
  "uid": "1",
  "uuid": "45502c49-a09e-43d5-a4f0-78b0a9fed8d1",
  "langcode": "en",
  "preferred_langcode": "en",
  "name": "admin",
  "mail": "admin@example.com",
  "timezone": "Europe/Berlin",
  "status": "1",
  "created": "1486231440",
  "changed": "1486933901",
  "access": "1487538187",
  "login": "1486847705",
  "init": "admin@example.com",
  "roles": "administrator",
  "default_langcode": "1",
  "field_first_name": "Roy",
  "field_last_name": "Segall"
}

```

### Creating a user

**Request type:** Post

**Payload example:**
```JSON
{
  "client_id": "CLIEND_UUID(9f6e6413-8128-40a5-b619-a3433aaf726f)",
  "client_secret": "1234",
  "name": "CaptainAmerica",
  "password": "SharonCarter",
  "mail": "CaptainAmerica@shield.com",
}
```

### Updating a password

**Request type:** Patch

**Payload example:**
```JSON
{
  "pass": "new_pass", 
  "previous_pass": 1234
}
```

### Updating fields:
**Request type:** Patch

**Payload example:**
```JSON
{
  "field_first_name": "Steve", 
}
```

## `/transaction`

## `/transaction/{bargain_transaction}`
