## Description

This is Booking 62, a simple app to handle booking a stay in wonderful cabins all across Bieszczady mountains.

## Installation

1. Clone this repo.

2. If you are working with Docker Desktop for Mac, ensure **you have enabled `VirtioFS` for your sharing implementation**. `VirtioFS` brings improved I/O performance for operations on bind mounts. Enabling VirtioFS will automatically enable Virtualization framework.

3. Create the file `./.docker/.env.nginx.local` using `./.docker/.env.nginx` as template. The value of the variable `NGINX_BACKEND_DOMAIN` is the `server_name` used in NGINX.

4. Go inside folder `./docker` and run `docker compose up -d` to start containers.

5. You should work inside the `php` container. This project is configured to work with [Remote Container](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers) extension for Visual Studio Code, so you could run `Reopen in container` command after open the project.

6. Inside the `php` container, run `composer install` to install dependencies from `/var/www/symfony` folder.

7. Use the following value for the DATABASE_URL environment variable:

```
DATABASE_URL=mysql://kajetan:1234@db:3306/booking62?serverVersion=8.0.33
```

You could change the name, user and password of the database in the `env` file at the root of the project.

