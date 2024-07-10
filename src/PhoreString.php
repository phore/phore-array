<?php

namespace Phore\Arr;

class PhoreString
{
    private string $data;

    public function __construct(string $data)
    {
        $this->data = $data;
    }

    /**
     * @param string $delimiter
     * @return PhoreArray
     * <example>
     * $str = phore_string("a,b,c");
     * $result = $str->explode(","); // ['a', 'b', 'c']
     * </example>
     */
    public function explode(string $delimiter): PhoreArray
    {
        return new PhoreArray(explode($delimiter, $this->data));
    }


    /**
     * @return PhoreString
     * <example>
     * $str = phore_string("  hello  ");
     * $result = $str->trim(); // "hello"
     * </example>
     */
    public function trim(): PhoreString
    {
        return new PhoreString(trim($this->data));
    }

    /**
     * @return PhoreString
     * <example>
     * $str = phore_string("  hello  ");
     * $result = $str->rtrim(); // "  hello"
     * </example>
     */
    public function rtrim(): PhoreString
    {
        return new PhoreString(rtrim($this->data));
    }

    /**
     * @return PhoreString
     * <example>
     * $str = phore_string("  hello  ");
     * $result = $str->ltrim(); // "hello  "
     * </example>
     */
    public function ltrim(): PhoreString
    {
        return new PhoreString(ltrim($this->data));
    }

    /**
     * @return PhoreString
     * <example>
     * $str = phore_string("hello");
     * $result = $str->toUpper(); // "HELLO"
     * </example>
     */
    public function toUpper(): PhoreString
    {
        return new PhoreString(strtoupper($this->data));
    }

    /**
     * @return PhoreString
     * <example>
     * $str = phore_string("HELLO");
     * $result = $str->toLower(); // "hello"
     * </example>
     */
    public function toLower(): PhoreString
    {
        return new PhoreString(strtolower($this->data));
    }

    /**
     * @param string $pattern
     * @param string $replacement
     * @return PhoreString
     * <example>
     * $str = phore_string("hello world");
     * $result = $str->regexReplace("/world/", "PHP"); // "hello PHP"
     * </example>
     */
    public function regexReplace(string $pattern, string $replacement): PhoreString
    {
        return new PhoreString(preg_replace($pattern, $replacement, $this->data));
    }

    /**
     * @param string $pattern
     * @return PhoreArray
     * <example>
     * $str = phore_string("hello world");
     * $result = $str->regexMatch("/\w+/"); // ["hello", "world"]
     * </example>
     */
    public function regexMatch(string $pattern): PhoreArray
    {
        preg_match_all($pattern, $this->data, $matches);
        return new PhoreArray($matches[0]);
    }

    /**
     * @param string $search
     * @param string $replace
     * @return PhoreString
     * <example>
     * $str = phore_string("hello world");
     * $result = $str->replace("world", "PHP"); // "hello PHP"
     * </example>
     */
    public function replace(string $search, string $replace): PhoreString
    {
        return new PhoreString(str_replace($search, $replace, $this->data));
    }

    /**
     * @param int $start
     * @param int|null $length
     * @return PhoreString
     * <example>
     * $str = phore_string("hello world");
     * $result = $str->substring(0, 5); // "hello"
     * </example>
     */
    public function substring(int $start, int $length = null): PhoreString
    {
        return new PhoreString(substr($this->data, $start, $length));
    }

    /**
     * @param string $string
     * @return bool
     * <example>
     * $str = phore_string("hello world");
     * $result = $str->includes("world"); // true
     * </example>
     */
    public function includes(string $string): bool
    {
        return strpos($this->data, $string) !== false;
    }

    /**
     * @param string $needle
     * @return bool
     * <example>
     * $str = phore_string("hello world");
     * $result = $str->startsWith("hello"); // true
     * </example>
     */
    public function startsWith(string $needle): bool
    {
        return strncmp($this->data, $needle, strlen($needle)) === 0;
    }

    /**
     * @param string $needle
     * @return bool
     * <example>
     * $str = phore_string("hello world");
     * $result = $str->endsWith("world"); // true
     * </example>
     */
    public function endsWith(string $needle): bool
    {
        return substr($this->data, -strlen($needle)) === $needle;
    }

    /**
     * @return int
     * <example>
     * $str = phore_string("hello world");
     * $result = $str->length(); // 11
     * </example>
     */
    public function length(): int
    {
        return strlen($this->data);
    }

    /**
     * @param string $delimiter
     * @return PhoreArray
     * <example>
     * $str = phore_string("a,b,c");
     * $result = $str->split(","); // ["a", "b", "c"]
     * </example>
     */
    public function split(string $delimiter): PhoreArray
    {
        return $this->explode($delimiter);
    }

    /**
     * @param int $times
     * @return PhoreString
     * <example>
     * $str = phore_string("abc");
     * $result = $str->repeat(3); // "abcabcabc"
     * </example>
     */
    public function repeat(int $times): PhoreString
    {
        return new PhoreString(str_repeat($this->data, $times));
    }

    /**
     * @return string
     * <example>
     * $str = phore_string("hello");
     * $result = $str->toString(); // "hello"
     * </example>
     */
    public function toString(): string
    {
        return $this->data;
    }
}
