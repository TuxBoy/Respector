<?php declare(strict_types=1);

namespace Respector;

use ReflectionClass;
use ReflectionException;
use Respector\Utils\Names;

class ParsedFile
{
    public function __construct(private string $path, private string $filename)
    {
        $this->path = str_replace('\\', DIRECTORY_SEPARATOR, $this->path);
        $this->path = str_replace('/', DIRECTORY_SEPARATOR, $this->path);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getFullClass(): string
    {
        return $this->path . DIRECTORY_SEPARATOR . $this->filename;
    }

    /**
     * @throws ReflectionException
     */
    public function getClass(): ReflectionClass
    {
        $classParts = explode('src/', $this->getFullClass());
        $pathToClass = end($classParts);
        $className = Names::pathToClass($pathToClass);

        return new ReflectionClass($className);
    }
}
