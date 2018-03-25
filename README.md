# Chronicle
```
composer require codymoorhouse/chronicle:0.0.0-dev
```

### Table Of Contents
1. [About](#about)
2. [Installation](#installation)
3. [Requirements](#requirements)
4. [Instructions](#instructions)
5. [Usage](#usage)
6. [License](#license)

## About
This package is designed for Laravel that lets users interact with eachother using notes that allow for file uploads and comments. Notes are tracked and held in sections and can have multiple files/comments attached.

## Installation
#### Requirements
To use this package, the following requiremenst must be met:
- [Composer](https://getcomposer.org/)
- [Laravel](https://laravel.com/) (5.3+)

#### Instructions
Once you have succesfully required the package, you must register the service provider in your config/app.php file.
```
CodyMoorhouse\Chronicle\ChronicleServiceProvider::class,
```

After you have registered the package you can now publish the associated files.
```
php artisan vendor:publish --provider="CodyMoorhouse\Chronicle\ChronicleServiceProvider"
```

Before you run the migrations, you can look at the `config/chronicle.php` file and update the values and models as needed, or just use the defaults. Then you may run the migration command to set up all the required tables:
```
php artisan migrate
```

## Usage
Under construction

## License
This project is covered under the MIT License. Feel free to use it wherever you like.