## Guideline

### Setup & Install

##### Install Composer, Laravel, Redis, Nodejs, NPM (depend on OS, you can google it)

##### Setup Laravel project:

Copy .env.example file to .env and edit following lines:
```
DB_DATABASE=realtime_app_db
DB_USERNAME=root
DB_PASSWORD=db_password // use your real mysql username & password
...

TIME_INTRO=init_time_intro
TIME_QUIZ=init_time_quiz
TIME_DISCUSS=init_time_discuss // these values is used as default value
...

ADMIN_PASS=admin_pass_here // admin pass used for seed file, because I dont deploy any function to change password
...

REDIS_SERVER=redis_server_address // address of your server, this will be used by client

```

Continue setup by using following command:

```
npm install
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
```

##### Run server
On server, run following commands:

```
node socket.js
sudo service redis start
```
If you build redis-server from source, you can run `redis-server` to start `redis-server`

In case of local test, you can use command `php artisan serve` to start server. In other cases, you need and background service for server, like Lampp or Homestead.

### Usage

##### For Manager:

+ Login by address: `http://<server_address>/auth/login`

+ Using username is `admin@quiz.com` and password you set in `.env` file to login

+ In dashboard screen, you can see some manager function:

> Setup current game

> Create new game

> View current game and some abstract information about all game you created

+ After creating game and setup current game, you can go to game screen by address: `http://<server_address>/manager/scene`. In this screen, you can see some select

> Start round 1

> Start round 2

> Start round 3

##### For Client:

When Manager created game, he/she created account for client too. Client can use this account for login game

+ Go to address: `http://<server_address>/auth/login` to login

+ After loging in, press `Go` to go to quiz game screen

+ In this screen, when Manager start quiz, Client will see question and answers to choose.
