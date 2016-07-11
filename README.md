# TextGen1000 by Cylesoft

This is a really simple/weird text generation/replacement thing.

Take a sentence formula like this:

```
Hey there __name__, how do you feel about __thing__?
```

And a corpus of words to replace those `__node__` things:

```
name: Nick, Donald, George
thing: pokemon, the 2016 election, cats, 1998
```

And then you mash it all together to get something like:

```
Hey there Nick, how do you feel about 1998?
```

Or any other random permutation of the above possible words.

Weird, huh? You can get pretty crazy with it.

## Installation

Put the `Cylesoft` folder with your PHP codes, and then:

```php
require_once 'Cylesoft/TextGen1000/Generator.php';
```

That's it.

## Usage

Check out the `usage.php` file, it has a lot more details on how to use this.

## Special Thanks

This was inspired by the [tracery](https://github.com/galaxykate/tracery) javascript library.

## Notes

This is basically a complicated wrapper around `preg_replace_callback()`, I know.

There's also basically no performance improvements made here. It's pretty raw.

## To Do

- [ ] Compound sentences; use the result of one generator within others.
- [ ] More modifiers; the starting 3 (uppercase, lowercase, capitalize) are not good enough
