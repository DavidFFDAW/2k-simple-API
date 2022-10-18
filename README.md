# 2K Simple API REST

This application provides a REST
API to the 2K Admin Database.

This project has zero configuration that you need to set to be able to use it.

The only thing you need to add to the root is an `.env` file like the `.env.example` filling the keys.

This project also has not one single dependency and there is no need to install anything as it is pure PHP.

## Run the app

    php -S 127.0.0.1:8566

<br/>

# REST API

The REST API to the app is described below with requests and responses.

This API REST works with `JSON`.

The base url for each request will be `/2k/api/v2`

    127.0.0.1:8566/2k/api/v2

<br/>

## `POST` login

### Request

`POST /login`

    /2k/api/v2/login/

### Body

    {
        "email": "james@example.com",
        "password": "1234"
    }

### Response

    {
        "status": "OK",
        "message": "Succesful login",
        "code": 200,
        "token": "$_121545678..."
    }

## `POST` register

### Request

`POST /register`

    /2k/api/v2/register

### Body

    {
        "email": "james@example.com",
        "password": "1234",
        "passphrase": "random"
    }

### Response

    {
        "status": "OK",
        "message": "Succesful register",
        "code": 200,
        "token": "$_121545678..."
    }

## `GET` all championships reigns

### Request

`GET /champions/get/reigns`

    /champions/get/reigns

### Response

    {
        "status": "success",
        "message": "Succesful",
        "code": 200,
        "reigns": {
            "currentSingles": [
                {
                    "championship": string,
                    "championshipId": string,
                    "wrestlerId": string,
                    "championshipImage": url,
                    "reignId": string,
                    "reignDays": string,
                    "brand": string,
                    "wrestlerName": string,
                    "wrestlerImage": url,
                    "overall": string,
                    "totalDays": string,
                    "totalReigns": string
                },
                ...
            ]
        }
    }
