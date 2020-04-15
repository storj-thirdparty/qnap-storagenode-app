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

# App Use
- Put path to identity folder in **Identity**. This is the absolute path of `identity` folder. For example you copied your identity folder to `/share/Public`, the input will be `/share/Public/identity`.
- Make sure you open the port you're going to enter in **Port Forwarding**.
- **Storage Directory** should contain the complete path to the shared folder. Make sure you give all the permissions to the folder with `chmod -R 777 </absolutepath/to/shared_folder>`. For example you create a folder with name `storj` at `/root`, this field should be populated with `/root/storj`
- **Ethereum Wallet Address** It should be a valid ERC-20 compatible wallet address. If this path is invalid the storagenode woould not start.
- **Email** Please check the email id
- **Bandwidth** The minimum bandwidth requirement is **2TB**.
- **Storage Allocation** Be sure not to over-allocate space! Allow at least 10% extra for overhead. If you over-allocate space, you may corrupt your database when the system attempts to store pieces when no more physical space is actually available on your drive. The minimum storage shared requirement is 500 GB, which means you need a disk of at least 550 GB total size to allow for the 10% overhead.
- **Update** During installation a cron tab is created to trigger the update script at 12:05 AM. The scripts checks if the docker container running is latest or not. If the current image is latest then the nothing is done but if it is old then the storagenode is stopped, old container is removed and updated and restarted using the same old parameters that are saved. This all process happen automatically. Also the user can trigger the same process manually whenver they want by pressing the 'Update' button on the UI . 

# Debug
App is installed at `/share/CACHEDEV1_DATA/.qpkg/STORJ`. The folder structure is the same as it is in the shared folder. In case the app is misbehaving or needs to be debugged, it can be found here.

If you can't see or access the dashboard try the command **docker container ls -all** to check if the storaganode docker container is running.

## More information
To know more about running the Docker container of Storj Storage node docker container [Storagenode CLI](https://documentation.storj.io/setup/cli/storage-node)