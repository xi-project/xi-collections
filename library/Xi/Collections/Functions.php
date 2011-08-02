<?php
namespace Xi\Collections;

/**
 * A set of static functions that generate callback functions. Collection
 * implementations use these to share some of their implementations between each
 * other, but they can also be used when mirroring certain operations with
 * constructs outside Xi\Collections.
 */
final class Functions
{
    /**
     * This class cannot be instantiated
     */
    final private function __construct() {}
    
    /**
     * Get a callback to method on a class
     * 
     * @param object|string $target instance or class name
     * @param string $method
     * @return Closure
     */
    public static function getCallback($target, $method)
    {
        return function() use($target, $method) {
            return call_user_func_array(array($target, $method), func_get_args());
        };
    }
    
    /**
     * @param scalar $key
     * @return callback
     */
    public static function pick($key)
    {
        return function($input) use($key) {
            switch(true) {
                case $input instanceof \ArrayAccess:
                case is_array($input):  return isset($input[$key]) ? $input[$key] : null;
                case is_object($input): return isset($input->$key) ? $input->$key : null;
                default:                return null;
            }
        };
    }
    
    /**
     * @param callback($value, $key) $callback
     * @return callback($collection)
     */
    public static function indexBy($callback)
    {
        return function($collection) use($callback) {
            $results = array();
            foreach ($collection as $key => $value) {
                $results[$callback($value, $key)] = $value;
            }
            return $results;
        };
    }
    
    /**
     * @param callback($value, $key) $groupIndex
     * @param callback($group) $groupValue optional
     * @return callback($collection)
     */
    public static function groupBy($groupIndex, $groupValue = null)
    {
        return function($collection) use($groupIndex, $groupValue) {
            $results = array();
            // Create groups using $groupIndex
            foreach ($collection as $key => $value) {
                $results[$groupIndex($value, $key)][] = $value;
            }
            // Transform groups using $groupValue
            if (null !== $groupValue) {
                foreach ($results as $key => $value) {
                    $results[$key] = $groupValue($value);
                }
            }
            return $results;
        };
    }
    
    /**
     * Flattens nested collections of arrays or Traversable objects
     * 
     * @return callback($collection)
     */
    public static function flatten()
    {
        $flatten = function($collection) use(&$flatten) {
            $results = array();
            foreach ($collection as $value) {
                if (is_array($value) || ($value instanceof \Traversable)) {
                    $results = array_merge($results, $flatten($value));
                } else {
                    $results[] = $value;
                }
            }
            return $results;
        };
        return $flatten;
    }
}