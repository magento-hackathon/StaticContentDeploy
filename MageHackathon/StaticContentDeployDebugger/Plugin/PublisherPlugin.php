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
        $dir = $this->filesystem->getDirectoryRead(DirectoryList::STATIC_VIEW);
        if ($dir->isExist($asset->getPath())) {
            return array($asset);
        }

        $rootDir = $this->filesystem->getDirectoryWrite(DirectoryList::ROOT);
        $this->source = $rootDir->getRelativePath($asset->getSourceFile());
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

        $message = '[static deploy]';
        $message .= '['.$timeElapsed.'] ';
        $message .= 'from '. $this->source;
        $message .= 'to '. $this->destination;
        $this->logger->info($message);

        return $return;
    }
}