<?php

// taruh file ini di namespace Tests buat kelas dasar semua test
namespace Tests;

// pake TestCase bawaan laravel terus dikasih alias BaseTestCase biar ga bentrok namanya
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

// kelas abstrak dasar yang bakal diwarisin sama semua file test di project ini
abstract class TestCase extends BaseTestCase
{
}
