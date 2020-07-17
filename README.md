
# QNAP storagenode

*QNAP storagenode* is an application for installing the Storj *[storagenode](https://documentation.storj.io/setup/cli/storage-node)* on QNAP NAS devices using the QNAP-native application bundle. The format of the application installed on the QNAP is .qpkg which can be built on [QDK](https://github.com/qnap-dev/QDK#installation).

<br />

<p align="center"><img src="README.assets/qnap_logo.png" alt="qnap_logo" /></p>

<br />

## Prerequisites

- 500 GB Free Disk space
- Docker



## App Use

Currently the application must be manually installed via the QNAP App Center application provided with your QNAP.  The App Center is accessable from the Web UI (Desktop) provided from your device.  The app is a local web application that makes it easier to install and configure the Storj *storagenode* service, which include the SNO Dashboard for monitoring your node. 

<br />

## Configuration Items

**Identity** .....  will support generating a storjnode identity.  As identity generation can take anywhere from minutes to hours, the identity log area will be updated every 60s to 

**Port Forwarding** .... contains host and port information that is required to reach the services on your NAS from the public Internet. (e.x. *mynashost.dynip.com:12345*)

**Storage Directory** .... should contain the complete path to the shared folder. Make sure you give all the permissions to the folder with `chmod -R 777 </absolutepath/to/shared_folder>`. For example you create a folder with name `storj` at `/root`, this field should be populated with `/root/storj`

**Ethereum Wallet Address** .... It should be a valid ERC-20 compatible wallet address. If this path is invalid the storagenode woould not start. (ex. )

**Email** .... Email ID associated with your authorization token invite

**Storage Allocation** .... Be sure not to over-allocate space! Allow at least 10% extra for overhead. If you over-allocate space, you may corrupt your database when the system attempts to store pieces when no more physical space is actually available on your drive. The minimum storage shared requirement is 500 GB, which means you need a disk of at least 550 GB total size to allow for the 10% overhead.

**Update** ... During installation a cron tab is created to trigger the update script at 12:05 AM. The scripts checks if the docker container running is latest or not. If the current image is latest then the nothing is done but if it is old then the storagenode is stopped, old container is removed and updated and restarted using the same old parameters that are saved. This all process happen automatically. Also the user can trigger the same process manually whenver they want by pressing the 'Update' button on the UI. 

**(Start | Stop)** ... will gracefully start and stop the container image.

<br /><br /><br />


## Identity Generation

Identity Generation is performed using the published Storj identity generation tools.  More information on the process can be found in the [Storj identity generation docs](https://documentation.storj.io/dependencies/identity) 

<br /><br />



## Building Instructions

For those interested in building the application on their device:

Install [Entware](https://www.qnapclub.eu/en/qpkg/556) on QNAP.
Once entware is installed, install git with following commands.

```sh
opkg update
opkg install git
```

Cloning the repository.

```bash
$ git clone https://github.com/storj/qnap-storagenode-app.git
$ cd qnap-storagenode-app
$ qbuild
```
The qpkg file is found at qnap-storagenode-app/build](qnap-storagenode-app/build)

[QPKG Building Instructions](https://edhongcy.gitbooks.io/qdk-quick-start-guide/content/build-your-own-qpkg.html)

<br /><br />

## Notes on Building

- To create a new Project `qbuild --create-env <project-name>`
- Change version number(QPKG_VER) in [qpkg.cfg](qpkg.cfg)
- [To add new icons](https://github.com/qnap-dev/QDK#how-to-add-icons-in-qpkg)
- Add html/php files in the [web folder (shared/web)](shared/web)

<br /><br />

## Notes on Paths

 Important paths used by the app:

- <u>**Paths are abolute locations on the QNAP filesystem**</u>.  QNAP often simplifies the director structure when using their tools.(e.g. *Filestation*)
- Location of Docker is '/share/CACHEDEV1_DATA/.qpkg/container-station/bin/docker'
- Actions that should be executed pre and post the installation of the app is written in package_routines.

- *storj-node-qnap/shared/file_exists.sh* will give you if the identity files are generated and present or not.

- Actions to triggers can be found and added in storj-node-qnap/shared/STORJ.sh.

- Default actions can be found in the package_routines.  Actions can be fired from system("*/etc/init.d/STORJ.sh <your-command>*") in php script.

- The entry point of the app is index.php. Based on the output of *file_exists.sh*, the page is redirected to either *authorization.php* or *dashboard.php*.

- *dashboard.php* will check if the docker is running or not which is queried from STORJ.sh. If the docker is running it will give stop button and show you the dashboard, else it will show a form for wallet, email, port, storage and bandwidth and let you start the container.
  start and stop commands are fired to STORJ.sh

  <br /><br />

## Debugging

App is installed at `/share/CACHEDEV1_DATA/.qpkg/STORJ`. The folder structure is the same as it is in the shared folder.

If you can't see or access the dashboard try the command **docker container ls -all** to check if the storaganode docker container is running.

Logging comes from the following sources:

- */var/log/STORJ* ... Applicaiton log messages
- */share/Public/identity/logs/storj_identity.log* ... Identity log messages

  <br />

## More information

To know more about running the Docker container of Storj Storage node docker container [Storagenode CLI](https://documentation.storj.io/setup/cli/storage-node)
