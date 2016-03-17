# What is this?

Simple JSON API which is build on top of [Silex](http://silex.sensiolabs.org/) framework.

## Main points
* This is just an API, nothing else
* Only JSON responses from API
* JWT authentication

### TODO
- [x] Configuration for each environment and/or developer
- [x] Authentication via JWT
- [x] "Automatic" API doc generation
- [x] Database connection (Doctrine dbal + orm)
- [x] Console tools (dbal, migrations, orm)
- [x] Docker support
- [ ] 
- [ ] 
- [ ] 


## Requirements
* PHP 5.5.x
* Apache / nginx / IIS / Lighttpd see configuration information [here](http://silex.sensiolabs.org/doc/web_servers.html) 

## Development
* Use your favorite IDE and get checkout from git
* Open terminal, go to folder where you make that checkout and run following commands

```bash
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar install
```

## Installation
* Open terminal and clone this repository to your server
* Create web-server configuration which points to ```web``` folder
* Ensure that ```var``` folder is writable by web-server user

## Configuration
TODO

## Nice to know things
```GET http://yoururl/_dump```
* generates pimple.json for autocomplete 
* See [this](https://github.com/Sorien/silex-pimple-dumper) for more info

```./bin/console orm:convert:mapping --from-database --namespace="App\\Entities\\" annotation ./src``` 
* Generate Doctrine entities from database

## Contributing
Please see the [CONTRIBUTING.md](CONTRIBUTING.md) file for guidelines.

## Author
[Tarmo Leppänen](https://github.com/tarlepp)

## LICENSE

[The MIT License (MIT)](LICENSE)

Copyright (c) 2016 Tarmo Leppänen
