<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

if (! function_exists('view')) {
    /**
     * Overrides the framework's default view() helper.
     *
     * Every view in this app checks for validation errors with the bare
     * pattern `<?php if (! empty($errors ?? [])): ?>` rather than calling
     * `session('errors')` directly. Controllers set those errors as flash
     * data via `redirect()->back()->with('errors', $this->validator->getErrors())`,
     * but plain CodeIgniter never surfaces flash data as a view variable
     * automatically — so without this override, validation error messages
     * are silently lost and the form just looks like it did nothing.
     *
     * This auto-injects 'errors' from session flashdata (if the
     * controller didn't already pass its own 'errors' key) before
     * rendering, so every existing view's $errors check works as written.
     * Everything else is identical to CodeIgniter's own view() helper.
     */
    function view(string $name, array $data = [], array $options = []): string
    {
        if (! array_key_exists('errors', $data)) {
            $flashErrors = session('errors');
            if (! empty($flashErrors)) {
                $data['errors'] = $flashErrors;
            }
        }

        $renderer = service('renderer');

        $config   = config(Config\View::class);
        $saveData = $config->saveData;

        if (array_key_exists('saveData', $options)) {
            $saveData = (bool) $options['saveData'];
            unset($options['saveData']);
        }

        return $renderer->setData($data, 'raw')->render($name, $options, $saveData);
    }
}
