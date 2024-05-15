<?php


namespace Phore\Arr;

class PhoreAssoc implements \ArrayAccess
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     *  <example>
     *  $assoc = phore_assoc(['a' => 1, 'b' => 2]);
     *  $result = $assoc->map(fn($k, $v) => [$k, $v * 2]); // ['a' => 2, 'b' => 4]
     *  </example>
     *
     * @param callable $callback
     * @return PhoreAssoc
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
     *
     *  <example>
     *  $assoc = phore_assoc(['a' => 1, 'b' => 2]);
     *  $result = $assoc->filter(fn($k, $v) => $v > 1); // ['b' => 2]
     *  </example>
     *
     * @param callable $callback
     * @return PhoreAssoc
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
     *  <example>
     *  $assoc = phore_assoc(['a' => 1, 'b' => 2]);
     *  $result = $assoc->find(1); // "a"
     *  </example>
     *
     * @param string $value
     * @return string|null
     */
    public function find($value) : string|null {
        return array_search($value, $this->data, true) ?: null;
    }

    /**
     * Return the value of the key or the default value if the key does not exist
     *
     * Same as phore_assoc()["key"] ?? $default
     *
     * @template T
     * @param string $key
     * @param class-string<T>|null $cast
     * @param $default
     * @return mixed|null|T
     */
    public function key(string $key, string $instanceOf = null, $default = null)
    {
        if (!isset($this->data[$key])) {
            return $default;
        }
        if ($instanceOf !== null) {
            if ( ! ($this->data[$key] instanceof $instanceOf))
                throw new \InvalidArgumentException("Key '$key' is not of type '$instanceOf'");
        }
        return $this->data[$key];
    }

    public function keyToString(string $key) : string|null
    {
        if ( ! isset($this->data[$key]))
            return null;
        if (! is_string($this->data[$key]))
            throw new \InvalidArgumentException("Key '$key' is not of type string");
        return (string)$this->data[$key];
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

    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }


    /**
     * @param $offset
     * @return PhoreAssoc|null
     */
    public function offsetGet(mixed $offset) : mixed
    {
        if (!isset($this->data[$offset]))
            return null;
        return new PhoreAssoc($this->data[$offset]);
    }

    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }


    public function toArray() : array
    {
        return $this->data;
    }


    public function ksort($flags = SORT_REGULAR) : PhoreAssoc
    {
        $data = $this->data;
        ksort($data, $flags);
        return new PhoreAssoc($data);
    }

    public function toJson(bool $prettyPrint = false) : string
    {
        return phore_json_encode($this->data, $prettyPrint);
    }


}
