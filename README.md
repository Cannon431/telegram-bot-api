# Simple library for working with Telegram bot API

## Instalation
```bash
composer require justify/telegram-bot-api
```

## Usage

### Creating client instance:
```php
define('TELEGRAM_TOKEN', 'Your token');

$bot = new Justify\TelegramBotApi\Client(TELEGRAM_TOKEN);
```

### Sending requests
You can send requests uses harvested methods:
```php
$bot->sendMessage($chatId, 'Hello!');
$bot->sendPhoto($chatId, 'File id || URL || path to file', [
    'caption' => 'My photo'
]);
```

Almost all API methods takes thirst argument **chat id** and last **optional params**


---
If you are missing inbuilt methods you can send requsest via `sendRequest` method:
```php
$response = $bot->sendRequest('getFile', [
    'file_id' => $fileId
]);
```

### Handling requests
You can handling requests using `Justify\TelegramBotApi\Client` methods:
```php
$bot->handler(function ($message) use ($bot) {
    // Actions ...
});
```

You can set conditions for handler:
```php
// Will react on "start" command
$bot->command('start', function ($message) use ($bot) {
    // Actions ...
});

// Will react on "Some text" text
$bot->text('Some text', function ($message) use ($bot) {
    // Actions ...
});

// Will react if text messages will match to regexp pattern
$bot->regexp('Password (\d+)', function ($message, $matches) use ($bot) {
    // Actions ...
});

// Will react if message will have "photo" type
$bot->type('photo', function ($message) use ($bot) {
    // Actions ...
});
$
```

---
### Sending some media:
```php
$bot->sendPhoto($chatId, 'Pass file id or file url or path to file');
```

### Sending media group:
```php
$photo = new Justify\TelegramBotApi\Types\InputMediaPhoto('Pass file id or file url or path to file', [
    'caption' => 'My photo'
]);
$video = new Justify\TelegramBotApi\Types\InputMediaVideo('Pass file id or file url or path to file', [
    'caption' => 'My video'
]);

$bot->sendMediaGroup($chatId, [$photo, $video]);
```

### Sending keyboards:
```php
$keyboard = new Justify\TelegramBotApi\Types\ReplyKeyboardMarkup([
    'resize_keyboard' => true,
    'ont_time_keyboard' => true
]);

$keyboard->row('1', '2', '3')
    ->row('4', '5', '6')
    ->row('7', '8', '9')
    ->row('0');

$bot->sendMessage($chatId, 'Choose a number', ['reply_markup' => $keyboard]);


// Removing keyboard
$removeKeyboard = new Justify\TelegramBotApi\Types\ReplyKeyboardRemove();
$bot->sendMessage($chatId, 'Removing keyboard', ['reply_markup' => $removeKeyboard]);
```

### Running application:
```php
$bot->polling();
```

### Usage example:
```php
require_once __DIR__ . '/vendor/autoload.php';

define('TELEGRAM_TOKEN', 'Pass your token here');

$bot = new Justify\TelegramBotApi\Client(TELEGRAM_TOKEN);

$bot->command('start', function ($message) use ($bot) {
    $keyboard = new Justify\TelegramBotApi\Types\ReplyKeyboardMarkup([
        'resize_keyboard' => true
    ]);
    $keyboard->row('Photo', 'Audio')
        ->row('Document', 'Video')
        ->row('/stop');

    $bot->sendMessage($message->chat->id, 'Welcome!', ['reply_markup' => $keyboard]);
});

$bot->command('stop', function ($message) use ($bot) {
    $bot->sendMessage($message->chat->id, 'Completed', [
        'reply_markup' => new Justify\TelegramBotApi\Types\ReplyKeyboardRemove()
    ]);
});

$bot->text('Photo', function ($message) use ($bot) {
    $bot->sendPhoto($message->chat->id, 'File id || URL || path to file');
});

$bot->text('Audio', function ($message) use ($bot) {
    $bot->sendAudio($message->chat->id, 'File id || URL || path to file');
});

$bot->text('Document', function ($message) use ($bot) {
    $bot->sendDocument($message->chat->id, 'File id || URL || path to file');
});

$bot->text('Video', function ($message) use ($bot) {
    $bot->sendVideo($message->chat->id, 'File id || URL || path to file');
});

try {
    $bot->polling();
} catch (Justify\TelegramBotApi\Exception $e) {
    echo $e->getMessage();
}
```
