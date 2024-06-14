<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Exception;

use Vuryss\Serializer\SerializerException;

class UnsupportedType extends \Exception implements SerializerException {}
