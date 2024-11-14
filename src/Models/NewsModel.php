<?php
declare(strict_types=1);

namespace App\Models;

class NewsModel
{
    private ?int $news_id;
    private string $title;
    private string $content;
    private string $creation_date;
    private ?string $last_update_date;
    private int $author_id;
    private bool $isVisible;

    public function __construct(?int $news_id, string $title, string $content, string $creation_date, ?string $last_update_date, int $author_id, bool $isVisible)
    {
        $this->news_id = $news_id;
        $this->title = $title;
        $this->content = $content;
        $this->creation_date = $creation_date;
        $this->last_update_date = $last_update_date;
        $this->author_id = $author_id;
        $this->isVisible = $isVisible;
    }

    public function getNewsId(): ?int { return $this->news_id; }
    public function getTitle(): string { return $this->title; }
    public function getContent(): string { return $this->content; }
    public function getCreationDate(): string { return $this->creation_date; }
    public function getLastUpdateDate(): ?string { return $this->last_update_date; }
    public function getAuthorId(): int { return $this->author_id; }
    public function isVisible(): bool { return $this->isVisible; }

    public function setTitle(string $title): void { $this->title = $title; }
    public function setContent(string $content): void { $this->content = $content; }
    public function setCreationDate(string $creation_date): void { $this->creation_date = $creation_date; }
    public function setLastUpdateDate(?string $last_update_date): void { $this->last_update_date = $last_update_date; }
    public function setAuthorId(int $author_id): void { $this->author_id = $author_id; }
    public function setIsVisible(bool $isVisible): void { $this->isVisible = $isVisible; }

    public function toArray(): array
    {
        return [
            'news_id' => $this->getNewsId(),
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
            'creation_date' => $this->getCreationDate(),
            'last_update_date' => $this->getLastUpdateDate(),
            'author_id' => $this->getAuthorId(),
            'isVisible' => $this->isVisible(),
        ];
    }
}



