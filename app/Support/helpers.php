<?php

if (! function_exists('user')) {
    /**
     * Get the authenticated user and/or attributes.
     *
     * @param string|null $attribute
     *
     * @return mixed
     */
    function user(?string $attribute = null)
    {
        if (! is_null($attribute)) {
            return auth()->user()->{$attribute};
        }

        return auth()->user();
    }
}
