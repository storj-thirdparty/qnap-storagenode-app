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