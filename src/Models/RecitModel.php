<?php
declare(strict_types=1);

class RecitModel implements JsonSerializable {
    private ?int $id;
    private string $title;
    private string $description;
    private string $slug;
    private int $author_id;
    private string $creation_date;
    private string $last_update_date;

    // Constructeur avec tous les champs nécessaires
    public function __construct(?int $id, string $title, string $description, string $slug, int $author_id, string $creation_date, string $last_update_date) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->slug = $slug;
        $this->author_id = $author_id;
        $this->creation_date = $creation_date;
        $this->last_update_date = $last_update_date;
    }

    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'slug' => $this->slug,
            'author_id' => $this->author_id,
            'creation_date' => $this->creation_date,
            'last_update_date' => $this->last_update_date,
        ];
    }

    // Getters et Setters pour chaque propriété
    public function getId(): ?int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): string { return $this->description; }
    public function getSlug(): string { return $this->slug; }
    public function getAuthorId(): int { return $this->author_id; }
    public function getCreationDate(): string { return $this->creation_date; }
    public function getLastUpdateDate(): string { return $this->last_update_date; }

    public function setTitle(string $title): void { $this->title = $title; }
    public function setDescription(string $description): void { $this->description = $description; }
    public function setSlug(string $slug): void { $this->slug = $slug; }
    public function setAuthorId(int $author_id): void { $this->author_id = $author_id; }
    public function setLastUpdateDate(string $last_update_date): void { $this->last_update_date = $last_update_date; }
}
?>
