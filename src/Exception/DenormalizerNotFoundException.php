<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Exception;

use Vuryss\Serializer\SerializerException;

class DenormalizerNotFoundException extends \Exception implements SerializerException {}
