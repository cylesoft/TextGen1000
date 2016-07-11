<?php

namespace Cylesoft\TextGen1000;

/**
 * A MadLibs-ish text generator. Input a formula sentence for text, and this'll generate some funkyness.
 *
 * Usage:
 *
 * // Define your sentence, with anything between double underscores considered a "node".
 * $sentence = 'The __thing___capitalize doesn\'t __feeling__ your __thing___uppercase in this __thing__scape, you __thing__-loving __thing__-__thing__';
 * // In that example, the "nodes" are __thing__ and __feeling__
 * // Notice that you can have modifiers on certain nodes by adding another underscore and a modifier name, i.e. _uppercase, making __thing___uppercase
 * // That modifier will be run when generating a new sentence, every time.
 *
 * // Next, define your corpus of nodes to actual word possibilities.
 * // When a new sentence is generated, these words may be used.
 * $corpus = [
 *     'thing' => [
 *         'duck',
 *         'cat',
 *         'dog',
 *         'wolf',
 *     ],
 *     'feeling' => [
 *         'like',
 *         'care about',
 *         'bend',
 *         'hear',
 *     ],
 * ];
 *
 * // Finally, instantiate a new generator with your input sentence and your corpus of possibilities.
 * $generator = new \Cylesoft\TextGen1000\Generator($sentence, $corpus);
 *
 * // Generate as many as you want, each will be different.
 * echo $generator->generate() . "\n"; // The Duck doesn't care about your DUCK in this catscape, you cat-loving cat-cat
 * echo $generator->generate() . "\n"; // The Cat doesn't like your CAT in this dogscape, you wolf-loving duck-dog
 * echo $generator->generate() . "\n"; // The Dog doesn't hear your DUCK in this dogscape, you cat-loving dog-wolf
 *
 */
class Generator
{
    /**
     * The regular expression used to find nodes in the input sentence.
     * @const string
     */
    const NODE_FINDER_REGEX = '/__([a-z0-9]+)__(?:_([a-z0-9]+))?/i';

    /**
     * The input formula sentence.
     * @var string
     */
    protected $sentence;

    /**
     * The nodes and potential text to use as replacements.
     * @var array of strings => array of strings, i.e. $corpus['animal'] = ['fish', 'dog', 'cat']
     */
    protected $corpus;

    /**
     * Construct a new TextGen1000 instance for the given sentence and corpus.
     *
     * @param string $sentence The sentence formula to use.
     * @param array $corpus An array of nodes and their possible replacement strings; $corpus['animal'] = ['fish', 'dog', 'cat']
     * @return TextGen1000
     */
    public function __construct($sentence, array $corpus)
    {
        if (!is_string($sentence) || empty($sentence)) {
            throw new \InvalidArgumentException('Generator needs a sentence formula string.');
        }

        if (empty($corpus)) {
            throw new \InvalidArgumentException('Generator needs a corpus of possible replacements to use.');
        }

        $this->sentence = $sentence;
        $this->corpus = $corpus;
    }

    /**
     * Generate a fresh new sentence given the formula sentence and corpus of replacements.
     *
     * @return string
     */
    public function generate()
    {
        return preg_replace_callback(
            static::NODE_FINDER_REGEX, // use our cool node finer regex
            'static::replace', // run the replace() method on this class
            $this->sentence // use the given sentence
        );
    }

    /**
     * Callable to replace the given regex match with a word.
     *
     * @param array $matches A regex match array passed from preg_replace_callback()
     * @return string Word to replace the node
     */
    protected function replace(array $matches)
    {
        // get the node name
        $corpus_node_name = $matches[1];

        if (empty($this->corpus[$corpus_node_name])) {
            // maybe should throw an exception here, but let's assume things are intended
            return $matches[0]; // bail out early if there is no corpus of words for this node
        }

        // get the specific corpus of words for the found node
        $corpus = $this->corpus[$corpus_node_name];

        // pick a random word
        $random_word = $corpus[mt_rand(0, (count($corpus) - 1))];

        // see if there's also a modifier on this individual node
        if (!empty($matches[2])) {
            $modifier_name = $matches[2];
            // if this class has a modifier method with the given name, use it
            if (method_exists(get_class(), 'modifier_' . $modifier_name)) {
                return call_user_func('static::modifier_' . $modifier_name, $random_word);
            }
        }

        // return the random word to replace this individual node
        return $random_word;
    }

    /**
     * Capitalize the words in the given string.
     *
     * @param string $string_input The input string to capitalize.
     * @return string
     */
    protected function modifier_capitalize($string_input)
    {
        return ucwords($string_input);
    }

    /**
     * ALL CAPS the given string.
     *
     * @param string $string_input The input string to make ALL CAPS.
     * @return string
     */
    protected function modifier_uppercase($string_input)
    {
        return strtoupper($string_input);
    }

    /**
     * all lowercase the given string.
     *
     * @param string $string_input The input string to make all lowercase.
     * @return string
     */
    protected function modifier_lowercase($string_input)
    {
        return strtolower($string_input);
    }
}