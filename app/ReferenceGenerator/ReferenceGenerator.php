<?php
namespace App\ReferenceGenerator;

interface ReferenceGenerator
{
    public function generateNextReference(): void;
    public function getGeneratedReference(): Reference;
}
