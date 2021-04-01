<?php

namespace Hyperf\DTO\Scan;

class PropertyManager
{
    /**
     * @var array
     */
    public static array $content = [];

    public static array $notSimpleClass = [];

    public static function setNotSimpleClass($className)
    {
        $className = trim($className,'\\');
        static::$notSimpleClass[$className] = true;
    }

    public static function setContent(string $className,string $fieldName,Property $property)
    {
        $className = trim($className,'\\');
        if(isset(static::$content[$className][$fieldName])){
            return;
        }
        static::$content[$className][$fieldName] = $property;
    }

    public static function getProperty($className,$fieldName) :? Property
    {
        $className = trim($className,'\\');
        if(!isset(static::$content[$className][$fieldName])){
            return null;
        }
        return static::$content[$className][$fieldName];
    }

    public static function getPropertyByType($className,$type,bool $isSimpleType) : array
    {
        $className = trim($className,'\\');
        if(!isset(static::$content[$className])){
            return [];
        }
        $data = [];
        foreach (static::$content[$className] as $fieldName => $propertyArr) {
            /** @var Property $property */
            foreach ($propertyArr as $property) {
                if ($property->type == $type
                    && $property->isSimpleType == $isSimpleType
                ) {
                    $data[$fieldName] = $property;
                }
            }
        }
        return $data;
    }

    /**
     * @param $className
     * @return Property[]
     */
    public static function getPropertyAndNotSimpleType($className) : array
    {
        $className = trim($className,'\\');
        if(!isset(static::$notSimpleClass[$className])){
            return [];
        }
        $data = [];
        foreach (static::$content[$className] as $fieldName => $property) {
            if ($property->isSimpleType == false) {
                $data[$fieldName] = $property;
            }
        }
        return $data;
    }
}