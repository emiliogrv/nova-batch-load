# A Laravel Nova XLS &amp; CSV importer

[![Latest Version on Packagist](https://img.shields.io/packagist/v/emiliogrv/nova-batch-load.svg?style=flat-square)](https://packagist.org/packages/emiliogrv/nova-batch-load)
[![Total Downloads](https://img.shields.io/packagist/dt/emiliogrv/nova-batch-load.svg?style=flat-square)](https://packagist.org/packages/emiliogrv/nova-batch-load)

## Description

This Package allow you to create many new entries at once, loading a file from creating resource screen.

Validation, fields and creating events are same that you declared into yours Resource's fields function.

## Install

```
composer require emiliogrv/nova-batch-load
```

## Usage

```php
// in your Nova's Resources

// ...
use Emiliogrv\NovaBatchLoad\BatchLoadField;
// ...

public function fields(Request $request)
{
    return [
        // ...

        BatchLoadField::make()
            ->accept('.xlsx') // Optional
            ->defaultTabActive(1) // Optional
            ->keepOriginalFields('belongs|select|boolean'), // Optional

        // ...
    ];
}
```

## i18n

To translate add this to your `resources/lang/vendor/nova/xx.json`

```json
    "File empty!": "Archivo vacío",
    "Load per file": "Carga por archivo",
    "Manual loading": "Carga manual",
    "Upload file": "Subir archivo",
    "Upload file & Add Another": "Subir archivo y Añadir Otro"
```

## API

| Function             | Type      | Default             | Description                                                |
| -------------------- | --------- | ------------------- | ---------------------------------------------------------- |
| `accept`             | `String`  | `.xlsx, .xls, .csv` | Set the accepted file formats by extensions.               |
| `defaultTabActive`   | `Integer` | `0`                 | Set the tab default when the component is mounted.         |
| `keepOriginalFields` | `String`  | `belongs|morph`     | Set which fields keep as original Nova format and options. |

## Screenshots

![Screenshot from 2019-07-05 15 12 44](https://user-images.githubusercontent.com/13983577/60739532-a82cbd00-9f38-11e9-90bf-879d112a7669.png)
![Screenshot from 2019-07-05 15 13 12](https://user-images.githubusercontent.com/13983577/60739533-a82cbd00-9f38-11e9-8580-e007f37001be.png)
![Screenshot from 2019-07-05 15 13 56](https://user-images.githubusercontent.com/13983577/60739534-a82cbd00-9f38-11e9-9625-276ce2799ce7.png)

## Contributing

Thank you for considering contributing! Please leave your [PR](https://github.com/emiliogrv/nova-batch-load/pulls) or [issue](https://github.com/emiliogrv/nova-batch-load/issues).

## Tested with Laravel Nova 2.\*

# License

[MIT](https://opensource.org/licenses/MIT)
