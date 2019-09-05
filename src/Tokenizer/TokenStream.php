<?php

declare(strict_types=1);

namespace Dgame\Type\Tokenizer;

use Generator;
use RuntimeException;

/**
 * Class TokenStream
 * @package Dgame\Type\Tokenizer
 */
final class TokenStream
{
    /**
     * @var Generator
     */
    private $generator;

    /**
     * TokenStream constructor.
     *
     * @param Tokenizer $tokenizer
     */
    public function __construct(Tokenizer $tokenizer)
    {
        $this->generator = $tokenizer->getGenerator();
    }

    /**
     * @return Token
     */
    public function peekNextToken(): Token
    {
        if (!$this->generator->valid()) {
            return Token::eof();
        }

        return $this->generator->current();
    }

    /**
     *
     */
    public function skipNextToken(): void
    {
        $this->generator->next();
    }

    /**
     * @return Token
     */
    public function getNextToken(): Token
    {
        $token = $this->peekNextToken();
        $this->skipNextToken();

        return $token;
    }

    /**
     * @param int ...$types
     *
     * @return Token|null
     */
    public function mayOneOf(int ...$types): ?Token
    {
        $token = $this->peekNextToken();
        foreach ($types as $type) {
            if ($token->getType() === $type) {
                $this->skipNextToken();

                return $token;
            }
        }

        return null;
    }

    /**
     * @param int ...$types
     *
     * @return Token
     */
    public function expectOneOf(int ...$types): Token
    {
        if (empty($types)) {
            throw new RuntimeException('No types to expect given');
        }

        $token = $this->mayOneOf(...$types);
        if ($token !== null) {
            return $token;
        }

        $type = implode(', ', array_map(static function (int $type): ?string {
            return Token::getName($type);
        }, $types));

        throw new RuntimeException(
            sprintf(
                '"%s" is not of type %s',
                (string) $this->peekNextToken(),
                $type
            )
        );
    }
}
