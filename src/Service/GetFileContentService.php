<?php

namespace App\Service;

class GetFileContentService
{
    public function getCSVContent(string $path): bool|array
    {
        if (!($handle = fopen($path, "r"))) {
            return false;
        }

        $csvData = [];

        while (($data = fgetcsv($handle)) !== false) {

            $csvData[] = [
                'code' => $data[1],
                'description' => $data[0],
                'price' => $data[2]
            ];

        }

        fclose($handle);

        return $csvData;

    }
}