# Xi Collections

Functional, immutable and extensible enumerations and collections for PHP 5.3.

## Design Philosophy

PHP has always lacked solid collections support, with the vast majority of programmers making do with arrays and the related built-in functions. With the introduction of SPL in PHP 5.0 and the consequent extensions in 5.3, there are currently more choices than ever if all you want for is speed and answers to specific use cases. Array processing, however, is not significantly better than ten years ago, with the API about as comfortable and handy for everyday tasks as picking at your dinner with a shovel.

Xi Collections aims to rectify the situation and inject your workflow with a hearty dose of functional and declarative aspects. This is intended to result in more clarity in expressing and understanding processing collections of objects or data, allowing you to work faster and deliver more self-documenting code.

## Design Principles

- _Object immutability._ The collections' methods do not manipulate the collections' contents, but return a new collection instead.

- _API chainability._ No operation will return void and leave you in the dark; you'll always be able to continue where you left off.

- _Embracing functional programming._ The Collections API is chosen to facilitate a functional workflow. Existing FP-compatible functionality in PHP is prioritized for inclusion to the API, and other important concepts that are missing are stolen from other languages and libraries.

- _Out-of-the-box extensibility._ Decorators for separating the concrete Collection implementations from your modifications to the API are included. The interfaces tend to the minimal rather than extensive, making new implementations easier. Specifically, the whole of PHP's array functions is not built-in, but can be readily used if you need to.

# Examples

## From imperative to functional

Let's assume a simple loop that filters and transforms a set of data:

	public function getMatchingInterestingParts() {
		$result = array();
		foreach ($this->getFoos() as $key => $value) {
			if ($this->match($value)) {
				$result[$key] = $value->getInterestingParts();
			}
		}
		return $result;
	}

Here's the same expressed with Collections:

    public function getMatchingInterestingParts() {
        return $this->getFoos()
            ->filter(function(Foo $foo) {
                return $this->matches($foo);
            })->map(function(Foo $foo) {
                return $foo->getInterestingParts();
            });
    }

The latter bit of code is not much shorter, and for someone unfamiliar with functional constructs it may be more difficult to process. It does, however, have a few interesting qualities. The code communicates its intent better - filter values, then map the result, and nothing else. This is especially beneficial when considering code with a significantly more complex set of transformations. There's less room for error; index associations are automatically maintained. This also means you can focus on the interesting bits instead of boilerplate, which helps both when reading and when writing the code. A third benefit is that you can take full advantage of type hints and the safety they can bring, something which will be lacking with a simple foreach loop.

## Simplifying common access patterns

One of the most common use cases for looping over an array is collecting the results of a member access or method invocation from every item. Collections makes that easy.

    public function getBarsByFoos() {
        $bars = array();
        foreach ($this->getFoos() as $key => $foo) {
            $bars[$key] = $foo->getBar();
        }
        return $bars;
    }
    // becomes
    public function getBarsByFoos() {
        return $this->getFoos()->invoke('getBar');
    }

    public function getFooTrivialities() {
        $trivialities = array();
        foreach ($this->getFoos() as $key => $foo) {
            $trivialities[$key] = $foo->triviality;
        }
        return $trivialities;
    }
    // becomes
    public function getFooTrivialities() {
        return $this->getFoos()->pick('triviality');
    }

Picking even works for arrays (or objects implementing ArrayAccess) as well, and you don't need to care about which type the input is.

## Inspect intermediate steps of complex operations

Suppose you have a pipeline where data is transformed according to complex rules.

    public function getAliveQuxen() {
        return $this->getFoos()
            ->map($this->fromFooToBar)
            ->filter(function($bar) { return $bar->isAlive(); })
            ->map($this->fromBarToQux);
    }

Suppose further that you want to inspect the data as it passes from one step to another. This is where you'd introduce temporary variables, were the code imperatively structured. With Collections, all you need is `tap`. It accepts a function that takes the contents of the collection as its parameter - and does nothing but call the function.

    public function getAliveQuxen() {
        return $this->getFoos()
            ->map($this->fromFooToBar)
            ->filter(function($bar) { return $bar->isAlive(); })
            ->tap(function($bars) { $this->log($bars); })
            ->map($this->fromBarToQux);
    }

