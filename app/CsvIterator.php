<?php

namespace app;


class CsvIterator
{
    /**
     * @var string
     */
    private $delimiter;
    /**
     * @var int
     */
    private $lineLength;

    /**
     * @param string $delimiter
     * @param int $lineLength
     */
    public function __construct(string $delimiter = ',', int $lineLength = 1000)
    {
        $this->delimiter  = $delimiter;
        $this->lineLength = $lineLength;
    }

    /**
     * @param string $filename
     * @return \Generator
     */
    public function read(string $filename)
    {
        $fp = fopen($filename, 'r');
        if ($fp === false) {
            throw new \RuntimeException(sprintf('Could not open file "%s" for reading', $filename));
        }

        while (($row = fgetcsv($fp, $this->lineLength, $this->delimiter)) !== false) {
            yield $row;
        }
        fclose($fp);
    }

    /**
     * @param string $filename
     * @return \Generator
     */
    public function readColumn(string $filename)
    {
        foreach ($this->read($filename) as $row) {
            yield reset($row);
        }
    }
}