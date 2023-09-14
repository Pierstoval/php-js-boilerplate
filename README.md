Default JAMStack project
========================

Contains:

* A PHP/Symfony app with Api Platform for backend
* A default SvelteKit application for frontend
* CSS powered by Tailwind
* An OpenApi spec export/import process to allow frontend client to call the backend API
* An administration panel powered by EasyAdmin

## Create a new project

There are two solutions:

* Click on the <kbd>Use this template</kbd> button on the top-right of this repository's page, this will create a repository in whichever Github profile or organization you want.
* Run one of these scripts:
  * With Git:
    ```
    git clone https://github.com/Pierstoval/php-js-boilerplate.git && cd php-js-boilerplate && rm -rf .git/ && git init && git checkout -b main && git add . && git commit -a -m "Init project"
    ```
  * With curl and unzip:
    ```
    curl -sSL "https://github.com/Pierstoval/php-js-boilerplate/archive/refs/heads/main.zip" -o main.zip && unzip main.zip && rm main.zip && cd php-js-boilerplate-main && rm -rf .git/ && git init && git checkout -b main && git add . && git commit -a -m "Init project"
    ```
  * With wget and unzip:
    ```
    wget "https://github.com/Pierstoval/php-js-boilerplate/archive/refs/heads/main.zip" && unzip main.zip && rm main.zip && cd php-js-boilerplate-main && rm -rf .git/ && git init && git checkout -b main && git add . && git commit -a -m "Init project"
    ```

## Usage

Run `make install`.

Then, you can visit the different parts of the app:

* [https://localhost/](https://localhost/) for the frontend app.
* [https://localhost/api/](https://localhost/api/) for the API.
* [https://localhost/admin/](https://localhost/admin/) for the administration panel.

## HTTP

The application is served through several endpoints:

* Static file server for all the assets in `backend/public/`
* PHP for the backend server, serving the API and administration panel.
* Node.js for the SvelteKit frontend server.
* A WebSocket connection, passed to the frontend server for HMR (Hot Module Replacement). This is a behavior that is not present in production mode.

All of these different HTTP endpoints are configured using [Caddy](https://caddyserver.com), one of the most modern and customizable HTTP servers.

Check also the [Caddyfile](./docker/caddy/Caddyfile) for details about HTTP routing.

## Backend

The backend app exposes **only two** endpoints:

* An API through the `/api` endpoint, and it is defined with Api Platform.
* An administration panel via `/admin`, configured with EasyAdmin.

You can customize the API and admin panel like you want, but if you need to change the **HTTP paths**, don't forget to check the [Caddyfile](./docker/caddy/Caddyfile) to update HTTP routing.

## Frontend

The frontend is served via SvelteKit, and is only a fallback after all other HTTP routes (static files, websocket and API).

So basically, everything that is not a file in the `backend/public/` directory and that does not begin with `/api` or `/admin` will be served by SvelteKit.

### API

The API is defined in the Api Platform schema, and exported to OpenAPI.

This OpenAPI export is then used by the frontend app, with the help of the [orval](https://orval.dev/) CLI tool, to generate an HTTP client with endpoints, structs/classes and [axios](https://axios-http.com) as HTTP client.
