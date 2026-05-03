

## Setup


- composer install
- cp .env.example .env
- php artisan key:generate
- php artisan migrate --seed
- php artisan jwt:secret 
- php artisan storage:link 
- set APP_LINKED_LOCAL_STORAGE to false if storage:link is not desired in -> .env file. 
- Add Role_management to admin to check role permission
