<?php
// app/Enums/ArticleContentElementType.php
namespace App\Enums;

enum ArticleContentElementType: string
{
    case Heading = 'heading';
    case Paragraph = 'paragraph';
    case Image = 'image';
    case Quote = 'quote';
    case List = 'list';

    case Video = 'video';
}
