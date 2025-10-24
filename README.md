## Setup

- Run with docker: `docker compose up -d --build`
- Use `postman/iot_collection.json` to test API routes
- Run `php artisan test`
- Run `php artisan app:check-offline-sensors` to execute a command that will find offline sensors

## Technical Requirements

Write a REST API with the following functionalities
- Endpoints for adding and removing users
- Endpoints for adding / removing devices and attaching/detaching to users. - Endpoints for sending data to the system (simulating as if a device has sent a temperature measurement)
- Endpoint for getting temperature measurements from devices for a user. - Endpoint for getting alerts for a user.
- For simplicity consider everything below 0 and above 30 degrees celsius as an alert and only preserve to the database (no need to send emails or anything).
