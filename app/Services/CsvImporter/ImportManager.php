<?php namespace App\Services\CsvImporter;

use Maatwebsite\Excel\Excel;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Services\CsvImporter\Queue\Contracts\ProcessorInterface;

class ImportManager
{
    /**
     * @var Excel
     */
    protected $excel;

    /**
     * @var ProcessorInterface
     */
    protected $processor;

    public function __construct(Excel $excel, ProcessorInterface $processor)
    {
        $this->excel     = $excel;
        $this->processor = $processor;
    }

    public function import(UploadedFile $file)
    {
        $csv = $this->excel->load($file)->get();

        dd($csv);

    }
}
