Добрый день.

Поскольку требований особых нет, решил показать довольно интересный подход к проектированию. Правда сначала пришлось его адаптировать к Yii advanced.

Для запуска, если соберётесь это сделать, потребуются два домена. Один для backend, второй для api.
Origin backend нужно указать в api/config/params.php, параметр corsOrigin.

В БД добавлен один пользователь.

username: Tester

password: 111111

Но запуск тестов БД затирает.

Дамп тестовых данных.

INSERT INTO apples.user (id, username, auth_key, access_token, password_hash, password_reset_token, email, status, created_at, updated_at, verification_token) VALUES (1, 'Tester', 'zKdALokOyLTphXdmNQiSOMM4VFva1RZ3', 'zKdALokOyLTphXdmNQiSOMM4VFva1RZ3', '$2y$13$50CErppxAWg5S7P0EZKhS.EIvX2ryUjMA/9rxBA1LRCHCDFicMnS6', null, 'tester@example.com', 10, 1580750242, 1580750242, null);

INSERT INTO apples.apple_color (id, user_id, color) VALUES (1, 1, 'red');
INSERT INTO apples.apple_color (id, user_id, color) VALUES (2, 1, 'green');
INSERT INTO apples.apple_color (id, user_id, color) VALUES (3, 1, 'yellow');
INSERT INTO apples.apple_color (id, user_id, color) VALUES (4, 1, 'orange');
INSERT INTO apples.apple_color (id, user_id, color) VALUES (5, 1, 'blue');

Если поднять третий домен, на frontend, можно добавлять юзера через форму. Это так же добавляет access_token для доступа к api, но список цветов яблок остнется пуст.

Писать UI  для добавления цветов яблок не стал, это будет ещё один такой же код, как и для самих яблок, только попроще. Однообразный код в реальном проекте, это хорошо, а в тестовом это просто ваше время на чтение, но ничего нового об авторе.

Интересного чтения, Александр.