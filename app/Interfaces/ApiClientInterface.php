<?php

namespace App\Interfaces;

interface ApiClientInterface {
    public function fetchData(): ?array;
}
