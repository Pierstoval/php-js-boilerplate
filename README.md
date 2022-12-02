Default JAMStack project
========================

Contains:

* A PHP/Symfony app with Api Platform for backend
* A default SvelteKit application for frontend
* CSS powered by Tailwind
* An OpenApi spec export/import process to allow frontend client to call the backend API
* An administration panel powered by EasyAdmin, compatible with DTOs for entity update.

## Usage

Run `make install`.

Then, you can visit the different parts of the app:

* [https://localhost/](https://localhost/) for the frontend app.
* [https://localhost/api/](https://localhost/api/) for the API.
* [https://localhost/admin/](https://localhost/admin/) for the administration panel.

## HTTP

The application is served through several endpoints:

* Static file server.
* PHP for the backend server, serving the API and administration panel.
* Node.js for the SvelteKit frontend server.
* A WebSocket connection, passed to the frontend server for HMR (Hot Module Replacement). This is a behavior that is not present in production mode.

All of these different "backends" are configured using [Caddy](https://caddyserver.com), one of the most modern and customizable HTTP servers.

Check also the [Caddyfile](./docker/caddy/Caddyfile) for details about HTTP routing.

## Backend

The backend app exposes **only two** endpoints:

* An API through the `/api` endpoint, and it is defined with Api Platform.
* An administration panel via `/admin`, configured with EasyAdmin.

You can customize the API and admin panel like you want, but if you need to change the **HTTP paths**, don't forget to check the [Caddyfile](./docker/caddy/Caddyfile) to update HTTP routing.

## Frontend

The frontend is served via SvelteKit, and is only a fallback after all other HTTP routes (static files, websocket and API).

### API

The API is defined in the Api Platform schema, and exported to OpenAPI.

This OpenAPI export is then used by the frontend app, with the help of the [orval](https://orval.dev/) CLI tool, to generate an HTTP client with endpoints, structs/classes and [axios](https://axios-http.com) as HTTP client.
