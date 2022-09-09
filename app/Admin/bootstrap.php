<?php
Use Encore\Admin\Admin;
/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

Encore\Admin\Form::forget(['map', 'editor']);
Admin::js('/dashboard/js/app.js');
Admin::favicon('data:image/png;base64,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');