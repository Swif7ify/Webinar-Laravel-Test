composer install
npm install
php artisan key:generate
New-Item -ItemType File -Path database\database.sqlite -Force
php artisan migrate --seed
php artisan storage:link
composer run dev
http://localhost:8000


earl@onedev.com
teacher123

marina@onedev.com
student123
