<?php
use \Magento\Framework\Component\ComponentRegistrar;
ComponentRegistrar::register(
    ComponentRegistrar::THEME,
    'frontend/testTheme/m2-theme',
    __DIR__
);
