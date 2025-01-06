<?php
declare(strict_types=1);

namespace App\Models;

class RecitModel implements \JsonSerializable
{
    private int $recit_id;
    private string $title;
    private string $description;
    private string $author;
    private string $creation_date;
    private ?string $last_update_date;

    public function __construct(
        int $recit_id,
        string $title,
        string $description,
        string $author,
        string $creation_date,
        ?string $last_update_date
    ) {
        $this->recit_id = $recit_id;
        $this->title = $title;
        $this->description = $description;
        $this->author = $author;
        $this->creation_date = $creation_date;
        $this->last_update_date = $last_update_date;
    }

    // Getters
    public function getRecitId(): int { return $this->recit_id; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): string { return $this->description; }
    public function getAuthor(): string { return $this->author; }
    public function getCreationDate(): string { return $this->creation_date; }
    public function getLastUpdateDate(): ?string { return $this->last_update_date; }

    // Implémentation de JsonSerializable
    public function jsonSerialize(): array
    {
        return [
            'recit_id' => $this->recit_id,
            'title' => $this->title,
            'description' => $this->description,
            'author' => $this->author,
            'creation_date' => $this->creation_date,
            'last_update_date' => $this->last_update_date,
        ];
    }
}
