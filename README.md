## Installation

You need to install docker and docker-compose on your machine.

We will clone 2 repositories, one is sakai-dao-api and another is web3js.

```
# git clone https://github.com/webooswap1/sakai-dao-api sakai-dao-api
# cd sakai-dao-api
# git clone https://github.com/webooswap1/express-web3.git web3js
``` 

## Running Installation

```
# cp .env.example .env
# docker compose up -d --build
# docker compose ps
# docker exec -it sakaidao2_php composer install
# docker exec -it sakaidao2_php php artisan migrate
# docker exec -it sakaidao2_php php artisan db:seed
```

### Ports

- 33060: mysql
- 7071: nginx
- 9000: php
- 7072: phpmyadmin
- 7073: web3js

### Apps Config

All of config store on database, you can change it on database for **config_address** and **configs**.

- If you need to change address of DAO and Proposal address, open table **config_addresses** and please change on
  _address_ field, **do not change code field!**
- If you need to change rpc_url, please open **configs** on configs table and update **rpc_url** field.
- If its fresh installation, please create 1 row on **configs** table with **rpc_url** field is your rpc_url.

### Manual Syncronize

There is multiple environtment on this system, EVM, Mysql Database, Laravel Backend and Web3, we need to syncronize it
manually if needed.
Syncronize data will store Log from evm, dapps frontend and database.

| Command                           | Description                                                       |
|-----------------------------------|-------------------------------------------------------------------|
| **app:sync-config**               | Syncronize total suplly on DAO Token and get minimum_vote_balance |
| **app:sync-user-balance**         | Syncronize user balance between our database and EVM              |
| **app:sync-proposal**             | Syncronize proposal data between our database and EVM             |
| **app:sync-history-reward-stake** | Syncronize reward of staking for log and chart mechanism          |
| **app:sync-history-referrer**     | Syncronize reward referral log                                    |

For running syncronize, you can use this command:

```
# docker exec -it sakaidao2_php php artisan {command}
```

Or, if you prefer using API, just call this endpoint:

```
GET
/api/sync
```

## Proxy Reverse

Cause its using docker, you need to setup proxy reverse on your server, here is example for nginx:

```
server {
    listen 80;
    server_name sakaidao2.com;
    location / {
        proxy_pass http://localhost:7071;
    }
}
```

You can update with your own domain, and reverse it to nginx port on **7071**.

### Endpoint

There is some endpoint.

| HTTP Method | Endpoint                               | Description                                           |
|-------------|----------------------------------------|-------------------------------------------------------|
| GET         | /api/ping                              | Responds to a ping request                            |
| GET         | /api/time                              | Retrieves the current server time                     |
| POST        | /api/stake                             | Handles staking actions                               |
| GET         | /api/validators                        | Retrieves the list of validators                      |
| POST        | /api/proposal                          | Creates a new proposal                                |
| GET         | /api/proposal                          | Retrieves the list of proposals                       |
| GET         | /api/rewardFromReferrer                | Retrieves rewards from referrals                      |
| GET         | /api/rewardFromStake                   | Retrieves rewards from staking                        |
| POST        | /api/profile                           | Updates the user profile                              |
| DELETE      | /api/profile                           | Deletes the user profile                              |
| GET         | /api/sync                              | Synchronizes with Web3                                |
| GET         | /api/stake-reward-history/{address}    | Retrieves stake reward history for a given address    |
| GET         | /api/referral-reward-history/{address} | Retrieves referral reward history for a given address |
| GET         | /api/web3/ping                         | Responds to a ping request within the web3 prefix     |
| GET         | /profile                               | Get profile picture of user                           |

### GET /api/ping

Sample Response

```json
{
    "meta": {
        "code": 200,
        "state": "success"
    },
    "data": {
        "message": "pong"
    }
}
```

### GET /api/time

Sample Response

```json
{
    "meta": {
        "code": 200,
        "state": "success"
    },
    "data": {
        "time": 1698482803,
        "timezone": "UTC"
    }
}
```

### POST /api/stake

Payload

| field    | required | description                             |
|----------|----------|-----------------------------------------|
| type     | YES      | STAKE or UNSTAKE                        |
| txHash   | YES      | transactionHash                         |
| amount   | YES      | amount in wei                           |
| referrer | YES      | if there is no referral, please send 0x |

Sample Response

```json
{
    "meta": {
        "code": 200,
        "state": "success"
    },
    "data": {
        "address": "0x123",
        "amount": "10000",
        "referrer": "0x",
        "txHash": "0x",
        "type": "STAKE",
        "updated_at": "2023-10-28T08:49:38.000000Z",
        "created_at": "2023-10-28T08:49:38.000000Z",
        "id": 2
    }
}
```

### GET /api/validators

Sample response

```json
{
    "meta": {
        "code": 200,
        "state": "success"
    },
    "data": [
        {
            "address": "0x25ee8f85778aD26aA871a369Cec5dC11782B4740",
            "powers": "25.00%",
            "amount": "10000000000000000000000",
            "ref_commission": "",
            "accumulation": "",
            "last_stake_date": 1698478506,
            "apr": "100%",
            "status_vote": false
        },
        {
            "address": "0x2150E6455230Bc33d55a50938de820550EDdD279",
            "powers": "75.00%",
            "amount": "30000000000000000000000",
            "ref_commission": "",
            "accumulation": "",
            "last_stake_date": 1698478487,
            "apr": "100%",
            "status_vote": false
        }
    ]
}
```

### GET /api/proposal

Sample Response

