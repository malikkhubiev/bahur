# Bahur Telegram Bot (Python)

## Запуск

1. Установите зависимости:
   ```bash
   pip install python-telegram-bot==13.15
   ```
2. Запустите бота:
   ```bash
   python botik.py
   ```

## Структура базы данных (SQLite)

База данных создаётся автоматически при первом запуске (`bahur_bot.db`).

### Таблица `aromas`
| Поле        | Тип     | Описание                |
|-------------|---------|-------------------------|
| id          | INTEGER | PRIMARY KEY AUTOINCREMENT |
| brand       | TEXT    | Бренд аромата           |
| aroma       | TEXT    | Название аромата        |
| description | TEXT    | Описание                |
| URL         | TEXT    | Ссылка на подробности   |

### Таблица `finds`
| Поле         | Тип     | Описание                |
|--------------|---------|-------------------------|
| id           | INTEGER | PRIMARY KEY AUTOINCREMENT |
| search_string| TEXT    | SQL-запрос поиска       |
| patterns     | TEXT    | Регулярное выражение    |

## Заполнение базы

Для работы поиска заполните таблицу `aromas`:

```sql
INSERT INTO aromas (brand, aroma, description, URL) VALUES ('Creed', 'Aventus', 'Мужской аромат с нотами ананаса и бергамота', 'https://example.com/aventus');
```

## Функционал
- Кнопки и команды, как в PHP-версии
- Поиск по описанию, бренду, полу, стране
- Сохранение поисковых запросов для повторения 