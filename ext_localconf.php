<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {
        $extKey = 'fbit_tslive';

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
            $extKey,
            'constants',
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:fbit_tslive/Configuration/TypoScript/constants.ts">'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
            $extKey,
            'setup',
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:fbit_tslive/Configuration/TypoScript/setup.ts">'
        );
    }
);
