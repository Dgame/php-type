<?php

declare(strict_types=1);

namespace Dgame\Type\Tokenizer;

use Generator;

/**
 * Class Tokenizer
 * @package Dgame\Type\Tokenizer
 */
final class Tokenizer
{
    /**
     * @var array
     */
    private $tokens = [];

    /**
     * Tokenizer constructor.
     *
     * @param string $content
     */
    public function __construct(string $content)
    {
        preg_match_all(Token::getPattern(), $content, $this->tokens);
    }

    /**
     * @return Generator
     */
    public function getGenerator(): Generator
    {
        for ($i = 0, $c = count($this->tokens[0]); $i < $c; $i++) {
            foreach (Token::LABELS as $type => $label) {
                $match = trim($this->tokens[$type][$i]);
                if ($match !== '') {
                    yield new Token($type, $match);
                }
            }
        }
    }

    /**
     * @return TokenStream
     */
    public function getTokenStream(): TokenStream
    {
        return new TokenStream($this);
    }
}
