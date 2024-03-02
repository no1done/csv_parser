<?php

declare(strict_types=1);

namespace Application;

/**
 * Object representing a CSV Row
 *
 * This class is responsible for parsing the row into the desired names
 * we need.
 */
final class Person implements \JsonSerializable
{
    public const string TITLE_MR     = 'Mr';
    public const string TITLE_MRS    = 'Mrs';
    public const string TITLE_MISS   = 'Miss';
    public const string TITLE_MS     = 'Ms';
    public const string TITLE_MISTER = 'Mister';
    public const string TITLE_DR     = 'Dr';
    public const string TITLE_PROF   = 'Prof';
    public const string BLANK_SPACE  = '';

    /** @var array An array of accepted titles. */
    public const array ACCEPTED_TITLES = [
        self::TITLE_MR,
        self::TITLE_MRS,
        self::TITLE_MISS,
        self::TITLE_MS,
        self::TITLE_MISTER,
        self::TITLE_DR,
        self::TITLE_PROF
    ];

    public const array TITLE_REPLACE_ARRAY = [
        self::TITLE_MR      => self::BLANK_SPACE,
        self::TITLE_MRS     => self::BLANK_SPACE,
        self::TITLE_MISS    => self::BLANK_SPACE,
        self::TITLE_MS      => self::BLANK_SPACE,
        self::TITLE_MISTER  => self::BLANK_SPACE,
        self::TITLE_DR      => self::BLANK_SPACE,
        self::TITLE_PROF    => self::BLANK_SPACE,
    ];

    /** @var string $title Required title. */
    protected string $title;

    /** @var string|null $first_name Optional first name. */
    protected ?string $first_name = null;

    /** @var string|null $initial Optional initial. */
    protected ?string $initial = null;

    /** @var string $surname Required surname */
    protected string $surname;

    /**
     * Optionally build from string if provided.
     * @param string|null $name
     */
    public function __construct(string $name = null)
    {
        if ($name) {
            $this->fromString($name);
        }
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Person
     */
    public function setTitle(string $title): Person
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    /**
     * @param string|null $first_name
     * @return Person
     */
    public function setFirstName(?string $first_name): Person
    {
        $this->first_name = $first_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getInitial(): ?string
    {
        return $this->initial;
    }

    /**
     * @param string|null $initial
     * @return Person
     */
    public function setInitial(?string $initial): Person
    {
        $this->initial = $initial;
        return $this;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     * @return Person
     */
    public function setSurname(string $surname): Person
    {
        $this->surname = $surname;
        return $this;
    }

    public function fromString(string $name): void
    {
        // Find and Remove the title.
        $this->setTitle($this->getTitleFromString($name));

        // Remove the title from the string.
        $name = $this->removeStringTitle($name);

        // Explode remaining name parts
        $nameParts = explode(' ', trim($name));

        // If we only have one part, it's the REQUIRED surname
        if (count($nameParts) == 1) {
            $this->setSurname($nameParts[0]);
        } else {
            // Trim spaces and any periods from first part.
            $trimmed = trim(strtr($nameParts[0], ['.' => '']));

            if (strlen($trimmed) == 1) {
                // If length is only one character, set as initial.
                $this->setInitial($trimmed);
            } else {
                // Else set as first name.
                $this->setFirstName($nameParts[0]);
            }

            // Set the surname.
            $this->setSurname($nameParts[1]);
        }
    }

    /**
     * Get valid title from string.
     * @param string $name
     * @return string
     */
    public function getTitleFromString(string $name): string
    {
        // Find and Remove the title.
        foreach (self::ACCEPTED_TITLES as $title) {
            if (str_contains(strtolower($name), strtolower($title . " "))) {
                return $title;
            }
        }
        throw new \InvalidArgumentException('Title is required.');
    }

    /**
     * Remove upper and lower case titles.
     * @param string $name
     * @return string
     */
    public function removeStringTitle(string $name): string
    {
        $uc = $this->getTitle() . " ";
        $lc = strtolower($this->getTitle());
        return str_replace([$uc, $lc], '', $name);
    }

    /**
     * Export data to array
     * @return array
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        return [
            'title' => $this->getTitle(),
            'first_name' => $this->getFirstName(),
            'initial' => $this->getInitial(),
            'surname' => $this->getSurname()
        ];
    }
}
