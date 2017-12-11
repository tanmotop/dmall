<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/12/5
 * Time: 15:29
 * Function:
 */

namespace App\Admin\Extensions\Exporter;


use Encore\Admin\Grid\Exporters\AbstractExporter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;

class XCsvExporter extends AbstractExporter
{
    /**
     * @var array
     */
    private $keyMap = [];

    /**
     * @var array
     */
    private $only = [];

    /**
     * @var string
     */
    private $filename = '';

    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    public function setKeyMap(array $keyMap)
    {
        $this->keyMap = $keyMap;
        $this->only = array_keys($keyMap);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function export()
    {
        $filename = ($this->filename != '' ? $this->filename : $this->getTable()) . '.csv';

        $headers = [
            'Content-Encoding'    => 'UTF-8',
            'Content-Type'        => 'text/csv;charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        response()->stream(function () {
            $handle = fopen('php://output', 'w');

            $titles = [];
            fputcsv($handle, array_values($this->keyMap));

            collect($this->getData())->map(function ($item) use ($handle) {
                fputcsv($handle, array_values(array_only(array_dot($item), $this->only)));
//                return array_only(array_dot($item), $this->only);
            });

            // Close the output stream
            fclose($handle);
        }, 200, $headers)->send();

        exit;
    }

    /**
     * @param Collection $records
     *
     * @return array
     */
    public function getHeaderRowFromRecords(Collection $records): array
    {
        $titles = collect(array_dot($records->first()->toArray()))->keys()->map(
            function ($key) {
                $key = str_replace('.', ' ', $key);

                return $key;
            }
        );

        return $titles->toArray();
    }

    /**
     * @param Model $record
     *
     * @return array
     */
    public function getFormattedRecord(Model $record)
    {
        return array_dot($record->getAttributes());
    }
}