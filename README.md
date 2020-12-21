Printer
========

**App for [Nextcloud](https://nextcloud.com) to print files using the CUPS/LPR printing ecosystem.**

![animation](screenshots/printer.gif)

Installation
------------

**Nextcloud**

You need a working CUPS setup with a compatible printer set up as the default printer and the LPR daemon installed on your server.

On Debian, you can install the packages `cups` and `cups-bsd` and configure a default printer in CUPS (please refer to the CUPS documentation on how to do that). You can verify the installation with `lpstat -p` and `lpstat -d` after you configured CUPS correctly.

To install the app itself on your instance, simply navigate to »Apps« in your Nextcloud web interface, choose the category »Tools«, find the Printer app and enable it.

**Docker**

You need a CUPS server with a shared IPP printer running within your local network. To use the plugin within a docker instance the docker image has to be altered to install the packages `cups-client`, `cups-daemon` and `cups-bsd`. The CUPS service has to be started and a default printer setup when the container is starting up. This is achieved by adding a startup script which is executed before the base image of the container is run. The following two examples show you how this can be done for the nextcloud:fpm docker image.

**Example Dockerfile: myNextcloud**

```Dockerfile
FROM        nextcloud:fpm
ADD         myNextcloud.sh /
RUN         apt-get update && apt-get install -y cups-client cups-daemon cups-bsd && chmod +x /myNextcloud.sh
ENTRYPOINT  ["/myNextcloud.sh"]
CMD         ["php-fpm"]
```

**Example startscript: myNextcloud.sh**

```Dockerfile
#!/usr/bin/env sh
service cups start
# Replace the placeholders $ip$ and $printer_name$ accordingly to your setup 
lpadmin -p HP_M281_docker -E -v ipp://$ip$:631/printers/$printer_name$ -m everywhere
lpoptions -d HP_M281_docker
sh /entrypoint.sh "$@"
```

In case you're using docker compose the following changes have to be made to your docker compose file in order to build the image with the changes made in the Dockerfile myNextcloud. To build the docker containers use `docker-compose build --pull`.

**Example Docker compose:**

```Dockerfile
...
  nextcloud:
#    image: nextcloud:fpm
    build:
      context: .
      dockerfile: myNextcloud
...
```

Usage
-----

Just open the details view of the file (Sidebar). There should be a new tab called "Printer". Select a Orientation of your print and it will try to ececute the LPR (Line Printing Daemon) on the CLI to send the job to a printer configured in CUPS. Currently, only the printer set as default can be used for printing.

Possible orientations are "Landscape" and "Portrait". Further options will be added in the future.

Compatibility
-------------

- I only tested the app for the current versions of Nextcloud (14 and up).
- I tried to use the current api as much as possible. It should be safe for future versions.
- Currently only files supported by CUPS natively are supported for printing. This includes Images, PDFs, text files and probably others. Not all filetypes are supported by CUPS.
- The app currently misses a lot of features that will be added in future versions of the app. If you want to help out with development, a PR is highly welcome.
