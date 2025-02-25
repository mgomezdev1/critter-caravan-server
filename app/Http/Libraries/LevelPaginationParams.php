<?php

namespace App\Http\Libraries;

enum SortBy {
    case UploadDate;
    case ModifiedDate;
    case Likes;
    case Completions;
    case Name;
}

class LevelPaginationParams
{
    public int $perPage;
    public SortBy $sortBy;
    public bool $sortAsc;
    public ?string $author;
    public ?string $category;
    public int $minVerification;
    public int $maxVerification;

    public function __construct(array $data)
    {
        $this->perPage = $data['perPage'] ?? 50;
        $this->sortBy = $this->parseSortBy($data['sort'] ?? null);
        $this->sortAsc = $data['sortAsc'] ?? false;
        $this->author = $data['author'] ?? null;
        $this->category = $data['category'] ?? null;
        $this->minVerification = $data['minVerification'] ?? 0;
        $this->maxVerification = $data['maxVerification'] ?? -1;
    }

    public function getSortAttribute()
    {
        return match($this->sortBy) {
            SortBy::ModifiedDate => 'updated_at',
            SortBy::UploadDate => 'created_at',
            SortBy::Name => 'name',
            default => 'created_at',
        };
    }

    function parseSortBy(?string $value): SortBy {
        return match($value ?? null) {
            'upload_date' => SortBy::UploadDate,
            'modified_date' => SortBy::ModifiedDate,
            'likes' => SortBy::Likes,
            'completions' => SortBy::Completions,
            'name' => SortBy::Name,
            default => SortBy::UploadDate,
        };
    }
}
