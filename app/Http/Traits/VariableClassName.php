<?php

namespace App\Http\Traits;

trait VariableClassName
{
    protected $className;

    protected function setNamespace(string $namespace)
    {
        $this->className = $namespace . '\\';
    }

    protected function setClassname(string $className)
    {
        $this->className .= ucfirst($className);
    }

    protected function getClassName(string $namespace, string $className)
    {
        $this->setNamespace($namespace);
        $this->setClassname($className);

        return $this->className;
    }
}
