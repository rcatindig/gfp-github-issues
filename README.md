**HOW TO RUN THE APPLICATION**

1. Copy .env.example to .env
   `cp .env.example .env`
2. Update the following details in .env
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=xxxxxx
DB_USERNAME=xxx
DB_PASSWORD=xxx

GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
GITHUB_REDIRECT_URL=
```
for Github details, you need to follow this step:
    https://docs.github.com/en/apps/oauth-apps/building-oauth-apps/creating-an-oauth-app

3. Run the `composer install`
4. Run the `php artisan migrate`
5. Run the `php artisan serve`
6. Go to browser and open the url `http://127.0.0.1:8000/issues`
7. Authorize the app
    <img width="442" alt="image" src="https://github.com/user-attachments/assets/4ac65898-4739-4df5-a175-cba496790cfa" />
8. <img width="1435" alt="image" src="https://github.com/user-attachments/assets/50791928-6e5d-482a-9288-963b99da1798" />
