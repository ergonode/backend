# Ergonode - Mailer

## Documentation

* Follow link to  [**Ergonode Documentation**](https://docs.ergonode.com),

## Cons

### Message template translation

We can't change locale in Twig template (https://github.com/symfony/symfony/issues/35925).
For now we can only set locale in translator and after all change it again

### Message subject translation

You must set correct translation in `MailMessage` object. We don't translate it automatically.

### Attachments

In first version we don't need to make it. That functionality will be develop in next version. 

## Tips

### How to test DSN configuration?

U can easly check DSN configuration by sending test e-mail with command:

```
bin/console ergonode:mailer:test team@ergonode.com -l en_US
```

## Community

* Get Ergonode support on **Stack Overflow**, [**Slack**](https://ergonode.slack.com) and [**email**](team@ergonode.com).
* Follow us on [**GitHub**](https://github.com/ergonode), [**Twitter**](https://twitter.com/ergonode) and [**Facebook**](https://www.facebook.com/ergonode),  

## Contributing

Ergonode is a Open Source. Join us as a contributor.

## About Us

Ergonode development is sponsored by Bold Brand Commerce Sp. z o.o., lead by **Eronode Core Team** and supported by Ergonode contributors. 
