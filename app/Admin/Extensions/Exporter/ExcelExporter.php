<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/12/5
 * Time: 15:29
 * Function:
 */

namespace App\Admin\Extensions\Exporter;


use Encore\Admin\Grid;
use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Writers\CellWriter;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Closure;

class ExcelExporter extends AbstractExporter
{
    /**
     * @var string
     */
    private $filename = '';

    /**
     * 列编号
     *
     * @var string
     */
    private $cellKey = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * @var Closure
     */
    private $callback;

    /**
     * 标题行
     *
     * @var array
     */
    private $header = [];

    /**
     * ExcelExporter constructor.
     * @param Grid|null $grid
     * @param Closure $callback
     */
    public function __construct(Grid $grid = null, Closure $callback)
    {
        parent::__construct($grid);

        if ($callback instanceof Closure) {
            $callback($this);
        }
    }

    /**
     * 行处理
     *
     * @param Closure $callback
     * @return $this
     */
    public function rowHandle(Closure $callback)
    {
        $this->callback = $callback;

        return $this;
    }

    public function setHeader(array $header)
    {
        $this->header = $header;
    }

    /**
     * @param $filename
     * @return $this
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return \Encore\Admin\Grid\Model
     */
    public function model()
    {
        return $this->grid->model();
    }

    /**
     * {@inheritdoc}
     */
    public function export()
    {
        $filename = ($this->filename != '' ? $this->filename : $this->getTable());

        ///
        if (! ($this->callback instanceof Closure)) {
            throw new \Exception('Undefined Callback Function!');
        }

        ///
        $callback = $this->callback;
        Excel::create($filename, function(LaravelExcelWriter $excel) use ($callback) {
            $excel->sheet('Sheet 1', function(LaravelExcelWorksheet $sheet) use ($callback) {
                /// 设置列宽
                $count = count($this->header);
                $columnsWidth = [];
                for ($i = 0; $i < $count; $i++) {
                    $columnsWidth[$this->cellKey[$i]] = 15;
                }
                $sheet->setWidth($columnsWidth);

                /// 设置头部标题
                if (!empty($this->header) && is_array($this->header)) {
                    /// 冻结第一行
                    $sheet->freezeFirstRow();

                    /// 设置第一行为粗体
                    $lastCell = $this->cellKey[$count -1];
                    $sheet->cells("A1:{$lastCell}1", function (CellWriter $row) {
                        $row->setFontWeight(true);
                    });

                    ///
                    $sheet->row(1, $this->header);
                }

                // 这段逻辑是从表格数据中取出需要导出的字段
                $rows = collect($this->getData())->map(function ($item) use ($callback) {
                    return $callback($item);
                });

                $sheet->rows($rows);
            });
        })->export('xls');
    }
}