<?php

declare(strict_types=1);

namespace Application;

/**
 * Class for processing the form.
 *
 * For the sake of this tech test, we are going to trust the file upload is
 * data to be parsed, in the format provided. In a real world application
 * we would need to properly sanitize and confirm the data prior to
 * processing. This would be vital to stop any malicious data from
 * entering our system, however I have omitted it for brevity.
 */
final class Form
{
    /** @var string $file_name - The temp file name from $_FILES. */
    protected string $file_name;

    /** @var array $data - The raw data before we process. */
    protected array $data;

    /** @var Row[] $rows */
    protected array $rows = [];

    /** @var array $errors */
    protected array $errors = [];

    /**
     * @param string $file_name
     */
    public function __construct(string $file_name)
    {
        $this->file_name = $file_name;
    }

    /**
     * Process the Data.
     *
     * @return array
     */
    public function process(): array
    {
        $this->data = array_map('str_getcsv', file($this->file_name));

        // Parse each row through into a row object.
        $this->processRows();

        return [
            // For the sake of getting array values, use JsonSerialize.
            json_decode(json_encode($this->rows), true),
            // Any errors we caught.
            $this->errors
        ];
    }

    protected function processRows(): void
    {
        // Parse each row through into a row object.
        foreach ($this->data as $num => $row) {
            try {
                // Skip the homeowner title row as provided in example file.
                if ($row[0] == 'homeowner') {
                    continue;
                }

                // Create a row object.
                $row = new Row($row);

                // Since a row can contain more than one person, merge.
                $this->rows = array_merge($this->rows, $row->getPeople());
            } catch (\Exception $e) {
                $this->errors[] = "Error on row $num: " . $e->getMessage();
            }
        }
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
