<?php

declare(strict_types=1);

namespace Dgame\Type\Tokenizer;

/**
 * Class Token
 * @package Dgame\Type\Tokenizer
 */
final class Token
{
    public const EOF                  = 0;
    public const BUILTIN_TYPE         = 1;
    public const KEYWORD              = 2;
    public const THIS_VARIABLE        = 3;
    public const VARIABLE             = 4;
    public const UNION                = 5;
    public const INTERSECTION         = 6;
    public const NULLABLE             = 7;
    public const OPEN_PARENTHESES     = 8;
    public const CLOSE_PARENTHESES    = 9;
    public const OPEN_ANGLE_BRACKET   = 10;
    public const CLOSE_ANGLE_BRACKET  = 11;
    public const OPEN_SQUARE_BRACKET  = 12;
    public const CLOSE_SQUARE_BRACKET = 13;
    public const OPEN_CURLY_BRACKET   = 14;
    public const CLOSE_CURLY_BRACKET  = 15;
    public const COMMA                = 16;
    public const VARIADIC             = 17;
    public const DOUBLE_COLON         = 18;
    public const DOUBLE_ARROW         = 19;
    public const EQUAL                = 20;
    public const COLON                = 21;
    public const IDENTIFIER           = 22;

    private const PATTERN = [
        self::BUILTIN_TYPE         => 'string|int(?:eger)?|float|double|real|bool(?:ean)?|resource|object|array|self|static|parent|callable|iterable|void|mixed|null',
        self::KEYWORD              => 'null|true|false',
        self::THIS_VARIABLE        => '\$this',
        self::VARIABLE             => '\$[_a-z]+\w*',
        self::UNION                => '\\|',
        self::INTERSECTION         => '&',
        self::NULLABLE             => '\\?',
        self::OPEN_PARENTHESES     => '\\(',
        self::CLOSE_PARENTHESES    => '\\)',
        self::OPEN_ANGLE_BRACKET   => '<',
        self::CLOSE_ANGLE_BRACKET  => '>',
        self::OPEN_SQUARE_BRACKET  => '\\[',
        self::CLOSE_SQUARE_BRACKET => '\\]',
        self::OPEN_CURLY_BRACKET   => '\\{',
        self::CLOSE_CURLY_BRACKET  => '\\}',
        self::COMMA                => ',',
        self::VARIADIC             => '\\.\\.\\.',
        self::DOUBLE_COLON         => '::',
        self::DOUBLE_ARROW         => '=>',
        self::EQUAL                => '=',
        self::COLON                => ':',
        self::IDENTIFIER           => '(?:\\\?[_a-zA-Z]+\w*)+',
    ];

    /**
     * @var int
     */
    private $type;

    public const LABELS = [
        self::BUILTIN_TYPE         => 'Builtin Type',
        self::KEYWORD              => 'Keyword',
        self::THIS_VARIABLE        => 'This',
        self::VARIABLE             => 'Variable',
        self::UNION                => 'Union',
        self::INTERSECTION         => 'Intersection',
        self::NULLABLE             => 'Nullable',
        self::OPEN_PARENTHESES     => 'Open Parentheses',
        self::CLOSE_PARENTHESES    => 'Close Parentheses',
        self::OPEN_ANGLE_BRACKET   => 'Open Angle Bracket',
        self::CLOSE_ANGLE_BRACKET  => 'Close Angle Bracket',
        self::OPEN_SQUARE_BRACKET  => 'Open Square Bracket',
        self::CLOSE_SQUARE_BRACKET => 'Close Square Bracket',
        self::OPEN_CURLY_BRACKET   => 'Open Curly Bracket',
        self::CLOSE_CURLY_BRACKET  => 'Close Curly Bracket',
        self::COMMA                => 'Comma',
        self::VARIADIC             => 'Variadic',
        self::DOUBLE_COLON         => 'Double Colon',
        self::DOUBLE_ARROW         => 'Double Arrow',
        self::EQUAL                => 'Equal',
        self::COLON                => 'Colon',
        self::IDENTIFIER           => 'Identifier'
    ];
    /**
     * @var string
     */
    private $value;

