# STORJ QNAP

STORJ QNAP is the application for installing on QNAP NAS applications page. The format of the application installed on the QNAP is .qpkg which can be built on [QDK](https://github.com/qnap-dev/QDK).

## Building Instructions

Install [git](https://www.reddit.com/r/qnap/comments/97d3lw/install_git_on_qnap/) on QNAP terminal

```bash
$ git clone https://ninad458@bitbucket.org/utropicmedia/storj-node-qnap.git
$ cd storj-node-qnap
$ qbuild
```
The qpkg file is found at storj-node-qnap/build
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