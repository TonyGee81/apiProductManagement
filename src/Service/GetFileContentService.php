<?php

namespace App\Service;

class GetFileContentService
{
    public function getCSVContent(string $path): bool|array
    {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException("File not found: $path");
        }

        if (!($handle = fopen($path, 'r'))) {
            throw new \RuntimeException("Unable to open file: $path");
        }

        $csvData = [];

        while (($data = fgetcsv($handle, null, ';')) !== false) {
            $csvData[] = [
                'isEuropean' => intval($data[0]),
                'country' => $data[1],
                'name' => $data[2],
                'category' => $data[3],
                'description' => $data[4],
                'code' => $data[5],
                'price' => $data[6],
            ];
        }

        fclose($handle);

        return $csvData;
    }
}
