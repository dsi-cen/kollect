FROM debian:bullseye-slim

# Update and install APACHE2 and PYTHON 
RUN apt-get update
RUN apt-get install -y vim curl apache2 apache2-utils php-common libapache2-mod-php php-cli php-pgsql

# Copy Kollect project's files
WORKDIR /var/www/html
RUN rm -r *
RUN echo '<?php phpinfo( ); ?>' > phpinfo.php

RUN echo "Copy www folder."
COPY . .

RUN chmod -R 775 /var/www/html/
RUN chown -R www-data: /var/www/html/

EXPOSE 80
CMD ["apache2ctl", "-D", "FOREGROUND"]