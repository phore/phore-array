<?php


namespace Phore\Arr;

class PhoreAssoc
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param callable $callback
     * @return PhoreAssoc
     * <example>
     * $assoc = phore_assoc(['a' => 1, 'b' => 2]);
     * $result = $assoc->map(fn($k, $v) => [$k, $v * 2]); // ['a' => 2, 'b' => 4]
     * </example>
     */
    public function map(callable $callback): PhoreAssoc
    {
        $result = [];
        foreach ($this->data as $key => $value) {
            [$newKey, $newValue] = $callback($key, $value);
            $result[$newKey] = $newValue;
        }
        return new PhoreAssoc($result);
    }

    /**
     * @param callable $callback
     * @return PhoreAssoc
     * <example>
     * $assoc = phore_assoc(['a' => 1, 'b' => 2]);
     * $result = $assoc->filter(fn($k, $v) => $v > 1); // ['b' => 2]
     * </example>
     */
    public function filter(callable $callback): PhoreAssoc
    {
        return new PhoreAssoc(array_filter($this->data, $callback, ARRAY_FILTER_USE_BOTH));
    }

    /**
     * @param string $key
     * @return bool
     * <example>
     * $assoc = phore_assoc(['a' => 1, 'b' => 2]);
     * $result = $assoc->has('a'); // true
     * </example>
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * @return \Phore\Arr\PhoreArray
     * <example>
     * $assoc = phore_assoc(['a' => 1, 'b' => 2]);
     * $result = $assoc->keys(); // ['a', 'b']
     * </example>
     */
    public function keys(): \Phore\Arr\PhoreArray
    {
        return new PhoreArray(array_keys($this->data));
    }

    /**
     * @return PhoreArray
     * <example>
     * $assoc = phore_assoc(['a' => 1, 'b' => 2]);
     * $result = $assoc->values(); // [1, 2]
     * </example>
     */
    public function values(): PhoreArray
    {
        return new PhoreArray(array_values($this->data));
    }

    /**
     * @param callable $callback
     * @param mixed $initial
     * @return mixed
     * <example>
     * $assoc = phore_assoc(['a' => 1, 'b' => 2]);
     * $result = $assoc->reduce(fn($carry, $k, $v) => $carry + $v, 0); // 3
     * </example>
     */
    public function reduce(callable $callback, $initial)
    {
        return array_reduce(array_keys($this->data), function ($carry, $key) use ($callback) {
            return $callback($carry, $key, $this->data[$key]);
        }, $initial);
    }

    /**
     * @param callable $callback
     * @return bool
     * <example>
     * $assoc = phore_assoc(['a' => 1, 'b' => 2]);
     * $result = $assoc->every(fn($k, $v) => $v > 0); // true
     * </example>
     */
    public function every(callable $callback): bool
    {
        foreach ($this->data as $key => $value) {
            if (!$callback($key, $value)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param callable $callback
     * @return bool
     * <example>
     * $assoc = phore_assoc(['a' => 1, 'b' => 2]);
     * $result = $assoc->some(fn($k, $v) => $v > 1); // true
     * </example>
     */
    public function some(callable $callback): bool
    {
        foreach ($this->data as $key => $value) {
            if ($callback($key, $value)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param callable $callback
     * @return void
     * <example>
     * $assoc = phore_assoc(['a' => 1, 'b' => 2]);
     * $assoc->forEach(fn($k, $v) => echo $k . ": " . $v . "\n"); // prints "a: 1\nb: 2\n"
     * </example>
     */
    public function forEach(callable $callback): void
    {
        foreach ($this->data as $key => $value) {
            $callback($key, $value);
        }
    }

    /**
     * @return string
     * <example>
     * $assoc = phore_assoc(['a' => 1, 'b' => 2]);
     * $result = $assoc->toString(); // "a:1,b:2"
     * </example>
     */
    public function toString(): string
    {
        return implode(",", array_map(fn($k, $v) => $k . ":" . $v, array_keys($this->data), $this->data));
    }
}
