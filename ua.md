# Simple php router
Це простий у використанні роутер для вашого застосунку в `php`

# Початок роботи
Перш ніж почати роботу з роутером, нам потрібно налаштувати
наш застосунок. Для цього потрібно створити у корені проекту
`.htaccess` файл, у якому напишемо декілька рякдів коду для сервера `Apache`
```apacheconf
RewriteEngine on

# Ігнорування файлів
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_URI} !(\.css|\.html|\.png|\.gif|\.jpeg)

# Тут ми перенаправляемо всі звернення до сервера
# у головний файл застосунку
RewriteRule ^ index.php
```

Наступним кроком буде ініціалізація роутера.
Для цього, напишіть наступний код.
```php
use router\Router; // підключення простору імен роутера
```
Далі створимо новий роутер за допомогою `new Router()`
```php
$router = new Router();
```
В роутері доступні такі методи як `get`, `post`, `put`, `patch`, `delete`.
Використовувати їх можна таким чином:
```php
$router->get('/', function () {
    echo 'Hello world';
});
```
Наспутним та останнім кроком буде запуск роутера за допомогою метода `resolve`.
```php
$url = Router::getPath();
$method = Router::getMethod();

$router->resolve($url, $method);
```
У коді наведеному вище, ми дізналися `$method` та `$url` з запиту.
Використовувати статичні методи `getPath()` та `getMethod()` не обов'язково,
але ми рекомендуємо вам написати ці методи у своєму коді для спрощення
розуміння алгоритму.

Таким чином, ми створили застосунок який показує `Hello world` за допомогою классу `Router`.