```json
{
    "meta": {
        "code": 200,
        "state": "success"
    },
    "data": [
        {
            "id": 1,
            "title": "Proposal 1",
            "description": "Proposal 1",
            "category": "Proposal 1",
            "meta_data": {
                "icon": "http://icon.com"
            },
            "owner": "0x2150E6455230Bc33d55a50938de820550EDdD279",
            "txHash": "0x1028e0d93dab48f4338d6633256311e06c202914cf7f1b582c67c317e93caf0d",
            "proposal_id": "1",
            "status": "published",
            "admin_vote_approve": "5",
            "admin_vote_reject": "0",
            "user_vote_approve": "10000000000000000000000",
            "user_vote_reject": "30000000000000000000000",
            "total_vote": "0",
            "total_participant": "2",
            "created_at": "2023-10-28T07:35:10.000000Z",
            "updated_at": "2023-10-28T07:35:12.000000Z"
        }
    ]
}
```

### GET /api/rewardFromReferrer

Sample Response

```json
{
    "meta": {
        "code": 200,
        "state": "success"
    },
    "data": {
        "amount_in_wei": "5178789504373177842566"
    }
}
```

### GET /api/rewardFromStake

Sample Response

```json
{
    "meta": {
        "code": 200,
        "state": "success"
    },
    "data": {
        "claimed_amount_in_wei": "6214547405247813411078719",
        "unclaimed_amount_in_wei": "1211987399650145772594753"
    }
}
```

### POST /api/profile

this endpoint used for update user profile

| field   | required | description  |
|---------|----------|--------------|
| address | YES      | user address |
| file    | YES      | new image    |

Sample Response 
```json
{
    "meta": {
        "code": 200,
        "state": "success"
    },
    "data": {
        "address": "0x1231231",
        "picture": "5nSKPFi5o2a74ixEw8jZhLJv9dHc0K2ifAnOYeWW.jpg",
        "updated_at": "2023-10-28T09:00:36.000000Z",
        "created_at": "2023-10-28T09:00:36.000000Z",
        "id": 1
    }
}
```


### DELETE /api/profile

this endpoint used for delete user profile

| field   | required | description  |
|---------|----------|--------------|
| address | YES      | user address |

Sample response
```json
{
    "meta": {
        "code": 200,
        "state": "success"
    },
    "data": null
}
```


### GET /api/sync
Syncronize database and web3 evm
```json
{
    "meta": {
        "code": 200,
        "state": "success"
    },
    "data": {
        "message": "success"
    }
}
```


### GET /api/stake-reward-history/{address}
Payload

| field | required | description                   |
|-------|----------|-------------------------------|
| limit | NO       | Limit per page, default is 15 |

```json
{
    "data": [
        {
            "address": "0x25ee8f85778aD26aA871a369Cec5dC11782B4740",
            "amount_in_wei": "464329329446064139941691",
            "amount_in_ether": 464329.3294460641,
            "accumulated_amount_in_wei": "1035757900874635568513119",
            "accumulated_amount_in_ether": 1035757.9008746356,
            "timestamp": 1698478509
        },
        {
            "address": "0x25ee8f85778aD26aA871a369Cec5dC11782B4740",
            "amount_in_wei": "571428571428571428571428",
            "amount_in_ether": 571428.5714285714,
            "accumulated_amount_in_wei": "571428571428571428571428",
            "accumulated_amount_in_ether": 571428.5714285714,
            "timestamp": 1698478499
        }
    ],
    "links": {
        "first": "http://sakai_dao_api.test/api/stake-reward-history/0x25ee8f85778aD26aA871a369Cec5dC11782B4740?page=1",
        "last": "http://sakai_dao_api.test/api/stake-reward-history/0x25ee8f85778aD26aA871a369Cec5dC11782B4740?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://sakai_dao_api.test/api/stake-reward-history/0x25ee8f85778aD26aA871a369Cec5dC11782B4740?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://sakai_dao_api.test/api/stake-reward-history/0x25ee8f85778aD26aA871a369Cec5dC11782B4740",
        "per_page": 15,
        "to": 2,
        "total": 2
    }
}
```


### GET /api/referral-reward-history/{address}
Payload

| field | required | description                   |
|-------|----------|-------------------------------|
| limit | NO       | Limit per page, default is 15 |

```json
{
    "data": [
        {
            "address": "0x2150E6455230Bc33d55a50938de820550EDdD279",
            "amount_in_wei": "2321646647230320699709",
            "amount_in_ether": 2321.6466472303,
            "accumulated_amount_in_wei": "5178789504373177842566",
            "accumulated_amount_in_ether": 5178.7895043731,
            "timestamp": 1698478509
        },
        {
            "address": "0x2150E6455230Bc33d55a50938de820550EDdD279",
            "amount_in_wei": "2857142857142857142857",
            "amount_in_ether": 2857.1428571428,
            "accumulated_amount_in_wei": "2857142857142857142857",
            "accumulated_amount_in_ether": 2857.1428571428,
            "timestamp": 1698478499
        }
    ],
    "links": {
        "first": "http://sakai_dao_api.test/api/referral-reward-history/0x2150E6455230Bc33d55a50938de820550EDdD279?page=1",
        "last": "http://sakai_dao_api.test/api/referral-reward-history/0x2150E6455230Bc33d55a50938de820550EDdD279?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://sakai_dao_api.test/api/referral-reward-history/0x2150E6455230Bc33d55a50938de820550EDdD279?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://sakai_dao_api.test/api/referral-reward-history/0x2150E6455230Bc33d55a50938de820550EDdD279",
        "per_page": 15,
        "to": 2,
        "total": 2
    }
}
```


### GET /api/web3/ping
Check web3js is up and run
```json
{
    "message": "pong"
}
```

### GET /profile
Respond back user images
