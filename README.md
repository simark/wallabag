[![Build Status](https://travis-ci.org/wallabag/wallabag.svg?branch=v2)](https://travis-ci.org/wallabag/wallabag)
[![Code Coverage](https://scrutinizer-ci.com/g/wallabag/wallabag/badges/coverage.png?b=v2)](https://scrutinizer-ci.com/g/wallabag/wallabag/?branch=v2)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/wallabag/wallabag/badges/quality-score.png?b=v2)](https://scrutinizer-ci.com/g/wallabag/wallabag/?branch=v2)

# What is wallabag?
wallabag is a self hostable application allowing you to not miss any content anymore.
Click, save and read it when you can. It extracts content so that you can read it when you have time.

More informations on our website: [wallabag.org](http://wallabag.org)

# Want to test the v2?

Keep in mind it's an **unstable** branch, everything can be broken :)

If you don't have it yet, please [install composer](https://getcomposer.org/download/). Then you can install wallabag by executing the following commands:

```
composer create-project wallabag/wallabag wallabag 2.0.0-alpha.1
cd wallabag
php app/console wallabag:install
php app/console server:run
```

## License
Copyright © 2013-2015 Nicolas Lœuillet <nicolas@loeuillet.org>
This work is free. You can redistribute it and/or modify it under the
terms of the MIT License. See the COPYING file for more details.
