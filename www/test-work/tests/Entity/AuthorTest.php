<?php

namespace App\Tests\Entity;

use App\Entity\Author;
use App\Services\Locale;

use PHPUnit\Framework\TestCase;

class AuthorTest extends TestCase
{

    public function testTranslations()
    {
        $author = new Author();
        $ruName = 'Лев Толстой';
        $enName = 'Lev Tolstoy';

        $author->translate(Locale::RU)->setName($ruName);
        $author->translate(Locale::EN)->setName($enName);

        $this->assertEquals($ruName, $author->translate(Locale::RU)->getName());
        $this->assertEquals($enName, $author->translate(Locale::EN)->getName());
    }
}