A reader of your code will be able to immediately recognize that the part in `tap` is only being executed for its side effects and that it has nothing to do with the transformation itself. We could've used `each` in a similar fashion if we were instead interested in the individual units of computation.

    public function getAliveQuxen() {
        return $this->getFoos()
            ->map($this->fromFooToBar)
            ->filter(function($bar) { return $bar->isAlive(); })
            ->each(function($bar) { $this->logBar($bar); })
            ->map($this->fromBarToQux);
    }

## Using an extended API on the fly

In any given PHP environment there tends to be an amount of existing functionality around for processing data in a Traversable format. PHP itself has a plethora of built-in array functions that aren't feasible to support in the Collection API if it is supposed to be kept minimal. This can potentially change with the introduction of traits in PHP 5.4, but for now you'll have to figure out ways to use these functions manually. At the core of this facility is `apply`. It accepts a function that applies a transformation of some kind to the collection, the result of which is taken in as a new collection.

Let's assume you want to sort your values. Here's a way to do it using `apply`.

    public function getSortedFoos() {
        return $this->getFoos()
            ->apply(function($collection) {
                $foos = $collection->toArray();
                ksort($foos);
                return $foos;
            });
    }

The argument is a collection, which will have to be converted to an array first to be accepted by `ksort`. The function also operates on references, not values, so a temporary variable is necessary. There's an amount of cruft with this use case, but you're likely to be using raw PHP functions rarely. If you're using `apply` with functions that have a more reasonable API, eg. accept Traversable objects instead of necessitating arrays, the footprint becomes much more palatable. In such a fictional scenario for `ksort`, for instance:

    public function getSortedFoos() {
        return $this->getFoos()
            ->apply('ksort');
    }

# API basics

Collections has two core interfaces. `Enumerable` implements a set of collection operations relying only on traversability. `Collection` extends the `Enumerable` operations to a superset that includes operations that yield other collections in return. This means collections can be transformed into other collections.

Every concrete class has a static `create` method that can be used for fluently constructing and accessing a collection. For instance:

    ArrayCollection::create($values)->invoke('getBar')->each(function(Bar $bar) { $bar->engage(); });

Below is a short description of the APIs provided by Enumerable and Collection. For more thorough information, you'll need to consult the source.

## Enumerable

`tap`: Calls a provided callback with this object as a parameter  
`each`: Performs an operation once per key-value pair  
`reduce`: Uses a given callback to reduce the collection's elements to a single value, starting from a provided initial value  
`find`: Returns the first value that satisfies a given predicate  
`exists`: Checks whether the collection has at least one element satisfying a given predicate  
`forAll`: Checks whether all of the elements in the collection satisfy a given predicate  
`countAll`: Counts the amount of elements in the collection that satisfy a given predicate  

## Collection

`apply`: Creates a new Collection of this type from the output of a given callback that takes this Collection as its argument  
`take`: Creates a new Collection with up to $number first elements from this one  
`map`: Applies a callback for each value-key-pair in the Collection and returns a new one with values replaced by the return values from the callback  
`filter`: Creates a collection with the values of this collection that match a given predicate  
`concatenate`: Creates a Collection with elements from this and another one  
`union`: Creates a Collection with key-value pairs in the `$other` Collection overriding ones in `$this` Collection  
`values`: Get a Collection with just the values from this Collection  
`keys`: Get a Collection with the keys from this one as values  
`indexBy`: Reindex the Collection using a given callback  
`groupBy`: Group the values in the Collection into nested Collections according to a given callback  
`pick`: Get a Collection with a key or member property picked from each value  
`invoke`: Map this Collection by invoking a method on every value  
`flatten`: Flatten nested arrays and Traversables  
`unique`: Get a Collection with only the unique values from this one  

## Most relevant Collection implementations

`ArrayCollection`: Basic Collection backed by a plain PHP array.  
`OuterCollection`: A decorator for a Collection. Can easily be extended to provide more collection operations without locking down the implementation specifics.  

# Running the unit tests

	cd tests
	phpunit --bootstrap bootstrap.php Xi

# TODO

- Collection implementations backed by SPL (SplFixedArray, SplDoublyLinkedList?)
- Once PHP 5.4 comes about, implementations can be significantly simplified using traits
- The option to lazily apply some of the available transformations
