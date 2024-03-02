<?php

declare(strict_types=1);

namespace Application;

/**
 * Object representing a CSV Row
 *
 * This class is responsible for parsing the row into the desired names
 * we need.
 */
final class Row
{
    public const string CON_AND = ' and ';
    public const string CON_AMP = ' & ';

    /** @var array|string[] */
    public const array CONJUNCTIONS = [
        self::CON_AND,
        self::CON_AMP
    ];

    /** @var string $row - The row string from CSV */
    protected mixed $row;

    /** @var ?string $conjunction The conjunction being used in this row. */
    protected ?string $conjunction = null;

    /**
     * Parsed will contain a single dimension array of names that were
     * found within this row.
     * @var Person[] $people
     */
    protected array $people = [];

    /**
     * @param mixed $row
     */
    public function __construct(mixed $row)
    {
        $this->row = $row;
        $this->parse();
    }

    /**
     * Parse the row
     *
     * This function will parse one row at a time which can contain
     * on or more names. If we have an array from fgetcsv, break it
     * down into a string with & separators.
     * @return void
     */
    public function parse(): void
    {
        // Convert to string if it's an array.
        if (is_array($this->row)) {
            $this->row = $this->arrayToString($this->row);
        }

        // Create the Person objects.
        $this->setupPeople();
    }

    /**
     * Setup People
     *
     * The person class will handle breaking a name string down
     * into the correct parts, but we need to manipulate the string
     * so that we can pass it the correct information in the instance
     * we have two titles but one shared name.
     * @return void
     */
    protected function setupPeople(): void
    {
        // Total people on this row.
        $totalPeople = $this->countPeople();

        if ($totalPeople < 2) {
            $person = new Person($this->row);
            $this->people[] = $person;
        } else {
            $this->setupMultiPeople();
        }
    }

    /**
     * Count number of people on row.
     * @return int
     */
    protected function countPeople(): int
    {
        foreach (self::CONJUNCTIONS as $conjunction) {
            $count = substr_count($this->row, $conjunction);
            if ($count > 0) {
                $this->conjunction = $conjunction;
                // Number of people is always conjunction plus 1.
                return $count + 1;
            }
        }
        return 0;
    }

    /**
     * Process a row with multiple people.
     *
     * For this function to work, we explode on the conjunction used (and
     * or &) and break down the names to what we need to process. If
     * we have a part name which is only the title, then fetch the
     * information from the next item in array.
     * @return void
     */
    protected function setupMultiPeople(): void
    {
        $names = explode($this->conjunction, $this->row);
        foreach ($names as $key => $name) {
            if (in_array(trim($name), Person::ACCEPTED_TITLES)) {
                /*
                 * We only have a title, so fetch the name from
                 * the next array up.
                 */
                $add = strtr($names[($key + 1)], Person::TITLE_REPLACE_ARRAY);
                $name .= $add;
            }
            $person = new Person($name);
            $this->people[] = $person;
        }
    }

    /**
     * Convert array to string
     *
     * Parsing using fgetcsv is giving me a double array with
     * the provided CSV file. While the second element is
     * always empty this time, parsing in scenario it
     * might occasionally contain another name.
     * @param array $row
     * @return string
     */
    public function arrayToString(array $row): string
    {
        $str = '';
        $first = true;
        foreach ($row as $item) {
            // Add in an ampersand so we can split later.
            if (!$first && $item !== '') {
                $str .= " & ";
            }
            $str .= $item;
            $first = false;
        }
        return $str;
    }

    public function getPeople(): array
    {
        return $this->people;
    }
}
