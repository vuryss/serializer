<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Exception;

use Vuryss\Serializer\SerializerException;

class InvalidTypeException extends \Exception implements SerializerException {}
