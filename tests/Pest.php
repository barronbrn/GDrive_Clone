<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Harapan
|--------------------------------------------------------------------------
|
| Saat menulis pengujian, Anda sering kali perlu memeriksa apakah nilai memenuhi kondisi tertentu.
| Fungsi "expect()" memberi Anda akses ke satu set metode "harapan" yang dapat Anda gunakan
| untuk menyatakan berbagai hal. Tentu saja, Anda dapat memperluas API Harapan kapan saja.
|
*/

expect()->extend('toBeOne', function () {
    // Custom expectation: asserts that the value is 1
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // Example helper function (placeholder)
    // ..
}
