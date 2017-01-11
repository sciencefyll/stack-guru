<?php

namespace StackGuru\CoreLogic\Utils;

class StringParser
{
	/**
	 * Returns an array of strings extracted from a given sentence.
	 * The strings are the words, white space is used as a delimiter.
	 * The arrays order reflects the words position in the sentence.
	 *
	 * Explode alternative.
	 * Does not work like explode when limit arguemnt is supplied!!!
	 * NOTE: `explode(' ', $sentence, 2) != getFirstWords($sentence, 2);` see example below.
	 *
	 * 	$sentence = "Nighty Anders test lol";
	 *  getFirstWords($sentence, 2) => ["Nighty", "Anders"]
	 *  explode(' ', $sentence, 2) => ["Nighty", "Anders test lol"]
	 *
	 * @param  string $sentence Sentence where words are extracted from.
	 * @param  int    $n        Number of words you want to extract.
	 * @return array           	Empty if no words were extracted.
	 */
	public static function getFirstWords (string $sentence, int $n) : array
	{
		$blocks = str_split($sentence); // in C: converts char array, faster than explode in theory..

		$words = []; // result.
		$len = sizeof($blocks); // number of letters in sentence.

		// iterates the sentence
		for ($i = 0, $p = 0; $i < $n && $p < $len; $i++, $p++) {
			// Word to be extracted
			$word = "";

			// Iterate the blocks until a whitespace is hit
			while ($p < $len && ' ' !== $blocks[$p]) {
				$word .= $blocks[$p];

				// Go to the next char
				$p += 1;
			}

			// store the new word
			$words[$i] = $word;

			// let $p jump one fourth, since it now should skip the whitespace
			// $p += 1;
			// NOTICE: this is done by the loop automatically :)
		}


		return $words;
	}
}
