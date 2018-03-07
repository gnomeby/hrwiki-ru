HRWiki
======
([English version](https://github.com/gnomeby/hrwiki))

HRWiki - база данных для ведения соискателей и их резюме, и другой связанной с этим информацией

Возможности:

* Ввод информации о кандидатах
* Поиск по прикреплённым файлам
* Оставление комментариев, отзывов
* Удалённый доступ
* Автозаполнение для специализаций

*Поддержка*
* [Оставьте заявку](https://github.com/gnomeby/hrwiki-ru/issues/new)

#### Требования:
* Apache 2.2
* * mod_rewrite
* * SSL
* * vhost
* * auth_basic
* * authn_file
* * authz_user
* PHP 5.3
* * intl
* * mysqli
* * fileinfo
* * mod_php for apache
* MariaDB 5.1+ or MySQL 5.0.2 or higher
* imagemagick
* SMTP client для отправки писем (exim, ssmtp)
* FileIndexer system tools
* * antiword
* * catdoc
* * pdftotext

#### Установка:
* Прочитайте *Требования* и установите всё
* Скачайте [утилиту](https://github.com/gnomeby/hrwiki-ru/archive/master.zip)
* * Распакуйте это туда, где обычно у вас хранятся источники файлов для apache VirtualHost
* * Восстановите дамп базы данных из файла *maintenance/dumps/06_hrwiki_russian_language.sql*
* * Откройте *extensions/FileIndexer/FileIndexer_cfg.php* и проверьте секцию $wgFiCommandPaths, убедитесь, что у вас есть эти файлы по указанным путям
* * Откройте *LocalSettings.php* и измените следующие настройки:
* * $wgServer
* * $wgEmergencyContact
* * $wgPasswordSender
* * $wgDBserver, $wgDBname, $wgDBuser, $wgDBpassword
* * $wgImageMagickConvertCommand, $wgShellLocale, $wgDiff3
* * Установите владельца папки с утилитой пользователя apache
* Настройте VirtualHost для apache
* * Используйте в качестве примера apache.hrwiki.example.conf

#### Использование:
* Откройте главную страницу
* Представьтесь системе: Admin / password1
* Изменить пароль
* Прочтите руководство

#### Настройки безопасности:
Утилита в процессе её эксплуатации будет пополняться конфиденциальной информацией о кандидатах. Для большей защиты этой информации:
* Настройте доступ к утилите по [HTTPS](http://httpd.apache.org/docs/2.2/ssl/)
* Защттите все страницы [базовой авторизацией](http://httpd.apache.org/docs/2.2/howto/auth.html)

#### Авторы:
* [Евгений Артюхов](https://github.com/jsirex)
* [Андрей Нехайчик](https://github.com/gnomeby)
