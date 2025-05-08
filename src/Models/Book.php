<?php

namespace BookStore\Models;

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

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    public function setAuthorId(int $author_id): void
    {
        $this->author_id = $author_id;
    }




}