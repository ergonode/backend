<p align="center">
  <a href="https://vuejs.org" target="_blank" rel="noopener noreferrer">
    <img width="300" src="https://ergonode.com/wp-content/themes/ergonode/assets/img/logo.svg" alt="Egronode logo">
  </a>
</p>
<p align="center">Desktop PWA Ready Product Information Management Platform</p>

<p align="center">
  <a href="https://ergonode.com" target="_blank">
    <img src="https://img.shields.io/badge/version-0.6.0-4c9aff.svg" alt="Version">
  </a>
  <a href="https://ergonode.com" target="_blank">
    <img src="https://img.shields.io/badge/version%20code-Vegas-00bc87.svg" alt="Code Version">
  </a>
  <a href="https://ergonode.slack.com/join/shared_invite/enQtNjE2NTA2ODM2NzIwLWU5NzhmNGM5NDUyYTVlZTI0YWJmMTViYWEyYWU2NDc2NzU4Y2U4ZTc0OTUwYmY0ODVhNzA2ZGE5OTMwOWFlYmM">
     <img src="https://img.shields.io/badge/chat-on%20slack-e51670.svg" alt="Chat">
  </a>
  <a href="https://docs.ergonode.com" target="_blank">
    <img src="https://img.shields.io/badge/docs-read-ffc108.svg" alt="Docs">
  </a>
  <a href="https://github.com/ergonode/backend/blob/master/LICENSE.txt" target="_blank">
    <img src="https://img.shields.io/github/license/ergonode/backend.svg" alt="License">
  </a>
</p>

## Instalation

**1) Manual**

Download project repository (ergonode) to your local directory:
```
git clone git@github.com:ergonode/backend.git
```
Open your terminal in local project, and execute:
```
composer install
``` 
Add `.env.local` file and configure database connection
```
DATABASE_URL=pgsql://db_user:db_password@127.0.0.1:5432/db_name
```

Now you need generate jwt keys with command
```
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

While executing above commends you would be asked about password. This password needs to be saved then in `.env.local` file 
```
JWT_PASSPHRASE=yourpassword
```

In terminal execute command which configure application (Available phing commands):
```
bin/phing build
```

If you need basic data in terminal execute command:
```
bin/phing database:fixture
```
or more complex data:
```
bin/phing database:fixture:dev
```

Run build in server
```
bin/console s:r
```

Run [frontend application][frontend] and login with credentials `test@ergonode.com` password `abcd1234`.

**2) Using Docker**

Documentation how to install: [docker repository][docker]

## Documentation

The project is in early stage and we have got a lot of milestones to develop.  We do our best to deliver great documentation, but - to be honest -  it is the hardest thing in open-source projects :)

**Please find out what we've already prepared on [docs.ergonode.com][docs]**

#### Backend Technologies

- PHP 7.2
- Symfony 4.3
- Postgres 9.6 (uuid-ossp, ltree)
- RabbitMQ (optional)
- Redis (optional)
- Elasticsearch (optional)
- Nginx (possible Apache)
- MongoDB (optional)

#### Tests

- Phpunit
- Behat (API) 


#### Domain Driven Design Approach

- CQRS
- ES
- SAGA
- EVENT BUS

## Build with us community on Slack

If you have any questions or ideas feel free to join our [slack][slack].

## Is it production ready?

No! At the moment we have only one testing implementation to production environment (with more than 150k+ product indexes and integration with Magento Commerce 2.3), but in our opinion system still needs to be stabilised and we recommend not to use it at the moment in production mode. We still develop the core and there could be a lot of changes in the near future. If you want to know when it will be production ready look at Ergonode Roadmap. 


## Roadmap

If you would like to find the current and future milestones for our project go to our [Roadmap][roadmap] page.

At the moment we finalize development of Milestone 1 of the project. 

## Build Ergonode with us!

We are looking for Contributors: Back-end Dev, JS Devs, Tech Writers and Designers. Please read our [contribution rules][contribut] before making any pull request. If you have any questions or ideas feel free to join our [slack][slack] or send us an email: contributors@ergonode.com

## Partners

Ergonode is open-source, and it can be brought to you only by great community and partners supported by our core team. If you want to be on that list please send us an email: contributors@ergonode.com

## The license

Ergonode source code is released under the [OSL 3.0 License][license].

[discord]: https://discord.gg/NntXFa4
[slack]: https://ergonode.slack.com/join/shared_invite/enQtOTA2ODY0ODMxNTI0LThlZGE2YWE0YzY4NzU1ODk3NWRmNTJiMGI2NmM5ZTgxYTk0MWRhMjM1Y2M4MjdjZjAxY2FkOWE1M2FhZmJkMDY
[contribut]: http://docs.ergonode.com/#/contribution
[license]: ./LICENSE.txt
[roadmap]: https://ergonode.com/features/#roadmap
[docs]: https://docs.ergonode.com
[ddd]: https://en.wikipedia.org/wiki/Domain-driven_design
[cqrs]: https://en.wikipedia.org/wiki/Command%E2%80%93query_separation
[es]: https://dev.to/barryosull/event-sourcing-what-it-is-and-why-its-awesome
[frontend]: https://github.com/ergonode/frontend
[docker]: https://github.com/ergonode/docker
