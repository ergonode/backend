# Ergonode Contributing Guide

Yoo! We're really excited that you are interested in contributing to Ergonode. 
As a contributor, here are the guidelines we would like you to follow:

* [Code of Conduct](#coc)
* [Question or Problem?](#question)
* [Issues and Bugs](#issue)
* [Coding Rules](#rules)
* [Pull Request Guidelines](#submit-pr)
* [Development Setup](#development)
* [Commonly used NPM scripts](#commonly)

## <a name="coc"></a> Code of Conduct
Please read and follow our [Code of Conduct][coc].


## <a name="question"></a> Got a Question or Problem?

**Do not open issues for general support questions as we want to keep GitHub issues for bug reports and feature requests.** 
You've got much better chances of getting your question answered on our [discord][discord] channel.


## <a name="issue"></a> Found a Bug?

If you find a bug in the source code, you can help us by [submitting an issue](#submit-issue) to our [GitHub Repository][github].

## <a name="rules"></a> Coding Rules

To ensure consistency throughout the source code, keep these rules in mind as you are working:

* All features or bug fixes **must be tested** by one or more specs (unit-tests).


## <a name="submit-pr"></a> Pull Request Guidelines

- The `master` branch is a current active branch.

- Checkout a feature branch from `master`, and create Pull Request back against it once your changes are ready.

- It's OK to have multiple small commits as you work on the PR - we will squash them on merge.

- Make sure `bin/phing test:unit` passes. (see [development setup](#development))

- If adding a new feature:
  - Add accompanying test case.
  - Provide a convincing reason to add this feature. Ideally, you should open a suggestion issue first and have it approved before working on it.

- If fixing bug:
  - If you are resolving a special issue, add `(fix #xxxx[,#xxxx])` (#xxxx is the issue id) in your PR title for a better release log, e.g. `update entities encoding/decoding (fix #1234)`.
  - Provide a detailed description of the bug in the PR. Live demo preferred.
  - Add appropriate test coverage if applicable.

## <a name="development"></a> Development Setup


1. After cloning the repo, run:

```
composer install
``` 

2) In .env file you need to configure database connection.

```
DATABASE_URL=pgsql://db_user:db_password@127.0.0.1:5432/db_name
```

3) Generate keys.

```
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

4) While executing above commends you would be asked about password. This password needs to be saved then in .env file in line JWT_PASSPHRASE=yourpassword

5) In terminal execute command which configure application.

```
bin/phing build
```

6) Execute create user console command

```
bin/console ergonode:user:create email name surname password language_code
```
 > eg. *bin/console ergonode:user:create test@ergonode.com John Snow 123 EN*

Run build in server

```
bin/console s:r
```

There are some other scripts available in the `build.xml` file.

## Credits

Thank you to all the people who have already contributed to Ergonode!

[coc]: ./CODE_OF_CONDUCT.md
[github]: https://github.com/ergonode/backend
[submit-issue]: https://github.com/ergonode/backend/issues
[discord]: https://discord.gg/NntXFa4
