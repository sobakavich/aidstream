<?php namespace App\Services\CsvImporter\Queue;

use App\Services\CsvImporter\CsvResultProcessor;
use App\Services\CsvImporter\Queue\Exceptions\HeaderMismatchException;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Services\CsvImporter\Queue\Jobs\ImportActivity;
use App\Services\CsvImporter\Queue\Contracts\ProcessorInterface;
use Maatwebsite\Excel\Excel;

/**
 * Class Processor
 * @package App\Services\CsvImporter\Queue
 */
class ResultProcessor implements ProcessorInterface
{
    use DispatchesJobs;

    /**
     * @var ImportActivity
     */
    protected $importActivity;

    /**
     * @var Excel
     */
    protected $csvReader;

    /**
     * Total no. of header present in basic csv.
     */
    const CSV_HEADERS_COUNT = 33;

    /**
     * Processor constructor.
     * @param Excel $csvReader
     */
    public function __construct(Excel $csvReader)
    {
        $this->csvReader = $csvReader;
    }

    /**
     * Push a CSV file's data for processing into Queue.
     * @param $file
     * @param $filename
     */
    public function pushIntoQueue($file, $filename)
    {
        $csv = $this->csvReader->load($file)->toArray();

        $this->dispatch(
            new ImportActivity(new CsvResultProcessor($csv), $filename)
        );
    }


    /**
     * {@inheritdoc}
     */
    public function isCorrectCsv($csv)
    {
        $csvHeaders = array_keys($csv[0]);

        if (count($csvHeaders) == self::CSV_HEADERS_COUNT) {
            $templateHeaders = $this->loadCsv('V201', 'result');
            $templateHeaders = array_keys($templateHeaders[0]);
            $diffHeaders     = array_diff($csvHeaders, $templateHeaders);

            return $this->isSameCsvHeader($diffHeaders);
        }

        throw new HeaderMismatchException();
    }

    /**
     * Load Csv template
     * @param $version
     * @param $filename
     * @return array
     */
    protected function loadCsv($version, $filename)
    {
        $file = $this->csvReader->load(app_path(sprintf('Services/CsvImporter/Templates/Activity/%s/%s.csv', $version, $filename)));

        return $file->toArray();
    }

    /**
     * Check if the difference of the csv headers is empty.
     * @param array $diffHeaders
     * @return bool
     * @throws HeaderMismatchException
     */
    protected function isSameCsvHeader(array $diffHeaders)
    {
        if (empty($diffHeaders)) {
            return true;
        }

        throw new HeaderMismatchException();
    }
}
