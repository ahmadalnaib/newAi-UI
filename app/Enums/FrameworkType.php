<?php
namespace App\Enums;

enum FrameworkType: string {
    case TailwindCSS = 'Tailwind CSS';
    case Bootstrap = 'Bootstrap';
    case HmtlCss = 'HTML & CSS';

    public static function toArray(): array 
    {
        return [
            self::TailwindCSS,
            self::Bootstrap,
            self::HmtlCss,
     
        ];
    }
}