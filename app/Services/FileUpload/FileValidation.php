<?php


namespace App\Services\FileUpload;


class FileValidation
{

    public static function fileType($mimeType): ?string
    {
        return match ($mimeType) {
            // Video files
            strstr($mimeType, "video/") => "video",
            // Image files
            strstr($mimeType, "image/gif"),
            strstr($mimeType, "image/jpe"),
            strstr($mimeType, "image/jpeg"),
            strstr($mimeType, "image/jpg"),
            strstr($mimeType, "image/png"),
            strstr($mimeType, "image/bmp") => "image",
            // Audio files
            strstr($mimeType, "audio/") => "audio",
            // Text/code files
            strstr($mimeType, "text/") => "text",
            // Compressed files: zip, rar etc
            strstr($mimeType, "application/x-gtar"),
            strstr($mimeType, "application/zip"),
            strstr($mimeType, "application/x-rar-compressed"),
            strstr($mimeType, "application/x-7z-compressed") => "compressed",
            default => null,
        };

    }

    public static function isCodeFile($extension): ?array
    {
        $types = [
            'ABAP'                => 'abap',
            'Windows Bat'         => 'bat',
            'BibTeX'              => 'bibtex',
            'Clojure'             => 'clojure',
            'Coffeescript'        => 'coffeescript',
            'C'                   => 'c',
            'C++'                 => 'cpp',
            'C#'                  => 'csharp',
            'CSS'                 => 'css',
            'Diff'                => 'diff',
            'Dockerfile'          => 'dockerfile',
            'F#'                  => 'fsharp',
            'Git'                 => ['git-commit', 'git-rebase'],
            'Go'                  => 'go',
            'Groovy'              => 'groovy',
            'Handlebars'          => 'handlebars',
            'HTML'                => 'html',
            'Ini'                 => 'ini',
            'Java'                => 'java',
            'JavaScript'          => ['javascript', 'js'],
            'JSON'                => 'json',
            'JSON with Comments'  => 'jsonc',
            'LaTeX'               => 'latex',
            'Less'                => 'less',
            'Lua'                 => 'lua',
            'Makefile'            => 'makefile',
            'Markdown'            => 'markdown',
            'Objective-C'         => 'objective-c',
            'Objective-C++'       => 'objective-cpp',
            'Perl'                => ['perl', 'perl6'],
            'PHP'                 => 'php',
            'Powershell'          => 'powershell',
            'Pug'                 => 'jade',
            'Python'              => ['python', 'py'],
            'R'                   => 'r',
            'Razor (cshtml)'      => 'razor',
            'Ruby'                => 'ruby',
            'Rust'                => 'rust',
            'SCSS'                => ['scss', 'sass'],
            'ShaderLab'           => 'shaderlab',
            'Shell Script (Bash)' => 'shellscript',
            'SQL'                 => 'sql',
            'Swift'               => 'swift',
            'TypeScript'          => ['typescript', 'ts'],
            'TeX'                 => 'tex',
            'Visual Basic'        => 'vb',
            'XML'                 => 'xml',
            'XSL'                 => 'xsl',
            'YAML'                => 'yaml',
            'VueJS'               => 'vue',
            'ReactJS'             => 'jsx',
        ];

        $extension = \strtolower($extension);

        foreach ($types as $extensionName => $extensionType) {
            if (is_array($extensionType)) {
                if (in_array($extension, $extensionType)) {
                    return [
                        'name'      => $extensionName,
                        'extension' => $extensionType,
                    ];
                }
            } else {
                if ($extensionType === $extension) {
                    return [
                        'name'      => $extensionName,
                        'extension' => $extensionType,
                    ];
                }
            }
        }

        return null;
    }

}
