<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Grigori Prokhorov <gee@friggin.de>
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


/**
 * @package tslive
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 *
 */
class Tx_Tslive_Controller_LiveEditorController extends Tx_Extbase_MVC_Controller_ActionController {
	/**
	 * is called before every action call
	 *
	 * @return void
	 */
	public function initializeAction() {
		global $TSFE;
		global $TT;

		$this->requestArguments = $this->request->getArguments();

		Tx_Extbase_Utility_FrontendSimulator::simulateFrontendEnvironment();

		$temp_TTclassName = t3lib_div::makeInstanceClassName('t3lib_timeTrack');
		$TT = new $temp_TTclassName();
		$TT->start();
			// Look up the page
		$TSFE->sys_page = t3lib_div::makeInstance('t3lib_pageSelect');
		$TSFE->sys_page->init($TSFE->showHiddenPage);

		$TSFE->csConvObj = t3lib_div::makeInstance('t3lib_cs');
	}

	/**
	 * Main action
	 *
	 * @return void
	 */
	protected function indexAction() {
		/** @var $TYPO3_DB t3lib_DB */
		global $TYPO3_DB;
		global $TCA;
		global $TSFE;

		$tsBase = array(
			'live' => 'COA',
			'live.' => array()
		);

		$ts = $this->requestArguments['typoscript'];
		$cobjdata = $this->requestArguments['cobjdata'];
		$currenttable = $this->requestArguments['currenttable'];
		$cobjdatauid = $this->requestArguments['cobjdatauid'];
		$cobjdatatable = $this->requestArguments['cobjdatatable'];
		$currentcobjdata = $this->requestArguments['currentcobjdata'];
		$parseablecobjdata = $this->requestArguments['parseablecobjdata'];
		$clearcobj = $this->requestArguments['clearcobj'];

		$tablelist = array('0' => '');
		$tablelistRes = $TYPO3_DB->sql_query('SHOW TABLES');
		while ($tablelistRet = $TYPO3_DB->sql_fetch_assoc($tablelistRes)) {
			$tablelist[reset($tablelistRet)] = reset($tablelistRet);
		}

		$this->view->assign('tablelist', $tablelist);
		$this->view->assign('currenttable', $currenttable);

		if (!empty($currenttable)) {
			$tableCA = t3lib_div::loadTCA($currenttable);

			$recordList = $TYPO3_DB->exec_SELECTgetRows(
				'uid, ' . $TCA[$currenttable]['ctrl']['label'] . ' AS label',
				$currenttable
			);

			$this->view->assign('recordList', $recordList);
		}

		if ($clearcobj === '1') {
			$cObjData = array();
		} else {
			if (empty($currentcobjdata)
					// TODO: find a more suitable solution to check if $cobjdata has changed
				|| http_build_query(array('parsedcobjdata' => $this->parseCObjData($cobjdata))) !== $parseablecobjdata
			) {
				if (!empty($cobjdatatable) && intval($cobjdatauid) > 0) {
					$recordData = $TYPO3_DB->exec_SELECTgetSingleRow('*', $cobjdatatable, 'uid = ' . $cobjdatauid);
					$cObjData = $recordData;
				}

				if (!empty($cobjdata)) {
					$parsedData = $this->parseCObjData($cobjdata);

					if (empty($parsedData)) {
						$this->flashMessageContainer->add(
							'Input data for cObj data seems to be malformed. Please refer to parsing syntax definition on the right.',
							'Input Data Rendering failed!',
							t3lib_Flashmessage::ERROR
						);
					} else {
						$cObjData = $parsedData;
					}
				}
			} else {
				parse_str($parseablecobjdata);
				if (is_array($parsedcobjdata)) {
					$cObjData = $parsedcobjdata;
				}
			}
		}

		if(!empty($cObjData)) {
			$TSFE->cObj->data = $cObjData;

			$this->view->assign('parseablecobjdata', http_build_query(array('parsedcobjdata' => $cObjData)));
			$this->view->assign('currentcobjdata', var_export($cObjData, 1));
		}

		if (!empty($ts)) {
			$TSParser = new t3lib_TSparser();
			$TSParser->parse($ts);

			$tsBase['live.'] = $TSParser->setup;

			$parsedTs = $TSFE->cObj->cObjGetSingle($tsBase['live'], $tsBase['live.']);

			$this->view->assign('rawTs', $ts);
			$this->view->assign('parsedTs', $parsedTs);
		}

		$this->view->assign('cobjdata', $cobjdata);
	}

	/**
	 * Renders a string into a json-string and returns the decoded string as an array
	 *
	 * @param $cobjdata
	 * @return mixed
	 */
	protected function parseCObjData($cobjdata) {
		$rawData = explode("\n", $cobjdata);
		$parsedData = array();
		$json = '';

		if (is_array($rawData)) {
			foreach ($rawData as $line) {
				$line = trim($line, "\n ");

				preg_match('/^(.*?)(=|\[|\])(.*?)$/', $line, $matches);

				if (!empty($matches)) {
					switch (trim($matches[2])) {
						case '=':
							$json .= '"' . trim($matches[1]) . '":"' . trim($matches[3]) . '",';
							break;
						case '[':
							$json .= '"' . trim($matches[1]) . '":{';
							break;
						case ']':
							$json = rtrim($json, ',');
							$json .= '},';
							break;
					}
				}
			}

			$json = '{' . rtrim($json, ',') . '}';
		}

		$parsedData = json_decode($json, 1);

		return $parsedData;
	}
}