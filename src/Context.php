<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

/**
 * @phpstan-type ContextOptions array{
 *     json_decode_options?: int,
 *     groups?: array<string>,
 *     skip_null_values?: bool,
 *     datetime_format?: string,
 *     datetime_format_strict?: bool,
 *     datetime_target_timezone?: string,
 *     ...
 * }
 */
readonly class Context
{
    public const string JSON_DECODE_OPTIONS = 'json_decode_options';
    public const string GROUPS = 'groups';
    public const string SKIP_NULL_VALUES = 'skip_null_values';
    public const string DATETIME_FORMAT = 'datetime_format';
    public const string DATETIME_FORMAT_STRICT = 'datetime_format_strict';
    public const string DATETIME_TARGET_TIMEZONE = 'datetime_target_timezone';
}
