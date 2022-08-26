# CodeIgniter 4 QueryBuilder Batch Sample

This is a sample to upload a CSV file and import to the database
using `QueryBuilder::insertBatch()` or `updateBatch()`.

## Requirements

- MySQL
- PHP 7.4 to 8.1

## Setup

```console
$ cd ci4-qb-batch-sample/
$ composer install
$ php spark migrate
```

```console
$ php spark serve
```

Navigate to http://localhost:8080/
