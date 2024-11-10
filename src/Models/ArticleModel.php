<?php
declare(strict_types=1);

class ArticleModel implements JsonSerializable {
    private ?int $id;
    private int $tocId;
    private string $title;
    private string $content;
    private int $authorId;
    private string $creationDate;
    private string $lastUpdateDate;

    // Constructeur avec tous les champs nécessaires
    public function __construct(
        ?int $id,
        int $tocId,
        string $title,
        string $content,
        int $authorId,
        string $creationDate,
        string $lastUpdateDate
    ) {
        $this->id = $id;
        $this->tocId = $tocId;
        $this->title = $title;
        $this->content = $content;
        $this->authorId = $authorId;
        $this->creationDate = $creationDate;
        $this->lastUpdateDate = $lastUpdateDate;
    }

    // Méthode pour la sérialisation JSON
    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'toc_id' => $this->tocId,
            'title' => $this->title,
            'content' => $this->content,
            'author_id' => $this->authorId,
            'creation_date' => $this->creationDate,
            'last_update_date' => $this->lastUpdateDate
        ];
    }

    // Getters et Setters
    public function getId(): ?int {
        return $this->id;
    }

    public function getTocId(): int {
        return $this->tocId;
    }

    public function setTocId(int $tocId): void {
        $this->tocId = $tocId;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function setContent(string $content): void {
        $this->content = $content;
    }

    public function getAuthorId(): int {
        return $this->authorId;
    }

    public function setAuthorId(int $authorId): void {
        $this->authorId = $authorId;
    }

    public function getCreationDate(): string {
        return $this->creationDate;
    }

    public function setCreationDate(string $creationDate): void {
        $this->creationDate = $creationDate;
    }

    public function getLastUpdateDate(): string {
        return $this->lastUpdateDate;
    }

    public function setLastUpdateDate(string $lastUpdateDate): void {
        $this->lastUpdateDate = $lastUpdateDate;
    }
}
