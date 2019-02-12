<?php
/*
 * Copyright (C) 2018 Sebastian Böttger <seboettg@gmail.com>
 * You may use, distribute and modify this code under the
 * terms of the MIT license.
 *
 * You should have received a copy of the MIT license with
 * this file. If not, please visit: https://opensource.org/licenses/mit-license.php
 */

namespace Seboettg\Collection\ArrayList;

/**
 * Trait ArrayListTrait
 * @package Seboettg\Collection
 * @author Sebastian Böttger <seboettg@gmail.com>
 */
trait ArrayListTrait
{

    use ArrayAccessTrait;

    /**
     * flush array list
     *
     * @return $this
     */
    public function clear()
    {
        $this->array = [];
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        return isset($this->array[$key]) ? $this->array[$key] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return current($this->array);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return next($this->array);
    }

    /**
     * {@inheritdoc}
     */
    public function prev()
    {
        return prev($this->array);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $element)
    {
        $this->array[$key] = $element;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setArray(array $array)
    {
        return $this->replace($array);
    }

    /**
     * {@inheritdoc}
     */
    public function append($element)
    {
        $this->array[] = $element;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $element)
    {

        if (!array_key_exists($key, $this->array)) {
            $this->array[$key] = $element;
        } elseif (is_array($this->array[$key])) {
            $this->array[$key][] = $element;
        } else {
            $this->array[$key] = [$this->array[$key], $element];
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        unset($this->array[$key]);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasKey($key)
    {
        return array_key_exists($key, $this->array);
    }

    /**
     * {@inheritdoc}
     */
    public function hasElement($value)
    {
        $result = array_search($value, $this->array, true);
        return ($result !== false);
    }

    /**
     * {@inheritdoc}
     */
    public function replace(array $data)
    {
        $this->array = $data;
        return $this;
    }

    /**
     * Returns the first element
     * @return mixed
     */
    public function first()
    {
        reset($this->array);
        return $this->array[key($this->array)];
    }

    /**
     * Returns the last element
     * @return mixed
     */
    public function last()
    {
        $item = end($this->array);
        reset($this->array);
        return $item;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return $this->array;
    }

    /**
     * Shuffles this list (randomizes the order of the elements in). It uses the PHP function shuffle
     * @see http://php.net/manual/en/function.shuffle.php
     * @return ArrayListInterface|ArrayListTrait
     */
    public function shuffle()
    {
        shuffle($this->array);
        return $this;
    }

    /**
     * returns a clone of this ArrayList, filtered by the given closure function
     * @param \Closure $closure
     * @return ArrayListInterface|ArrayListTrait
     */
    public function filter(\Closure $closure)
    {
        $newInstance = new self();
        $newInstance->setArray(array_filter($this->array, $closure));
        return $newInstance;
    }

    /**
     * returns a clone of this ArrayList, filtered by the given array keys
     * @param array $keys
     * @return ArrayListInterface|ArrayListTrait
     */
    public function filterByKeys(array $keys)
    {
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $newInstance = new self();
        $newInstance->setArray(array_filter($this->array, function ($key) use ($keys) {
            return array_search($key, $keys) !== false;
        }, ARRAY_FILTER_USE_KEY));
        return $newInstance;
    }

    /**
     * returns a new ArrayList containing all the elements of this ArrayList after applying the callback function to each one.
     * @param \closure $mapFunction
     * @return ArrayListInterface|ArrayListTrait
     */
    public function map(\closure $mapFunction)
    {
        $newInstance = new self();
        $newInstance->setArray(array_map($mapFunction, $this->array));
        return $newInstance;
    }

    /**
     * Returns a new ArrayList containing an one-dimensional array of all elements of this ArrayList. Keys are going lost.
     * @return ArrayListInterface|ArrayListTrait
     */
    public function flatten()
    {
        $flattenedArray = [];
        array_walk_recursive($this->array, function ($item) use (&$flattenedArray) {
            $flattenedArray[] = $item;
        });
        $newInstance = new self();
        $newInstance->setArray($flattenedArray);
        return $newInstance;
    }
}
