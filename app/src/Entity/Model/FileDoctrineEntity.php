<?php

namespace App\Entity\Model;


use App\Traits\TimestampableTrait;
use App\Utils\UtilsStr;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * FileDoctrineEntity
 * @ORM\MappedSuperclass
 */
abstract class FileDoctrineEntity implements FileDoctrineEntityInterface
{
    use TimestampableTrait;

    const BLACKLIST_NORMALIZE_FIELDS = ["dir", "dirPath", "filePath", "mimeType", "downloadName"];

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    protected string $path;

    /**
     * @var File
     */
    protected File $file;

    /**
     * @var string
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected string $clientName;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->updatedAt = new \DateTime();
        $this->createdAt = new \DateTime();
    }

    public function getName(): string
    {
        return $this->getClientName() ?? UtilsStr::lastNamespaceItem($this->getPath());
    }

    /**
     * @return string
     */
    public function getClientName(): string
    {
        return $this->clientName;
    }

    /**
     * @param string $clientName
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;
    }

    /**
     * GetDir
     *
     * @return string
     */
    public function getDir(): string
    {
        return 'private/';
    }

    /**
     * GetDirPath
     * @param boolean $absolute
     * @return string
     */
    public function getDirPath($absolute = true): string
    {
        if ($absolute)
            return __DIR__ . '/../../../' . $this->getDir();
        else
            return $this->getDir();
    }

    /**
     * GetFilePath
     *
     * @param boolean $absolute
     *
     * @return string
     */
    public function getFilePath($absolute = true): string
    {
        return $this->getDirPath($absolute) . $this->getPath();
    }

    /**
     * @param $path
     * @return $this
     */
    public function setPath($path): FileDoctrineEntity
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Set file
     *
     * @param $file
     *
     * @return FileDoctrineEntity
     */
    public function setFile($file = null): FileDoctrineEntity
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return File
     */
    public function getFile(): File
    {
        return $this->file;
    }
}
