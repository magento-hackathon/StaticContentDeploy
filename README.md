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

## Module StaticContentDeployDebugger
This repository contains a `StaticContentDeployDebugger` module to allow you to log (with timings) which files are being copied where. Simply copy the module to `app/code/` and it should log to `var/system.log`. Do NOT enable this module on a live site.

The module dumps for each static deploy copy a line to the `system.log` mentioning the time it took to copy things (in milliseconds), the original file and the destination file. Timestamps should be around 0.2 - 0.3 ms. If it is more, there is an issue with your disk (or optimization of the filesystem).
