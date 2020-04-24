FROM ubuntu:18.04

RUN apt-get update -yq

RUN apt-get install -yq \
      unzip \
      wget

### Install QDK ###

# QDK master branch as of Feb 4, 2020
WORKDIR /src/github.com/qnap-dev
RUN wget -O QDK.zip 'https://github.com/qnap-dev/QDK/archive/4903650172544a1379692cba9597a6f9afadfad5.zip' \
 && unzip QDK.zip \
 && rm QDK.zip \
 && mv QDK-4903650172544a1379692cba9597a6f9afadfad5 QDK

WORKDIR /src/github.com/qnap-dev/QDK
RUN ./InstallToUbuntu.sh install

# This is necessary because QDK adjusts the PATH in the user's bashrc and not
# at the system level.
ENV PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/share/QDK/bin

COPY . /src/github.com/storj/qnap-app
WORKDIR /src/github.com/storj/qnap-app

### Build qpkg ###

RUN qbuild
