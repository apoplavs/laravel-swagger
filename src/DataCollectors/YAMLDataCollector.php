<?php

namespace RonasIT\Support\AutoDoc\DataCollectors;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use RonasIT\Support\AutoDoc\Interfaces\DataCollectorInterface;
use RonasIT\Support\AutoDoc\Exceptions\MissedProductionFilePathException;

class YAMLDataCollector implements DataCollectorInterface
{
    public $prodFilePath;

    protected static $data;

    public function __construct()
    {
        $this->prodFilePath = config('local-data-collector.production_path');

        if (empty($this->prodFilePath)) {
            throw new MissedProductionFilePathException();
        }
    }

    public function saveTmpData($tempData)
    {
        self::$data = $tempData;
    }

    public function getTmpData()
    {
        return self::$data;
    }

    public function saveData()
    {
        yaml_emit_file($this->prodFilePath, self::$data);

        self::$data = [];
    }

    public function getDocumentation()
    {
        if (!file_exists($this->prodFilePath)) {
            throw new FileNotFoundException();
        }

        return yaml_parse_file($this->prodFilePath);
    }
}
