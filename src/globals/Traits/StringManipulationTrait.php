<?php


trait StringManipulationTrait
{
    /**
     * Convert a word to its singular form.
     *
     * @param string $word The word to be singularized.
     * @return string The singularized word.
     */
    public function singularize(string $word): string
    {
        // Implement singularization logic here
        return singularize($word);
    }

    /**
     * Convert a word to its plural form.
     *
     * @param string $word The word to be pluralized.
     * @return string The pluralized word.
     */
    public function pluralize(string $word): string
    {
        // Implement pluralization logic here
        return pluralize($word);
    }
}