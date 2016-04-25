<?php
namespace MageHackathon\StaticContentDeployDebugger\Plugin;

use Magento\Framework\App\View\Asset\Publisher;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\View\Asset;

class PublisherPlugin
{
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @var \Magento\Framework\File\Size
     */
    protected $fileSize;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var Asset\LocalInterface
     */
    protected $asset;

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
        \Magento\Framework\File\Size $fileSize,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->filesystem = $filesystem;
        $this->fileSize = $fileSize;
        $this->logger = $logger;
    }

    public function beforePublish(Publisher $subject, Asset\LocalInterface $asset)
    {
        $this->asset = $asset;
        $this->source = $asset->getSourceFile();
        $this->destination = $asset->getPath();
        $this->timer = microtime(true);
        $this->logger->info('test');

        return array($asset);
    }

    public function afterPublish(Publisher $subject, $return)
    {
        if (empty($this->timer)) {
            return $return;
        }

        $timeElapsed = round(microtime(true) * 1000 - $this->timer * 1000, 3).'ms';
        $sourceFile = $this->asset->getSourceFile();
        $sourceSize = filesize($sourceFile);
        $fileSize = $this->fileSize->getFileSizeInMb($sourceSize, 3);

        $message = '[static deploy]';
        $message .= '['.$timeElapsed.' / '.$fileSize.'Mb]';
        $message .= ' from '. $this->source;
        $message .= ' to '. $this->destination;
        $this->logger->info($message);

        return $return;
    }
}