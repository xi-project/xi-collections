# Design Philosophy

PHP has always lacked solid collections support, with the vast majority of programmers making do 
with arrays and the related built-in functions. With the introduction of SPL in PHP 5.0 and the 
consequent extensions in 5.3, there are currently more choices than ever if all you want for is 
speed and answers to specific use cases. Array processing, however, is not significantly better 
than ten years ago, with the API about as comfortable and handy for everyday tasks as picking at 
your dinner with a shovel.

Xi Collections aims to rectify the situation and inject your workflow with a hearty dose of 
functional and declarative aspects. This is intended to result in more clarity in expressing and 
understanding processing collections of objects or data, allowing you to work faster and deliver 
more self-documenting code.

# Design Principles

- _Object immutability._ The collections' methods do not manipulate the collections' contents, 
but return a new collection instead.

- _API chainability._ No operation will return void and leave you in the dark; you'll always be 
able to continue where you left off.

- _Embracing functional programming._ The Collections API is chosen to facilitate a functional 
workflow. Existing FP-compatible functionality in PHP is prioritized for inclusion to the API, 
and other important concepts that are missing are stolen from other languages and libraries.

- _Out-of-the-box extensibility._ Decorators for separating the concrete Collection 
implementations from your modifications to the API are included. The interfaces tend to the 
minimal rather than extensive, making new implementations easier. Specifically, the whole of 
PHP's array functions is not built-in, but can be readily used if you need to.

# To-do

- Generic API description
- Usage examples

# Running the unit tests

  cd tests
  phpunit --bootstrap bootstrap.php Xi
