<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Exceptions;

class MayarNotFoundException extends MayarException
{
    /**
     * @param  array<int, string>  $ids
     */
    public function __construct(
        public readonly string $modelClass,
        public readonly array $ids = [],
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        if (blank($message)) {
            $message = sprintf(
                'No query results for model [%s]%s.',
                $modelClass,
                $ids === [] ? '' : ' ' . implode(', ', $ids),
            );
        }

        parent::__construct(message: $message, code: $code, previous: $previous);
    }
}
