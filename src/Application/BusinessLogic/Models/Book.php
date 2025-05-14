<?php

namespace BookStore\Application\BusinessLogic\Models;

class Book
{
    private int $id;
    private string $title;
    private int $year;
    private int $author_id;

    /**
     * @param int $id
     * @param string $title
     * @param int $year
     * @param int $author_id
     */
    public function __construct(int $id, string $title, int $year, int $author_id)
    {
        $this->id = $id;
        $this->title = $title;
        $this->year = $year;
        $this->author_id = $author_id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @param int $year
     * @return void
     */
    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    /**
     * @param int $author_id
     * @return void
     */
    public function setAuthorId(int $author_id): void
    {
        $this->author_id = $author_id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'year' => $this->year,
            'author_id' => $this->author_id
        ];
    }
}