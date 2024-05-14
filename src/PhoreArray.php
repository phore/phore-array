<?php

namespace Phore\Arr;

class PhoreArray
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param callable $callback
     * @return PhoreArray
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $result = $arr->map(fn($v) => $v * 2); // [2, 4, 6, 8]
     * </example>
     */
    public function map(callable $callback): PhoreArray
    {
        return new PhoreArray(array_map($callback, $this->data));
    }

    /**
     * @param callable $callback
     * @return PhoreArray
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $result = $arr->filter(fn($v) => $v % 2 === 0); // [2, 4]
     * </example>
     */
    public function filter(callable $callback): PhoreArray
    {
        return new PhoreArray(array_filter($this->data, $callback));
    }

    /**
     * @param callable $callback
     * @param mixed $initial
     * @return mixed
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $result = $arr->reduce(fn($carry, $item) => $carry + $item, 0); // 10
     * </example>
     */
    public function reduce(callable $callback, $initial)
    {
        return array_reduce($this->data, $callback, $initial);
    }

    /**
     * @return string
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $result = $arr->join(','); // "1,2,3,4"
     * </example>
     */
    public function join(string $glue): string
    {
        return implode($glue, $this->data);
    }

    /**
     * @param callable $callback
     * @return bool
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $result = $arr->every(fn($v) => $v > 0); // true
     * </example>
     */
    public function every(callable $callback): bool
    {
        foreach ($this->data as $value) {
            if (!$callback($value)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param mixed $value
     * @param int $start
     * @param int $end
     * @return PhoreArray
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $result = $arr->fill(0, 1, 3); // [1, 0, 0, 4]
     * </example>
     */
    public function fill($value, int $start = 0, int $end = null): PhoreArray
    {
        $end = $end ?? count($this->data);
        for ($i = $start; $i < $end; $i++) {
            $this->data[$i] = $value;
        }
        return new PhoreArray($this->data);
    }

    /**
     * @param callable $callback
     * @return mixed
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $result = $arr->find(fn($v) => $v > 2); // 3
     * </example>
     */
    public function find(callable $callback)
    {
        foreach ($this->data as $value) {
            if ($callback($value)) {
                return $value;
            }
        }
        return null;
    }

    /**
     * @param callable $callback
     * @return int
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $result = $arr->findIndex(fn($v) => $v > 2); // 2
     * </example>
     */
    public function findIndex(callable $callback): int
    {
        foreach ($this->data as $index => $value) {
            if ($callback($value)) {
                return $index;
            }
        }
        return -1;
    }

    /**
     * @param callable $callback
     * @return mixed
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $result = $arr->findLast(fn($v) => $v > 2); // 4
     * </example>
     */
    public function findLast(callable $callback)
    {
        for ($i = count($this->data) - 1; $i >= 0; $i--) {
            if ($callback($this->data[$i])) {
                return $this->data[$i];
            }
        }
        return null;
    }

    /**
     * @param callable $callback
     * @return int
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $result = $arr->findLastIndex(fn($v) => $v > 2); // 3
     * </example>
     */
    public function findLastIndex(callable $callback): int
    {
        for ($i = count($this->data) - 1; $i >= 0; $i--) {
            if ($callback($this->data[$i])) {
                return $i;
            }
        }
        return -1;
    }

    /**
     * @return PhoreArray
     * <example>
     * $arr = phore_array([[1, 2], [3, 4]]);
     * $result = $arr->flat(); // [1, 2, 3, 4]
     * </example>
     */
    public function flat(): PhoreArray
    {
        $flattened = [];
        array_walk_recursive($this->data, function ($a) use (&$flattened) {
            $flattened[] = $a;
        });
        return new PhoreArray($flattened);
    }

    /**
     * @param callable $callback
     * @return void
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $arr->forEach(fn($v) => echo $v); // prints "1234"
     * </example>
     */
    public function forEach(callable $callback): void
    {
        array_map($callback, $this->data);
    }

    /**
     * @param callable $callback
     * @return PhoreArray
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $result = $arr->flatMap(fn($v) => [$v * 2]); // [2, 4, 6, 8]
     * </example>
     */
    public function flatMap(callable $callback): PhoreArray
    {
        $mapped = array_map($callback, $this->data);
        return (new PhoreArray($mapped))->flat();
    }

    /**
     * @param mixed $needle
     * @return bool
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $result = $arr->includes(2); // true
     * </example>
     */
    public function includes($needle): bool
    {
        return in_array($needle, $this->data);
    }

    /**
     * @param mixed $needle
     * @return int
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $result = $arr->indexOf(2); // 1
     * </example>
     */
    public function indexOf($needle): int
    {
        return array_search($needle, $this->data, true);
    }



    /**
     * @param mixed $needle
     * @return int
     * <example>
     * $arr = phore_array([1, 2, 3, 2, 4]);
     * $result = $arr->lastIndexOf(2); // 3
     * </example>
     */
    public function lastIndexOf($needle): int
    {
        for ($i = count($this->data) - 1; $i >= 0; $i--) {
            if ($this->data[$i] === $needle) {
                return $i;
            }
        }
        return -1;
    }



    /**
     * @return mixed
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $result = $arr->pop(); // 4
     * </example>
     */
    public function pop()
    {
        return array_pop($this->data);
    }

    /**
     * @param mixed $value
     * @return int
     * <example>
     * $arr = phore_array([1, 2, 3]);
     * $result = $arr->push(4); // [1, 2, 3, 4]
     * </example>
     */
    public function push($value): int
    {
        return array_push($this->data, $value);
    }


    /**
     * @param callable $callback
     * @param mixed $initial
     * @return mixed
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $result = $arr->reduceRight(fn($carry, $item) => $carry + $item, 0); // 10
     * </example>
     */
    public function reduceRight(callable $callback, $initial)
    {
        return array_reduce(array_reverse($this->data), $callback, $initial);
    }

    /**
     * @return PhoreArray
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $result = $arr->reverse(); // [4, 3, 2, 1]
     * </example>
     */
    public function reverse(): PhoreArray
    {
        return new PhoreArray(array_reverse($this->data));
    }

    /**
     * @return mixed
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $result = $arr->shift(); // 1
     * </example>
     */
    public function shift()
    {
        return array_shift($this->data);
    }

    /**
     * @param int $start
     * @param int $end
     * @return PhoreArray
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $result = $arr->slice(1, 3); // [2, 3]
     * </example>
     */
    public function slice(int $start, int $end = null): PhoreArray
    {
        return new PhoreArray(array_slice($this->data, $start, $end));
    }

    /**
     * @param callable $callback
     * @return bool
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $result = $arr->some(fn($v) => $v > 2); // true
     * </example>
     */
    public function some(callable $callback): bool
    {
        foreach ($this->data as $value) {
            if ($callback($value)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param callable|null $callback
     * @return PhoreArray
     * <example>
     * $arr = phore_array([3, 1, 4, 2]);
     * $result = $arr->sort(); // [1, 2, 3, 4]
     * </example>
     */
    public function sort(callable $callback = null): PhoreArray
    {
        $data = $this->data;
        $callback ? usort($data, $callback) : sort($data);
        return new PhoreArray($data);
    }

    /**
     * @param int $offset
     * @param int $length
     * @param mixed $replacement
     * @return PhoreArray
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $result = $arr->splice(1, 2, [5, 6]); // [1, 5, 6, 4]
     * </example>
     */
    public function splice(int $offset, int $length = null, $replacement = []): PhoreArray
    {
        array_splice($this->data, $offset, $length, $replacement);
        return new PhoreArray($this->data);
    }

    /**
     * @param mixed $value
     * @return int
     * <example>
     * $arr = phore_array([1, 2, 3]);
     * $result = $arr->unshift(0); // [0, 1, 2, 3]
     * </example>
     */
    public function unshift($value): int
    {
        return array_unshift($this->data, $value);
    }

    /**
     * @param callable $callback
     * @return PhoreArray
     * <example>
     * $arr = phore_array([3, 1, 4, 2]);
     * $result = $arr->toSorted(fn($a, $b) => $a <=> $b); // [1, 2, 3, 4]
     * </example>
     */
    public function toSorted(callable $callback): PhoreArray
    {
        $data = $this->data;
        usort($data, $callback);
        return new PhoreArray($data);
    }

    /**
     * @param int $start
     * @param int $deleteCount
     * @param array $items
     * @return PhoreArray
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $result = $arr->toSpliced(1, 2, [5, 6]); // [1, 5, 6, 4]
     * </example>
     */
    public function toSpliced(int $start, int $deleteCount, array $items = []): PhoreArray
    {
        $data = $this->data;
        array_splice($data, $start, $deleteCount, $items);
        return new PhoreArray($data);
    }

    /**
     * @return string
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $result = $arr->toString(); // "1,2,3,4"
     * </example>
     */
    public function toString(): string
    {
        return implode(",", $this->data);
    }

    /**
     * @param mixed $value
     * @param int $index
     * @return PhoreArray
     * <example>
     * $arr = phore_array([1, 2, 3, 4]);
     * $result = $arr->with(2, 5); // [1, 2, 5, 4]
     * </example>
     */
    public function with(int $index, $value): PhoreArray
    {
        $data = $this->data;
        $data[$index] = $value;
        return new PhoreArray($data);
    }

    /**
     * @return PhoreArray
     * <example>
     * $arr = phore_array(['a' => 1, 'b' => 2]);
     * $result = $arr->keys(); // ['a', 'b']
     * </example>
     */
    public function keys(): PhoreArray
    {
        return new PhoreArray(array_keys($this->data));
    }

    /**
     * @return PhoreArray
     * <example>
     * $arr = phore_array(['a' => 1, 'b' => 2]);
     * $result = $arr->values(); // [1, 2]
     * </example>
     */
    public function values(): PhoreArray
    {
        return new PhoreArray(array_values($this->data));
    }
}