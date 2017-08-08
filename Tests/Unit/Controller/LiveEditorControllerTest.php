<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Grigori Prokhorov <gee@friggin.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Test case for class Tx_Tslive_Controller_LiveEditorController.
 *
 * @version $Id:$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage TSLive
 *
 * @author Grigori Prokhorov <gee@friggin.de>
 */
class Tx_Tslive_Controller_LiveEditorControllerTest extends Tx_Extbase_Tests_Unit_BaseTestCase {

	/**
	 * @var Tx_Tslive_Controller_LiveEditorController
	 */
	protected $fixture;

	/**
	 * (non-PHPdoc)
	 * @see PHPUnit_Framework_TestCase::setUp()
	 */
	protected function setUp() {
		$this->getAccessibleMock('Tx_Tslive_Controller_LiveEditorController');

		$view = $this->getMock('Tx_Fluid_View_TemplateView', array(), array(), '', FALSE);
		$this->fixture->_set('view', $view);
	/*
		$this->fixture =  $this->getAccessibleMock('Tx_Tslive_Controller_LiveEditorControllerTest', array('dummy'));

		// Mock configuration manager
		$settings = array(
			'listPid' => 0
		);
		$configurationManager = $this->getMock('Tx_Extbase_Configuration_ConfigurationManager', array('getConfiguration'));
		$configurationManager->expects($this->any())->method('getConfiguration')->with(
			Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS
		)->will($this->returnValue($settings));
		$this->fixture->injectConfigurationManager($configurationManager);
		// Mock view
		$view = $this->getMock('Tx_Fluid_View_TemplateView', array(), array(), '', FALSE);
		$this->fixture->_set('view', $view);
		// Set object manager
		$this->fixture->injectObjectManager(new Tx_Extbase_Object_ObjectManager());
	*/
	}

	/**
	 * (non-PHPdoc)
	 * @see PHPUnit_Framework_TestCase::tearDown()
	 */
	protected function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function indexActionParseUserTypoScript() {
	/*
		$parameter = array(
			'pid' => 0,
			'cols' => 0,
			'ctype' => 'list',
			'list_type' => array(
				'aldiproducts_list',
				'aldiproducts_listthemeimageproduct',
				'aldiproducts_listtextandproduct'
			)
		);
		$GLOBALS['TSFE']->id = 0;
		// Mock repository to return product list for test
		$repository = $this->getMock('Tx_DkdExtbase_Domain_Repository_ContentRepository', array('findByAndConstraints'));
		$repository->expects($this->once())->method('findByAndConstraints')->with($parameter)->will($this->returnValue(array()));

		$pageRepository = $this->getMock('Tx_DkdExtbase_Domain_Repository_PageRepository', array('findByUid'));
		$pageRepository->expects($this->any())->method('findByUid')->with(0)->will($this->returnValue(new Tx_DkdExtbase_Domain_Model_Page()));

		$productRepository = new Tx_AldiProducts_Domain_Repository_ProductRepository();
		$productRepository->injectContentRepository($repository);

		$this->fixture->injectProductRepository($productRepository);
		$this->fixture->injectPageRepository($pageRepository);

		$this->fixture->showAction(new Tx_AldiProducts_Domain_Model_Product());
	*/
	}

}
?>