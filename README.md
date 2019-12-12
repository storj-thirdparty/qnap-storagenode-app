# STORJ QNAP

STORJ QNAP is the application for installing on QNAP NAS applications page. The format of the application installed on the QNAP is .qpkg which can be built on [QDK](https://github.com/qnap-dev/QDK#installation).

## Building Instructions

Install [Entware](https://www.qnapclub.eu/en/qpkg/556) on QNAP.

Once entware is installed, install git with following commands.
```sh
opkg update
opkg install git
```

Cloning the repository.

```bash
$ git clone https://ninad458@bitbucket.org/utropicmedia/storj-node-qnap.git
$ cd storj-node-qnap
$ qbuild
```
The qpkg file is found at [storj-node-qnap/build](storj-node-qnap/build)

[QPKG Building Instructions](https://edhongcy.gitbooks.io/qdk-quick-start-guide/content/build-your-own-qpkg.html)

## Cheat sheet
- To create a new Project `qbuild --create-env <project-name>`
- Change version number(QPKG_VER) in [qpkg.cfg](qpkg.cfg)
- [To add new icons](https://github.com/qnap-dev/QDK#how-to-add-icons-in-qpkg)
- Add html/php files in [web folder](shared/web)

## Identity Generation
[Storj docs](https://documentation.storj.io/dependencies/identity)

## Paths
docker-path - '/share/CACHEDEV1_DATA/.qpkg/container-station/bin/docker'

Actions to the triggers can be found and added in storj-node-qnap/shared/STORJ.sh.
Perform actions accordingly. Give the full path of the executables.
Default actions can be found in the package_routines.
Actions can be fired from system("/etc/init.d/STORJ.sh <your-command>") in php script.

Actions that should be executed pre and post the installation of the app is written in package_routines.

storj-node-qnap/shared/file_exists.sh will give you if the identity files are generated or not. 

The entry point of the app is index.php. Based on the output of file_exists.sh, the page is redirected to either authorization.php or dashboard.php.

dashboard.php will check if the docker is running or not which is queried from STORJ.sh. If the docker is running it will give stop button and show you the dashboard, else it will show a form for wallet, email, port, storage and bandwidth and let you start the container.
start and stop commands are fired to STORJ.sh

Pass absolute path of the Storage Directory in the form field Identity path.