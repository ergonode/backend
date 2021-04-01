<p align="center" style="padding: 20px; background: #252832">
  <a href="https://ergonode.com" rel="noopener noreferrer">
    <img width="300" src="./ergonode.svg" alt="Egronode logo">
  </a>
</p>
<p align="center">Modern Product Information Management Platform</p>

<p align="center">
  <a href="https://ergonode.com" target="_blank">
    <img src="https://img.shields.io/badge/version-1.0.0--rc.2-4c9aff.svg" alt="Version">
  </a>
  <a href="https://join.slack.com/t/ergonode-community/shared_invite/zt-ibppxnyc-4Ykac1Gh64Qkk5SWy3sg3w">
     <img src="https://img.shields.io/badge/chat-on%20slack-e51670.svg" alt="Chat">
  </a>
  <a href="https://docs.ergonode.com" target="_blank">
    <img src="https://img.shields.io/badge/docs-read-ffc108.svg" alt="Docs">
  </a>
  <a href="https://github.com/ergonode/backend/blob/master/LICENSE.txt" target="_blank">
    <img src="https://img.shields.io/github/license/ergonode/backend.svg" alt="License">
  </a>
</p>

Ergonode is modern PIM platform based on Symfony and Vue.js frameworks. It has modular structure and gives power of flexibility and easy management thousand of products. More info how to use it for merchants and users: https://userguide.ergonode.com

## Installation

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
bin/console server:run
```

For more information check our [installation][installation] section in the documentation.

## Documentation

The project is in early stage and we have got a lot of milestones to develop.  We do our best to deliver great documentation, but - to be honest -  it is the hardest thing in open-source projects :)

**Please find out what we've already prepared on [docs.ergonode.com][docs]**

#### Backend Technologies

- PHP 7.4
- Symfony 4.4
- Postgres 10
- RabbitMQ

#### Tests

- Phpunit
- Behat (API) 

## Build with us community on Slack

If you have any questions or ideas feel free to join our [slack][slack].

## Is it production ready?
We're releasing 1.0.0-rc so there are some minor bugs and improvements that need to be eliminated before the stable version 1.0. Follow our SM channels and take a look at Ergonode Roadmap to see what's coming in the future. 

## Roadmap

If you would like to find the current and future milestones for our project go to our [Roadmap][roadmap] page.
 

## Build Ergonode with us!

We are looking for Contributors: Back-end Dev, JS Devs, Tech Writers and Designers. Please read our [contribution rules][contribut] before making any pull request. If you have any questions or ideas feel free to join our [slack][slack] or send us an email: contributors@ergonode.com

## Partners

Ergonode is open-source, and it can be brought to you only by great community and partners supported by our core team. If you want to be on that list please send us an email: contributors@ergonode.com

## The license

Ergonode source code is released under the [OSL 3.0 License][license].

[slack]: https://join.slack.com/t/ergonode-community/shared_invite/zt-ibppxnyc-4Ykac1Gh64Qkk5SWy3sg3w
[contribut]: http://docs.ergonode.com/#/community/contribution
[license]: ./LICENSE.txt
[roadmap]: https://trello.com/b/DSWyaTlL/ergonode-product-roadmap
[docs]: https://docs.ergonode.com
[installation]: https://docs.ergonode.com/#/installation
[ddd]: https://en.wikipedia.org/wiki/Domain-driven_design
[cqrs]: https://en.wikipedia.org/wiki/Command%E2%80%93query_separation
[es]: https://dev.to/barryosull/event-sourcing-what-it-is-and-why-its-awesome
[frontend]: https://github.com/ergonode/frontend
[docker]: https://github.com/ergonode/docker
