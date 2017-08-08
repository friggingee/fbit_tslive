# Module configuration
module.tx_tslive {
	view {
		templateRootPaths.0 = {$module.tx_tslive.view.templateRootPath}
		partialRootPaths.0 = {$module.tx_tslive.view.partialRootPath}
		layoutRootPaths.0 = {$module.tx_tslive.view.layoutRootPath}
	}
	features {
		#skipDefaultArguments = 1
		# if set to 1, the enable fields are ignored in BE context
		ignoreAllEnableFieldsInBe = 0
		# Should be on by default, but can be disabled if all action in the plugin are uncached
		requireCHashArgumentForActionArguments = 1
	}
	mvc {
		callDefaultActionIfActionCantBeResolved = 1
	}
}
plugin.tx_tslive < module.tx_tslive