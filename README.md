# Микросервис получения кода страны по IP

Для создания микросервиса использовался репозиторий [dunglas/symfony-docker](https://github.com/dunglas/symfony-docker)

## Запуск микросервиса

1. Для запуска необходим установленный, [Docker Compose](https://docs.docker.com/compose/install/)
2. Выполните в консоли `docker-compose build --pull --no-cache`  для построения контейнеров и обновления образов
3. Выполните в консоли `docker-compose up` (логи будут выводиться в терминале)
4. Откройте в браузере `https://localhost` (если вы используете переменные окружения из [.env.example](.env.example), то приложение будет развёрнуто на порту 4443)
5. Для остановки контейнеров `docker-compose down --remove-orphans`.
6. Удобные предустановленные команды можно посмотреть в [Makefile](Makefile) при условии если у вас установлен [make](https://www.gnu.org/software/make/)

## Работа микроосервиса

* Микросервис получает запросы по маршруту: `/v1/get_country_code`
* Запрашиваемый Ip адрес должен быть указан в качестве query запроса: `/v1/get_country_code?ip=8.8.8.8`
* Ответ на запрос отправляется а виде json с ключем `country_code`
```
{
    "country_code": "RU"
}
```

* В настройках можно изменить api - поставщика данных для микросервиса а также время кеширования в .env:

```
#Время кеширования запроса
EXPIRES_COUNTRY_CODE_TIME=3600

#Базовый формат url с указанием {ip}
COUNTRY_CODE_API_URL=https://ipapi.co/{ip}/json/

#Метод запроса к внешнему API
COUNTRY_CODE_API_METHOD=GET

#Параметры запроса к внешнему API (query для GET, body для POST)
COUNTRY_CODE_API_PARAMETERS=""

#Ключ и значение тела ответа при ошибке либо неудачном запросе
COUNTRY_CODE_API_IS_FAILED=error:true

#Ключ тела ответа для получения данных о коде страны
COUNTRY_CODE_API_FIELD=country_code

```
* При ошибках валидации ip а также ошибках запроса у api микросервис возвращает json с ошибками:
```
{
    "errors": "Внешний API не отвечает!"
}

{
    "errors": "Object(App\\Entity\\IpAddress).ip:\n    Не верный формат Ip адреса! (code b1b427ae-9f6f-41b0-aa9b-84511fbb3c5b)\n"
}
```
