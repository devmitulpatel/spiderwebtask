<?php

namespace MacsiDigital\Zoom\Support;

use DateTime;
use MacsiDigital\API\Support\ApiResource;

class Model extends ApiResource
{
    protected $apiDataField = '';

    protected $dateFormat = DateTime::ATOM;
}
