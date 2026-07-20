<?php

namespace FilamentCloudFileLinks\Support;

final class FileIcon
{
    /**
     * @var array<string, list<string>>
     */
    private const MAP = [
        'heroicon-o-document-text' => ['pdf', 'doc', 'docx', 'odt', 'rtf', 'txt', 'md'],
        'heroicon-o-table-cells' => ['xls', 'xlsx', 'csv', 'ods'],
        'heroicon-o-presentation-chart-bar' => ['ppt', 'pptx', 'odp'],
        'heroicon-o-photo' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'ico', 'heic'],
        'heroicon-o-film' => ['mp4', 'mov', 'avi', 'mkv', 'webm', 'wmv'],
        'heroicon-o-musical-note' => ['mp3', 'wav', 'ogg', 'flac', 'aac', 'm4a'],
        'heroicon-o-archive-box' => ['zip', 'rar', '7z', 'tar', 'gz', 'bz2'],
        'heroicon-o-code-bracket' => ['json', 'xml', 'html', 'htm', 'css', 'js', 'ts', 'php', 'py', 'java', 'sql'],
    ];

    public static function forName(string $name): string
    {
        $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        if ($extension === '') {
            return 'heroicon-o-link';
        }

        foreach (self::MAP as $icon => $extensions) {
            if (in_array($extension, $extensions, true)) {
                return $icon;
            }
        }

        return 'heroicon-o-document';
    }
}
