<?php

namespace App\Service;

class GetFileContentService
{
    public function getCSVContent(string $path): bool|array
    {
        if (!($handle = fopen($path, 'r'))) {
            return false;
        }

        $csvData = [];

        while (($data = fgetcsv($handle, null, ';')) !== false) {
            $csvData[] = [
                'isEuropean' => intval($data[0]),
                'country' => $data[1],
                'name' => $data[2],
                'description' => $data[3],
                'code' => $data[4],
                'price' => $data[5],
            ];
        }

        fclose($handle);

        return $csvData;
    }
}
