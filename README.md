Default JAMStack project
========================

Contains:

* A PHP/Symfony app with Api Platform for backend
* A default SvelteKit application for frontend
* An OpenApi spec export/import process to allow frontend client to call the backend API

## Usage

Run `make install` and visit https://localhost/ .

## HTTP

The application is served through several endpoints:

* Static file server.
* PHP for the API backend server.
* Node.js for the SvelteKit frontend server.
* A WebSocket connection, passed to the frontend server for HMR (Hot Module Replacement).

All of these different "backends" are configured using [Caddy](https://caddyserver.com), one of the most modern and customizable HTTP servers.

Check also the [Caddyfile](./docker/caddy/Caddyfile) for details about HTTP routing.

## Backend

The backend app exposes **only** an API through the `/api` endpoint, and it is defined with Api Platform.

You can customize the API like you want, but if you need to change the **HTTP path**, don't forget to check the [Caddyfile](./docker/caddy/Caddyfile) to update HTTP routing.

## Frontend

The frontend is served via SvelteKit, and is only a fallback after all other HTTP routes (static files, websocket and API).

### API

The API is defined in the Api Platform schema, and exported to OpenAPI.

This OpenAPI export is then used by the frontend app, with the help of the [orval](https://orval.dev/) CLI tool, to generate an HTTP client with endpoints, structs/classes and [axios](https://axios-http.com) as HTTP client.
