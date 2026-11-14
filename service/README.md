# Сервис joker (systemd)

Пример unit-файла для запуска бота как сервиса systemd. Бот запускается
командой `php joker.php` от отдельного пользователя `joker` из папки
`/opt/joker-telegram-bot`. При падении перезапускается через 5 секунд,
стартует при загрузке сервера. Вывод бота пишется в журнал systemd (journald).

## Установка

```bash
# пользователь без логина и код бота
sudo useradd --system --home /opt/joker-telegram-bot --shell /usr/sbin/nologin joker
sudo git clone https://github.com/miamibc/joker-telegram-bot.git /opt/joker-telegram-bot
cd /opt/joker-telegram-bot && sudo composer install --no-dev
sudo chown -R joker:joker /opt/joker-telegram-bot

# токены и ключи: строки KEY=value (TELEGRAM_TOKEN=..., OPENAI_API_KEY=... и т.д.)
sudo -u joker nano /opt/joker-telegram-bot/.env
sudo chmod 600 /opt/joker-telegram-bot/.env

# сервис
sudo cp service/joker.service /etc/systemd/system/joker.service
sudo systemctl daemon-reload
sudo systemctl enable --now joker
```

## Свои пути, пользователь или скрипт

Не правьте `joker.service` — переопределите нужное в drop-in файле,
тогда обновление репозитория ничего не затрёт:

```bash
sudo systemctl edit joker
```

```ini
[Service]
User=bot
Group=bot
WorkingDirectory=/srv/joker
EnvironmentFile=
EnvironmentFile=/srv/joker/.env
ReadWritePaths=
ReadWritePaths=/srv/joker/data
ExecStart=
ExecStart=/usr/bin/php my-joker.php
```

Пустая строка (`ExecStart=` и т.п.) сбрасывает значение из основного файла.
Если бот живёт в домашней папке, добавьте `ProtectHome=false`.

## Управление

```bash
sudo systemctl start joker      # запустить
sudo systemctl stop joker       # остановить
sudo systemctl restart joker    # перезапустить (например после git pull)
systemctl status joker          # статус
```

## Логи

```bash
journalctl -u joker -f          # смотреть в реальном времени
journalctl -u joker --since today
```

Размер и срок хранения журнала задаются в `/etc/systemd/journald.conf`
(`SystemMaxUse=`, `MaxRetentionSec=`), logrotate не нужен.

## Удаление

```bash
sudo systemctl disable --now joker
sudo rm -r /etc/systemd/system/joker.service /etc/systemd/system/joker.service.d
sudo systemctl daemon-reload
```
