<div style="display:flex; align-items: center">
  <h1 style="position:relative; top: -6px" >Epic Movie Quotes API</h1>
</div>

---

This project represents an API of Epic movie quotes, application developed during redberry bootcamp 2023

#

### Table of Contents

-   [Prerequisites](#prerequisites)
-   [Tech Stack](#tech-stack)
-   [Getting Started](#getting-started)
-   [Migrations](#migration)
-   [Development](#development)
-   [Database relations](#database-relations)

#

### Prerequisites

-   <img src="readme/assets/php.svg" width="35" style="position: relative; top: 4px" /> *PHP@8.2 and up*
-   <img src="readme/assets/mysql.png" width="35" style="position: relative; top: 4px" /> _MYSQL@8 and up_
-   <img src="readme/assets/npm.png" width="35" style="position: relative; top: 4px" /> _npm@6 and up_
-   <img src="readme/assets/composer.png" width="35" style="position: relative; top: 6px" /> _composer@2 and up_

#

### Tech Stack

-   <img src="readme/assets/laravel.png" height="18" style="position: relative; top: 4px" /> [Laravel@10.x](https://laravel.com/docs/10.x) - back-end framework
-   <img src="readme/assets/tailwind-css-logo-vector.png" height="18" style="position: relative; top: 4px" /> [Tailwind CSS v.3.0](https://tailwindcss.com/docs/installation) - CSS framework
-   <img src="readme/assets/spatie.png" height="19" style="position: relative; top: 4px" /> [Spatie Translatable](https://github.com/spatie/laravel-translatable) - package for translation

#

### Getting Started

1\. First of all you need to clone E Space repository from github:

```sh
git clone https://github.com/RedberryInternship/levan-kereselidze-epic-movie-quotes-back
```

2\. Next step requires you to run _composer install_ in order to install all the dependencies.

```sh
composer install
```

3\. after you have installed all the PHP dependencies, it's time to install all the JS dependencies:

```sh
npm install
```

and also:

```sh
npm run dev
```

in order to build your JS/SaaS resources.

4\. Now we need to set our env file. Go to the root of your project and execute this command.

```sh
cp .env.example .env
```

And now you should provide **.env** file all the necessary environment variables:

#

**MYSQL:**

> DB_CONNECTION=mysql

> DB_HOST=127.0.0.1

> DB_PORT=3306

> DB_DATABASE=**\***

> DB_USERNAME=**\***

> DB_PASSWORD=**\***

#

**GMAIL SERVICE:**

> MAIL_MAILER=smtp

> MAIL_HOST=smtp.gmail.com

> MAIL_PORT=465

> MAIL_USERNAME=\***\*\*\*\*\*\*\***

> MAIL_PASSWORD=\***\*\*\*\*\*\*\***

#

**PUSHER:**

> PUSHER_APP_ID=**\***

> PUSHER_APP_KEY=\***\*\*\*\*\*\*\***

> PUSHER_APP_SECRET=\***\*\*\*\*\*\*\***

> PUSHER_HOST=

> PUSHER_PORT=**\***

> PUSHER_SCHEME=**\***

> PUSHER_APP_CLUSTER=**\***

#

**GOOGLE AUTH SERVICE (LARAVEL SOCIALITE):**

> GOOGLE_CLIENT_ID=\***\*\*\*\*\*\*\***

> GOOGLE_CLIENT_SECRET=**\*\*\*\*\*\*\*\***

#

```sh
php artisan config:cache
```

in order to cache environment variables.

4\. Now execute in the root of you project following:

```sh
  php artisan key:generate
```

Which generates auth key.

#

### Migration

if you've completed getting started section, then migrating database if fairly simple process, just execute:

```sh
php artisan migrate
```

after migrating database, you can use seeders. execute:

```sh
php artisan db:seed
```

The project includes predefined Genres. If you want to generate fake data along with the Genres, you can use the provided artisan command. However, if you only need the Genres without any additional data, you can execute the command Below:

```sh
php artisan db:seed --class=GenreSeeder
```

#

### Development

You can run Laravel's built-in development server by executing:

```sh
  php artisan serve
```

when working on JS you may run:

```sh
  npm run dev
```

For deploying vite & tailwind styles

#

### Database Relations

SQL database tables & relations

<br>

[Database Relations](./readme/assets/database-tables.png)

</br>
