<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // tambah setup method
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from addresses");
        DB::delete("delete from contacts");
        DB::delete("delete from users");
    }
}
