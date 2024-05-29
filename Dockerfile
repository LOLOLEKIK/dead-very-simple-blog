FROM debian:bullseye

RUN apt update 
RUN apt install -y apache2 default-mysql-client php php-gd php-zip php-xml php-mysql php-mbstring
RUN mkdir /files
COPY ./docker_init/run.sh /files/run.sh
COPY apache_conf/000-default.conf /etc/apache2/sites-available/000-default.conf
ENTRYPOINT [ "sh", "/files/run.sh" ]