# Chronicle
```
composer require pderas/chronicle
```

### Table Of Contents
1. [About](#about)
2. [Installation](#installation)
3. [Requirements](#requirements)
4. [Instructions](#instructions)
5. [Usage](#usage)
6. [Properties](#properties)
7. [License](#license)

## About
This package is designed for Laravel that lets users interact with eachother using notes that allow for file uploads and comments. Notes are tracked and held in sections and can have multiple files/comments attached.

## Installation
#### Requirements
To use this package, the following requiremenst must be met:
- [Composer](https://getcomposer.org/)
- [Laravel](https://laravel.com/) (5.3+)
- [Carbon](https://carbon.nesbot.com/)

#### NPM Packages
- [Vuejs](https://vuejs.org/) (v2)
- [Axios](https://github.com/axios/axios)

#### Current Front End Limitations
As the package is still being developed, not all features are currently available. For now, it is recommended you disable the features in the database by setting the booleans accordingly on the sections you are using.
- Attachments
- Comments
- Private / Public Differentiation

#### Instructions
Once you have succesfully required the package, (v5.3 only) you must register the service provider in your config/app.php file.
```
Pderas\Chronicle\ChronicleServiceProvider::class,
```

After you have registered the package you can now publish the associated files. This will publish the config file as well as some default vue components
```
php artisan vendor:publish --provider="Pderas\Chronicle\ChronicleServiceProvider"
```

Before you run the migrations, you can look at the `config/chronicle.php` file and update the values and models as needed, or just use the defaults. Then you may run the migration command to set up all the required tables:
```
php artisan migrate
```

## Usage
### Back End
To use chronicle you must make a section in the database. To help set up a quick section, you can use this command:
```
php artisan chronicle:section
```
The tag you specified will be used in the front end. You will also need a reference slug for the front end. Think of the reference slug as a 'sub-section' of a section. So basically when you create a section, you can have many notes within the section. However, notes are assigned a reference slug, and that reference slug determines which notes will be shown.

#### Example: You create a chronicle section with the tag 'user-notes'
Rather than making a new section for each different user, you simply set the ref-slug to be that users id, or some unique slug for that user. Now when a user looks at that section, they will only see notes with the same ref slug. (aka - their id/slug).

### Front End

Register chronicle as a component in your vue instance.
```javascript
Vue.component('chronicle', require('./components/chronicle/Chronicle'));
```

Use the component anywhere in your app instance. If you do not pass in a user, chronicle will display as 'read only'.
```html
<chronicle tag='my-section' ref-slug="customer-reference-slug" :user="{{ json_encode(Auth::user()) }}"></chronicle>
```

#### Properties
| Property          | Required | Type    | Default      | Description                                                     |
|-------------------|----------|---------|--------------|-----------------------------------------------------------------|
| tag               | true     | String  | n/a          | The tag of the section to show                                  |
| ref-slug          | true     | String  | n/a          | A ref slug for a section, only shows notes with this ref slug   |
| load-font-awesome | false    | Boolean | true         | Boolean to toggle auto load of font awesome on or off           |
| show-display      | false    | Boolean | true         | Shows the display                                               |
| show-input        | false    | Boolean | true         | Shows the input / add note button                               |
| show-pages        | false    | Boolean | true         | Turns off pagination in the title slot                          |
| show-title        | false    | Boolean | true         | Turns off title in the title slot (pages will be shown instead) |
| use-font-awesome  | false    | Boolean | true         | Use font awesome for buttons                                    |
| user              | false    | Object  | new Object() | A logged in user object                                         |


## License
This project is covered under the MIT License. Feel free to use it wherever you like.