    /**
     * Token constructor.
     *
     * @param int    $type
     * @param string $value
     */
    public function __construct(int $type, string $value)
    {
        $this->type  = $type;
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isIdentifier(): bool
    {
        return $this->type === self::IDENTIFIER;
    }

    /**
     * @return bool
     */
    public function isBuiltinType(): bool
    {
        return $this->type === self::BUILTIN_TYPE;
    }

    /**
     * @return bool
     */
    public function isKeyword(): bool
    {
        return $this->type === self::KEYWORD;
    }

    /**
     * @return bool
     */
    public function isThisVariable(): bool
    {
        return $this->type === self::THIS_VARIABLE;
    }

    /**
     * @return bool
     */
    public function isVariable(): bool
    {
        return $this->type === self::VARIABLE;
    }

    /**
     * @return bool
     */
    public function isUnion(): bool
    {
        return $this->type === self::UNION;
    }

    /**
     * @return bool
     */
    public function isIntersection(): bool
    {
        return $this->type === self::INTERSECTION;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->type === self::NULLABLE;
    }

    /**
     * @return bool
     */
    public function isOpenParentheses(): bool
    {
        return $this->type === self::OPEN_PARENTHESES;
    }

    /**
     * @return bool
     */
    public function isCloseParentheses(): bool
    {
        return $this->type === self::CLOSE_PARENTHESES;
    }

    /**
     * @return bool
     */
    public function isOpenAngleBracket(): bool
    {
        return $this->type === self::OPEN_ANGLE_BRACKET;
    }

    /**
     * @return bool
     */
    public function isCloseAngleBracket(): bool
    {
        return $this->type === self::CLOSE_ANGLE_BRACKET;
    }

    /**
     * @return bool
     */
    public function isOpenSquareBracket(): bool
    {
        return $this->type === self::OPEN_SQUARE_BRACKET;
    }

    /**
     * @return bool
     */
    public function isCloseSquareBracket(): bool
    {
        return $this->type === self::CLOSE_SQUARE_BRACKET;
    }

    /**
     * @return bool
     */
    public function isOpenCurlyBracket(): bool
    {
        return $this->type === self::OPEN_CURLY_BRACKET;
    }

    /**
     * @return bool
     */
    public function isCloseCurlyBracket(): bool
    {
        return $this->type === self::CLOSE_CURLY_BRACKET;
    }

    /**
     * @return bool
     */
    public function isComma(): bool
    {
        return $this->type === self::COMMA;
    }

    /**
     * @return bool
     */
    public function isVariadic(): bool
    {
        return $this->type === self::VARIADIC;
    }

    /**
     * @return bool
     */
    public function isDoubleColon(): bool
    {
        return $this->type === self::DOUBLE_COLON;
    }

    /**
     * @return bool
     */
    public function isDoubleArrow(): bool
    {
        return $this->type === self::DOUBLE_ARROW;
    }

    /**
     * @return bool
     */
    public function isEqual(): bool
    {
        return $this->type === self::EQUAL;
    }

    /**
     * @return bool
     */
    public function isColon(): bool
    {
        return $this->type === self::COLON;
    }

    /**
     * @return bool
     */
    public function isEof(): bool
    {
        return $this->type === 0;
    }

    /**
     * @param int $type
     *
     * @return string|null
     */
    public static function getName(int $type): ?string
    {
        return $type === 0 ? 'eof' : self::LABELS[$type] ?? 'unknown';
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->type === 0 ? 'eof' : sprintf('%s(%s)', self::getName($this->type), $this->value);
    }

    /**
     * @return string
     */
    public static function getPattern(): string
    {
        static $pattern = null;
        if ($pattern === null) {
            $pattern = '/(' . implode(')|(', self::PATTERN) . ')/Sis';
        }

        return $pattern;
    }

    /**
     * @return Token
     */
    public static function eof(): self
    {
        return new self(0, '');
    }
}
