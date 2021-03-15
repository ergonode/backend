# Ergonode - Mailer

## Documentation

* Follow link to [Ergonode Documentation][docs],

### Message template translation

We can't change locale in Twig template (https://github.com/symfony/symfony/issues/35925).
For now we can only set locale in translator and after all change it again

### Message subject translation

You must set correct translation in `MailMessage` object. We don't translate it automatically.

### Attachments

In first version we don't need to make it. That functionality will be developed in next version. 

## Tips

### How to test DSN configuration?

U can easly check DSN configuration by sending test e-mail with command:

```
bin/console ergonode:mailer:test team@ergonode.com -l en_US
```

## Community

* Get Ergonode support on **Stack Overflow**, [Slack][slack] or email team@ergonode.com
* Follow us on [GitHub][github], [Twitter][twitter] and [Facebook][facebook],  

## Contributing

Ergonode is an Open Source. Join us as a [contributor][contribution]. 

## About Us

Ergonode development is lead by **Ergonode Core Team** and supported by Ergonode contributors. 

[docs]: https://docs.ergonode.com
[slack]: https://ergonode-community.slack.com
[twitter]: https://twitter.com/ergonode
[facebook]: https://www.facebook.com/ergonode
[github]: https://github.com/ergonode
[license]: ./LICENSE.txt
[contribution]: http://docs.ergonode.com/#/community/contribution