<?php

// taruh file ini di namespace Tests\Unit buat unit test
namespace Tests\Unit;

// pake TestCase bawaan phpunit karena unit test ringan ga butuh app laravel full
use PHPUnit\Framework\TestCase;

// kelas contoh unit test bawaan laravel
class ExampleTest extends TestCase
{
    // fungsi test dasar buat ngecek nilai true beneran true
    public function test_that_true_is_true(): void
    {
        // pastiin nilai yang dicek itu beneran true
        $this->assertTrue(true);
    }
}
