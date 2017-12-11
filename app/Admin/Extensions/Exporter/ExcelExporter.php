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
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Writers\CellWriter;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;

class ExcelExporter extends AbstractExporter
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

    private $cellKey = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    private $lastCell = 'A';

    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    public function setKeyMap(array $keyMap)
    {
        $this->keyMap = $keyMap;
        $this->only = array_keys($keyMap);
        $this->lastCell = $this->cellKey[count($this->only) - 1];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function export()
    {
        $this->grid->model()->where('status', 0);
        $filename = ($this->filename != '' ? $this->filename : $this->getTable());

        Excel::create($filename, function(LaravelExcelWriter $excel) {

            $excel->sheet('Sheet 1', function(LaravelExcelWorksheet $sheet) {
                $sheet->cells('A1:' . $this->lastCell . '1', function (CellWriter $row) {
                    $row->setFontWeight(true);
                });


                // 这段逻辑是从表格数据中取出需要导出的字段
                $rows = collect($this->getData())->map(function ($item) {
//                    dd($item);
//                    dd(array_only(array_dot($item), $this->only));
                    return array_only(array_dot($item), $this->only);
                });

                $sheet->rows($rows);

            });

        })->export('xls');
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