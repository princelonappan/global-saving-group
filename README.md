## Overview

This application provides the following features.

- Voucher Management
- Order Management

## Requirements and dependencies

- PHP >= 7.2
- Symfony CLI version  v4.28.1

## Features

- This system allows the following features to perform through the REST API.
- Option to add/edit/delete the voucher
- In the voucher listing, we can pass the type parameter as (active/expired) to get the list
- Edit and Delete option are applicable for the voucher, when the voucher is not expired or not used.
- Option to create the order with or without voucher. 
- List all the orders with pagination format.

## Installation

First, clone the repo:
```bash
$ git clone https://github.com/princelonappan/global-saving-group.git
```
#### Running as a Docker container

The following docker command will run the application.

```
$ cd global-saving-group
$ docker-compose up -d
```
#### Install dependencies
```
$ change the database configuration in the .env file
```
#### Run the following commands to migrate the database change
```
$ php bin/console doctrine:migrations:migrate
$ copy the database with '_test' name and create for testing purpose
```
This will start the application.

#### Run API Swagger

You can access the Swagger API through the following end point. <br />
```{{ base_url}}/api/doc```

#### Run Test

```
$ php bin/phpunit
```
