# Як запустити проект?

Markup : 1. Встановити [docker](https://docs.docker.com/install/)
         2. Встановити [docker-compose](https://docs.docker.com/compose/install/)
         3. Відкрити термінал 
         4. git clone http://gitlab.devtoday.com/KVladyslav/todo_list.git
         5. cd todo_list/
         6. docker-compose up --build -d
         7. docker-compose run composer install
         8. docker-compose exec php-fpm bash
         9. php bin/console doctrine:database:create
         10. php bin/console doctrine:migrations:diff
         11. php bin/console doctrine:migrations:migrate