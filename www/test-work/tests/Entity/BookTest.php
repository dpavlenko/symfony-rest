<?php

namespace App\Tests\Entity;

use App\Entity\Book;
use App\Services\Locale;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{
    public function testTranslations()
    {
        $book = new Book();
        $ruName = 'Война и мир';
        $enName = 'War and peace';

        $book->translate(Locale::RU)->setName($ruName);
        $book->translate(Locale::EN)->setName($enName);

        $this->assertEquals($ruName, $book->translate(Locale::RU)->getName());
        $this->assertEquals($enName, $book->translate(Locale::EN)->getName());
    }

}