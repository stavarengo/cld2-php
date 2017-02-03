FROM ubuntu:16.04

# Configura o ambiente mínimo do ubuntu
RUN DEBIAN_FRONTEND=noninteractive apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install software-properties-common -y && \
    DEBIAN_FRONTEND=noninteractive locale-gen en_US.UTF-8 pt_BR.UTF-8 && \
    DEBIAN_FRONTEND=noninteractive dpkg-reconfigure locales && \
    DEBIAN_FRONTEND=noninteractive apt-get install language-pack-en-base -y && \
    DEBIAN_FRONTEND=noninteractive LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php -y

# Instala nossas dependencias
RUN DEBIAN_FRONTEND=noninteractive apt-get update
    DEBIAN_FRONTEND=noninteractive apt-get install -y \
     git \
     apache2 \
     libapache2-mod-php5.6 \
     php5.6 \
     php5.6-dev
 
# Compila a extenção CLD2 do PHP
RUN cd /root && \
    git clone https://github.com/fntlnz/cld2-php-ext.git && \
    cd cld2-php-ext && \
    git clone https://github.com/CLD2Owners/cld2.git libcld2 && \
    cd libcld2/internal && \
    ./compile_libs.sh && \
    cp libcld2.so /usr/local/lib && \
    cd ../.. && \
    phpize && \
    ./configure --with-cld2=libcld2 && \
    make -j && \
    make install && \
    DEBIAN_FRONTEND=noninteractive apt-get remove php5.6-dev -y && \
    echo -e "\nextension=cld2.so\n" | tee -a /etc/php/5.6/apache2/php.ini && \
    service apache2 restart

# Expoẽ nosso serviço de identificação da linguagem