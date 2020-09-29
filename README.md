Learning App API
===============

REST API for Learning App project.

API endpoints provide data in JSON format only.

Setup
------------

* Clone the repository
* Open the project folder:
```bash
cd learning-app-api/
```

* Run the Symfony local server:
```bash
symfony server:start
```

* Install project dependencies with [Composer](https://getcomposer.org/):
```bash
composer install
```

Documentation
-------------

[WIP] View the documentation by following the local server link:
```
https://127.0.0.1:8000/api/doc
```
>  Warning: Documentation in under development, params and response example missing.

**Example Error Message:**

```
Status: 404 Not Found
```
```php
{
    "status": "error",
    "message": "Entity \"Word\" with ID 1 not found."
}
```

Extending Project
-----

You can extend words import service and add supported file types for word lists uploader by implementing `WordsImportServiceInterface` interface.

```php
namespace App\Service\WordsImport;

final class NewImportService extends AbstractImportService implements WordsImportServiceInterface
{
    public function getData(string $filePath): ?iterable
    {
        // do some logic to extract words data from file

        return $data;
    }

    public static function getServiceKey(): string
    {
        return 'importer_'.$importedFileType;
    }

}
```