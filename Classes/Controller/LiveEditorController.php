<?php

namespace FBIT\TSLive\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013-2017 Grigori Prokhorov <hello@feinberg.it>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Core\Charset\CharsetConverter;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Http\AjaxRequestHandler;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Utility\FrontendSimulatorUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * @package fbit_tslive
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 *
 */
class LiveEditorController extends ActionController
{
    /**
     * @var string
     */
    protected $defaultViewObjectName = BackendTemplateView::class;

    /**
     * @var BackendTemplateView
     */
    protected $view;

    /**
     * @var array
     */
    protected $requestArguments = [];

    public function initializeView(ViewInterface $view) {
        $this->view->getModuleTemplate()->getPageRenderer()->loadRequireJsModule('TYPO3/CMS/FbitTslive/Module');
    }

    /**
     * is called before every action call
     *
     * @return void
     */
    public function initializeAction()
    {
        $this->requestArguments = $this->request->getArguments();
    }

    /**
     * Main action
     *
     * @return void
     */
    protected function indexAction()
    {
        $ts = $this->requestArguments['typoscript'];
        // $cobjdata = $this->requestArguments['cobjdata'];
        // $currenttable = $this->requestArguments['currenttable'];
        // $cobjdatauid = $this->requestArguments['cobjdatauid'];
        // $cobjdatatable = $this->requestArguments['cobjdatatable'];
        // $currentcobjdata = $this->requestArguments['currentcobjdata'];
        // $parseablecobjdata = $this->requestArguments['parseablecobjdata'];
        // $clearcobj = $this->requestArguments['clearcobj'];
        //
        // $tablelist = ['0' => ''];
        // $tablelistRes = $GLOBALS['TYPO3_DB']->sql_query('SHOW TABLES');
        // while ($tablelistRet = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($tablelistRes)) {
        //     $tablelist[reset($tablelistRet)] = reset($tablelistRet);
        // }
        //
        // $this->view->assign('tablelist', $tablelist);
        // $this->view->assign('currenttable', $currenttable);
        //
        // if (!empty($currenttable)) {
        //     $tableCA = $GLOBALS['TCA'][$currenttable];
        //
        //     $recordList = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
        //         'uid, ' . $GLOBALS['TCA'][$currenttable]['ctrl']['label'] . ' AS label',
        //         $currenttable
        //     );
        //
        //     $this->view->assign('recordList', $recordList);
        // }
        //
        // if ($clearcobj === '1') {
        //     $cObjData = [];
        // } else {
        //     if (empty($currentcobjdata)
        //         // TODO: find a more suitable solution to check if $cobjdata has changed
        //         || http_build_query(['parsedcobjdata' => $this->parseCObjData($cobjdata)]) !== $parseablecobjdata
        //     ) {
        //         if (!empty($cobjdatatable) && intval($cobjdatauid) > 0) {
        //             $recordData = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', $cobjdatatable, 'uid = ' . $cobjdatauid);
        //             $cObjData = $recordData;
        //         }
        //
        //         if (!empty($cobjdata)) {
        //             $parsedData = $this->parseCObjData($cobjdata);
        //
        //             if (empty($parsedData)) {
        //                 $this->addFlashMessage(
        //                     'Input data for cObj data seems to be malformed. Please refer to parsing syntax definition on the right.',
        //                     'Input Data Rendering failed!',
        //                     FlashMessage::ERROR
        //                 );
        //             } else {
        //                 $cObjData = $parsedData;
        //             }
        //         }
        //     } else {
        //         parse_str($parseablecobjdata);
        //         if (is_array($parsedcobjdata)) {
        //             $cObjData = $parsedcobjdata;
        //         }
        //     }
        // }
        //
        // if (!empty($cObjData)) {
        //     $GLOBALS['TSFE']->cObj->data = $cObjData;
        //
        //     $this->view->assign('parseablecobjdata', http_build_query(['parsedcobjdata' => $cObjData]));
        //     $this->view->assign('currentcobjdata', var_export($cObjData, 1));
        // }

        if (!empty($ts)) {
            $parsedTs = $this->parseTS(['ts' => $ts]);

            $this->view->assign('rawTs', $ts);
            $this->view->assign('parsedTs', $parsedTs);
        }

        // $this->view->assign('cobjdata', $cobjdata);
    }

    public function parseTS(array $params, AjaxRequestHandler &$ajaxRequestHandler = null) {
        $ts = $params['ts'];
        if ($params['request'] instanceof ServerRequest) {
            $ts = $params['request']->getParsedBody()['ts'];
        }

        $tsBase = [
            'live' => 'COA',
            'live.' => [],
        ];

        /** @var TypoScriptParser $TSParser */
        $TSParser = GeneralUtility::makeInstance(TypoScriptParser::class);
        $TSParser->parse($ts);

        $tsBase['live.'] = $TSParser->setup;

        FrontendSimulatorUtility::simulateFrontendEnvironment();

        /** @var TimeTracker $GLOBALS['TT'] */
        $GLOBALS['TT'] = GeneralUtility::makeInstance(TimeTracker::class);
        $GLOBALS['TT']->start();

        // Look up the page
        $GLOBALS['TSFE']->sys_page = GeneralUtility::makeInstance(PageRepository::class);
        $GLOBALS['TSFE']->sys_page->init($GLOBALS['TSFE']->showHiddenPage);

        $GLOBALS['TSFE']->csConvObj = GeneralUtility::makeInstance(CharsetConverter::class);

        $parsedTs = $GLOBALS['TSFE']->cObj->cObjGetSingle($tsBase['live'], $tsBase['live.']);

        if ($ajaxRequestHandler instanceof AjaxRequestHandler) {
            $ajaxRequestHandler->setContentFormat('json');
            $ajaxRequestHandler->addContent('parsedTs', $parsedTs);
        } else {
            return $parsedTs;
        }
    }
    //
    // /**
    //  * Renders a string into a json-string and returns the decoded string as an array
    //  *
    //  * @param $cobjdata
    //  * @return mixed
    //  */
    // protected function parseCObjData($cobjdata)
    // {
    //     $rawData = explode("\n", $cobjdata);
    //     $parsedData = [];
    //     $json = '';
    //
    //     if (is_array($rawData)) {
    //         foreach ($rawData as $line) {
    //             $line = trim($line, "\n ");
    //
    //             preg_match('/^(.*?)(=|\[|\])(.*?)$/', $line, $matches);
    //
    //             if (!empty($matches)) {
    //                 switch (trim($matches[2])) {
    //                     case '=':
    //                         $json .= '"' . trim($matches[1]) . '":"' . trim($matches[3]) . '",';
    //                         break;
    //                     case '[':
    //                         $json .= '"' . trim($matches[1]) . '":{';
    //                         break;
    //                     case ']':
    //                         $json = rtrim($json, ',');
    //                         $json .= '},';
    //                         break;
    //                 }
    //             }
    //         }
    //
    //         $json = '{' . rtrim($json, ',') . '}';
    //     }
    //
    //     $parsedData = json_decode($json, 1);
    //
    //     return $parsedData;
    // }
}