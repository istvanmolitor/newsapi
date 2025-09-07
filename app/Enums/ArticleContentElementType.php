<?php
// app/Enums/ArticleContentElementType.php
namespace App\Enums;

enum ArticleContentElementType: string
{
    case Paragraph = 'paragraph';
    case Image = 'image';
    case Quote = 'quote';
}
