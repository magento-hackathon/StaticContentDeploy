# StaticContentDeploy
This project is meant as base for work to optimize the static view file
caching process. 

## Goals
There are multiple areas which we try to improve, to
cut the time it takes to run the static content deploy process shorter:

- **Optimizing environment**: When running the static content deploy, 
do make sure to run everything as performant as possible: Use PHP 7 as
PHP CLI tool; Make sure to enable the Zend OPcache extension; Use a fast
disk (or even a RAMdisk) for the `pub/static` folder.

- **Optimizing timestamps**: Within the class
  `Magento\Framework\App\View\Asset\Publisher` the actual copying takes
place. The presence of `isExists` checks whether the deployed copy
already exists, and if not, the file is copied. No further optimization
can be done here.

- **Separating different deploy destinations**: For each language /
  Store View, for either backend or frontend, the full deployment takes
place. This leads to the conclusion there should be CLI arguments to
determine which area you actually want to deploy to. For instance,
during development, if you are working on the frontend, you do not need
a deploy to the backend, or vice versa. This is currently on the list of
the Magento core team itself (according to Anton Krill).

- **Separating different deploy types**: Within the generic deployer,
  various files are copied (images, JavaScript, plain CSS, LESS), while
actually a frontend developer might only want to tune a specific type.

## Step: Pull Request for adding arguments to deployment command
@denisristic has created a PR for the Magento 2 core to add various arguments to the deployment command, so that a deploy only copies for instance theming or JavaScript.

https://github.com/magento/magento2/pull/4294

## Step: Pull Request for adding MD5 checksums
@jissereitsma has created a PR for the Magento 2 core that will check whether a file is modified or not. The original deploy only allows for file copying if the destination does not exist. This PR performs a MD5 check to see if the original is different from the deployed file. Together with @denisristic it is a perfect combination to determine which file is copied where, instead of copying all files at once.

https://github.com/magento/magento2/pull/4295

## Step: Module StaticContentDeployDebugger
This repository contains a `StaticContentDeployDebugger` module to allow you to log (with timings) which files are being copied where. Simply copy the module to `app/code/` and it should log to `var/system.log`. Do NOT enable this module on a live site.

The module dumps for each static deploy copy a line to the `system.log` mentioning the time it took to copy things (in milliseconds), the original file and the destination file. Timestamps should be around 0.2 - 0.3 ms. If it is more, there is an issue with your disk (or optimization of the filesystem). If the file already exists in the `pub/static` folder, no file is copied and log entry is made either.
