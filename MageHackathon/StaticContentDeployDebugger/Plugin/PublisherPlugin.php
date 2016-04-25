<?php
namespace MageHackathon\StaticContentDeployDebugger\Plugin;

use Magento\Framework\App\View\Asset\Publisher;
use Magento\Framework\View\Asset;

class PublisherPlugin
{
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $source;

    /**
     * @var string
     */
    protected $destination;

    /**
     * @var double
     */
    protected $timer;

    public function __construct(
        \Magento\Framework\Filesystem $filesystem,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->filesystem = $filesystem;
        $this->logger = $logger;
    }

    public function beforePublish(Publisher $subject, Asset\LocalInterface $asset)
    {
        $this->logger->info('test');
        //$rootDir = $this->filesystem->getDirectoryWrite(DirectoryList::ROOT);
        //$this->source = $rootDir->getRelativePath($asset->getSourceFile());
        //$this->destination = $asset->getPath();
        //$this->timer = microtime(true);

        //$this->logger->info('Publisher::publishAsset > source = '.$source);
        //$this->logger->info('Publisher::publishAsset > destination = '.$destination);

        return array($asset);
    }
}