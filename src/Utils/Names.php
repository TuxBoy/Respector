<?php declare(strict_types=1);

namespace Respector\Utils;

abstract class Names
{
    /**
     * @return object the class name with your namespace
     *
     * @example "src/path/to/Class.php" => "Path\To\Class"
     */
    public static function pathToClass(string $classPath): object
    {
        $classPath = str_replace('src/', '', $classPath);
        $classPath = str_replace('/', '\\', $classPath);
        $className = str_replace('.php', '', $classPath);
        if (! class_exists($className)) {
            throw new \Exception(sprintf('The class does not exist with %s path.', $classPath));
        }

        return new $className;
    }
}
