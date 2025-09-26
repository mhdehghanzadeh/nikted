# Nikted Order Fulfillment System

A demo application created with Lravel11 + Inertia.js + Tailwindcss.

![](https://media.licdn.com/dms/image/v2/D4E3DAQE3OKE1K88Jyw/image-scale_191_1128/image-scale_191_1128/0/1735641575133/nikted_cover?e=2147483647&v=beta&t=RJ6LdcSl3v8VpR0mw9hdWmqTN9rAPxCSxgCjVbUOlpA)

## Installation

Clone the repo locally:

```sh
git clone https://github.com/mhdehghanzadeh/nikted.git nikted
cd nikted
```


Run App  With Docker:

```sh
docker compose up -d
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed --force
```

Run App Normal:

```sh
composer install
php artisan migrate
php artisan db:seed
php artisan serve
php artisan queue:work
```

 

You're ready to go! Visit localhost:8000 in your browser, and login with:

Users:
- **Username:** nikted@info.com
- **Username:** sales_manager@nikted.com
- **Username:** warehouse_manager@nikted.com
- **Username:** logistics_manager@nikted.com
- **Password:** secret
 


Users:
- **Event and Listener** Use Event and Listener For Send SMS Notification When Order Status Changed
- **Notifications** Use Ghasedak SMS
- **Queues and Jobs:** Use For Generate Random Order Every Hour

 
