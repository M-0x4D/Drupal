
# Drupal-Task

Make sure you have installed php version 8.2 and mysql version 8.0

before we start take into consideration this CMS has a strong cache and if you try something and does not work you should run this command 

```bash
./vendor/bin/drush cr
```

## Deployment

To deploy this project run

1- clone the app from github
```bash
https://github.com/M-0x4D/Drupal.git
```

2- Run this command

```bash
composer install
```

3- Create a new database in your database system for example name it "drupal"

4- then start your server on port "4444" using this command

```bash
php -S localhost:4444
```

5 - open localhost:444 in your browser and flow the steps to install the app and put your db credentials while installing it.

6 - final step you should enable custom module using this command

```bash
./vendor/bin/drush en event_management
```

  - hint : if you need to uninstall our module run this command

```bash
./vendor/bin/drush pmu event_management
```
  - another hint if you get Acess denied in admin routes make sure that the admin credentials [name , pass] exists in "user_field_data" table to open admin routes and works with you.
AAAAAANNNNNNNDDDD gues what ? whe did it . now open any route from routing yml file it will works with you.
## Authors

- [@MohamedAdel](https://www.github.com/m-0x4d)
