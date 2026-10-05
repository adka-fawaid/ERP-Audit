<?php

namespace App\Exports;

use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class ExcelFile
{
    public static function download(string $filename, array $sheets)
    {
        $path = tempnam(sys_get_temp_dir(), 'qad-export-');
        $writer = new Writer();

        try {
            $writer->openToFile($path);

            foreach ($sheets as $index => $sheet) {
                $currentSheet = $index === 0
                    ? $writer->getCurrentSheet()
                    : $writer->addNewSheetAndMakeItCurrent();
                $currentSheet->setName($sheet['name']);
                $writer->addRow(Row::fromValues($sheet['rows'][0]));

                foreach (array_slice($sheet['rows'], 1) as $row) {
                    $writer->addRow(Row::fromValues(array_map(static function ($value) {
                        if (is_string($value) && preg_match('/^\s*[=+@-]/u', $value)) {
                            return "'" . $value;
                        }

                        return $value;
                    }, $row)));
                }
            }

            $writer->close();
        } catch (\Throwable $exception) {
            if (is_file($path)) {
                unlink($path);
            }

            throw $exception;
        }

        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}