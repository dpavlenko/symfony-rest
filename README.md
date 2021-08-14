# Справочная информация

В данном проекте приведен пример простого приложения на Symfony 5 с использованием  REST. 
Чтобы упростить задачу взаимодействия с сервером, я написал простой и 
интуитивно понятный web-интерфейс. Для перевода сущностей на английский язык
использовалось дополнение для Doctrine 2 "Translatable Behavior". Подробнее
с ним можно ознакомиться в официальной документации [Symfony][symfonyref] и на 
странице расширения, т.е. поведения(behavior) для Doctrine 2 [Translatable][knp]. Надо добавить, что поведение 
Translatable позволяет довольно удобно пользоваться переводами на разные локали (языки).

[symfonyref]:https://symfony.com/doc/current/translation.html#translating-database-content
[knp]:https://github.com/KnpLabs/DoctrineBehaviors/blob/master/docs/translatable.md

Установка 
------------------
1. Открываем терминал, переходим в папку проекта (`.../symfony-rest/`). Если в папке
   можно найти файл `docker-compose.yml`, то вы попали в нужное место.
2. Проверяем, чтобы на локальной машине не было запущено веб-сервера,
   который использует ip 127.0.0.1, доменное имя http://localhost и порты 80 и 8080.
   Если таковой имеется, то требуется завершить его работу, освободить имя и порты.
3. Запускаем контейнеры docker командой `docker-compose up -d` и ждем, пока все установится.
   Docker потребует подключение к интернету.
4. Запускаем Composer: `docker exec -it lamp-php74 composer install` 
5. Устанавливаем миграции базы данных: `docker exec -it lamp-php74 php bin/console doctrine:migrations:migrate`. На вопрос
   в терминале отвечаем подтверждением.
6. Выполняем тесты PHPUnit: `docker exec -it lamp-php74 php ./vendor/bin/phpunit`
7. Открываем браузер на странице http://localhost, смотрим дальнейшие инструкции. 
   Базу данных можно просмотреть по адресу http://localhost:8080/. Пароль к БД - docker, 
   имя пользователя - docker.