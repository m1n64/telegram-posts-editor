## MVP | WIP

Telegram editor for create posts in channels (with markdown editor)

## Laravel 11 + Octane (swoole) + sail + Reverb template

Stack: PHP 8.2, Laravel 11 (Octane, Sanctum, Sail, Reverb, internachi/modular, Inertia.js (React.js + tailwindcss)), PostgreSQL 15, Vite (node.js 20), Redis, mailhog, adminer

### Startup:
```bash
git clone https://github.com/m1n64/telegram-posts-editor.git
```
Next,
```bash
cd telegram-posts-editor
```
```bash
cp .env.example .env
```
```bash
chmod 755 ./sail
```
```bash
./sail -f docker-compose.yml -f docker-compose.dev.yml up -d --build
```
```bash
./sail composer install
```
```bash
./sail npm i
```
```bash
./sail artisan key:generate
```
```bash
./sail artisan migrate
```
```bash
./sail artisan storage:link
```
```bash
./sail npm run build
```
Reload app:
```bash
./sail stop
```
```bash
./sail -f docker-compose.yml -f docker-compose.dev.yml up -d
```

App successfully installed!

App url: [http://localhsot](http://localhost)

Mailhog url: [http://localhsot:8025](http://localhost:8025)

Adminer url: [http://localhsot:1337](http://localhost:1337) (login `sail`, pass `password`, driver `PostgreSQL`)

***

## DEMO



https://github.com/m1n64/telegram-posts-editor/assets/24874264/a8116150-841d-4341-b2b9-c81ed8b9d12b

### Ver 2
Table view for posts
![image](https://github.com/m1n64/telegram-posts-editor/assets/24874264/7e8f02fe-2919-4513-b8d8-58afc2b5331a)

Server notifications
![image](https://github.com/m1n64/telegram-posts-editor/assets/24874264/d95b5a0b-c2c2-45a8-9a14-786bd0b9e77b)

Table for scheduled posts with remove from queue
![image](https://github.com/m1n64/telegram-posts-editor/assets/24874264/845c6065-87d6-40f1-abcb-694a9cc0c1e6)
