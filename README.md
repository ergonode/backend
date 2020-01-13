<p align="center">
  <a href="https://vuejs.org" target="_blank" rel="noopener noreferrer">
    <img width="300" src="https://ergonode.com/wp-content/themes/ergonode/assets/img/logo.svg" alt="Egronode logo">
  </a>
</p>
<p align="center">Desktop PWA Ready Product Information Management Platform</p>

<p align="center">
  <a href="https://ergonode.com" target="_blank">
    <img src="https://img.shields.io/badge/version-0.5.0-4c9aff.svg" alt="Version">
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
<br>

## Welcome to the world of Ergonode :)

Ergonode is an open-source project of a new Product Information Management system (PIM). We have set ourselves the goal of building a completely new system from scratch, which will be able to easily out-of-the box handle 100k+ product references, and in addition will provide maximum user satisfaction with product management. 

It is a system that can be easily integrated with many popular e-commerce platforms (Magento, Shopware, PrestaShop, Shopify, Sylius, etc.).

Ergonode will be always full open-source project. Currently, the system is in the phase of product catalog stabilization and import. We invite all developers who want to develop a new quality of shopping experience with us, we are open to contributors and support, because the project is not developed by large team (we are small but  extraordinary team of designers, programmers and product managers).  

If you are a new developer and need help, feel-free to ask questions our core team on our [slack][slack]:

`@Marcin Piwowarczyk` (product vision and strategy)

`@Rafal Przedzik` (backend architecture)

`@Sebastian Bielawski` (backend integrations)

`@Maciej Kaczorowski` (frontend architecture)

`@Piotr Bletek` (frontend architecture)

`@Mateusz Kolankowski` (documentation and backend support)

`@Marcin Warzynski` (product requirements management)

**We are looking for core team members who willing to support us and help Ergonode awesome software.** Interested? Contact to @Marcin Piwowarczyk on slack.

At the moment we prepare full list contribution rules, to support devs before making any pull request. Pull request that doesn't meet these rules will not be merged and included to core functions. 

## Ergonode awesome functions 

#### Grid Management

Ergonode is first and foremost an efficient environment for managing products from the list level. The solution enables you to filter and edit hundreds of products at once at any time, thanks to which mass content editing becomes pleasant and convenient. The system is easy to use, yet it has the capabilities of a spreadsheet. 

In Ergonode you can edit and translate products information directly from spreadsheet view. Moving around cells is now fast and comfortable by supporting simple keyboard shortcuts.

![](https://ergonode.com/wp-content/uploads/grid-management.jpg)

#### Filtering products

Ergonode lets you to easily manage spreadsheet columns by simple drag and drop actions. The system allows you to adjust the appearance of the products spreadsheet to suit their needs and preferences. 

The solution allows you to filter products information at any time by choosing any attributes and preferences you want.

![](https://ergonode.com/wp-content/uploads/filtering-productslters.jpg)

#### Product Template Designer

Ergonode allows you to create any feature describing the product. You can create attributes that are text fields for filling in, single and multi-selection fields, as well as complex features such as price or unit. 

All of this, included in a simple and intuitive wizard, allows you to create any structure of the product sheet.

![](https://ergonode.com/wp-content/uploads/template-designer.jpg)

## System Architecture

From the very beginning, Ergonode was designed to provide maximum satisfaction not only for product and conent managers, but especially for developers. 

The system architecture was built entirely based on **[Domain Driven Design][ddd]** approach using **[Command Query Responsibility Segregation (CQRS)][cqrs]** and **[Event Sourcing][es]**. 

Thanks to this, we have gained unprecedented flexibility in managing every element of the system from the moment of its creation and a trouble-free division/exchange of databases in individual areas of the system.

The system consists of two main independent Backend and Frontend applications, which communicate with each other through the RestAPI interface. 

On the front side we've used headless approach with Vue.js application. Thanks to that we have gained an increase in the performance and speed of the application, as well as the readiness to fully adapt the system as a Desktop Progressive Web App.  

![](https://ergonode.com/wp-content/uploads/2-1024x788.png)


#### Backend Technologies

- PHP 7.2
- Symfony 4.3
- Postgres 9.6 (uuid-ossp, ltree)
- RabbitMQ (optional)
- Redis (optional)
- Elasticsearch (optional)
- Nginx (possible Apache)
- MongoDB (optional)

#### Front-End Technologies

- Vue.js
- Nuxt.js
- Node.js
- SASS
- Axios
- BEM (CSS)
- ESLint (Airbnb standard)

#### Tests

- Phpunit
- Behat (API) 
- JestJS

#### Domain Driven Design Approach

- CQRS
- ES
- SAGA
- EVENT BUS
  
## Instalation

Download project repository (ergonode) to your local directory:
```
git clone git@github.com:ergonode/backend.git
```
Open your terminal in local project, and execute:
```
composer install
``` 
In .env.local file you need to configure database connection
```
DATABASE_URL=pgsql://db_user:db_password@127.0.0.1:5432/db_name
```

Now you need generate jwt keys with command
```
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

While executing above commends you would be asked about password. This password needs to be saved then in .env.local file in line `JWT_PASSPHRASE=yourpassword`


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

To create user execute console command
```
bin/console ergonode:user:create email name surname password language_code [role]
```
 > eg. *bin/console ergonode:user:create test@ergonode.com John Snow 123 EN*

Run build in server
```
bin/console s:r
```

Run frontend application and login with credentials given in above user creating command.      

## Build with us community on Slack

If you have any questions or ideas feel free to join our [slack][slack].

## Is it production ready?

No! At the moment we have only one testing implementation to production environment (with more than 150k+ product indexes and integration with Magento Commerce 2.3), but in our opinion system still needs to be stabilised and we recommend not to use it at the moment in production mode. We still develop the core and there could be a lot of changes in the near future. If you want to know when it will be production ready look at Ergonode Roadmap. 

## Documentation

The project is in early stage and we have got a lot of milestones to develop.  We do our best to deliver great documentation, but - to be honest -  it is the hardest thing in open-source projects :)

**Please find out what we've already prepared on [docs.ergonode.com][docs]**

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
[slack]:  https://join.slack.com/t/ergonode/shared_invite/enQtNzEwMjkwOTQwOTM0LWQ4YWYwMWJmZGExYzQxMGMwZTFjMjg4MjQyNmU3NjY1MWU1OTE1MjA2N2ExMzA1MGQ1ZDJjMzkxMTQzNTBmMGM
[contribut]: http://docs.ergonode.com/#/contribution
[license]: ./LICENSE.txt
[roadmap]: https://ergonode.com/features/#roadmap
[docs]: https://docs.ergonode.com
[ddd]: https://en.wikipedia.org/wiki/Domain-driven_design
[cqrs]: https://en.wikipedia.org/wiki/Command%E2%80%93query_separation
[es]: https://dev.to/barryosull/event-sourcing-what-it-is-and-why-its-awesome
