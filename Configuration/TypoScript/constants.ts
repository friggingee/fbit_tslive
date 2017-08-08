module.tx_tslive {
	view {
		# cat=module.tx_fbittslive/file; type=string; label=Path to template root (BE)
		templateRootPath = EXT:fbit_tslive/Resources/Private/Templates/
		# cat=module.tx_fbittslive/file; type=string; label=Path to template partials (BE)
		partialRootPath = EXT:fbit_tslive/Resources/Private/Partials/
		# cat=module.tx_fbittslive/file; type=string; label=Path to template layouts (BE)
		layoutRootPath = EXT:fbit_tslive/Resources/Private/Layouts/
	}
	persistence {
		# cat=module.tx_fbittslive//a; type=int+; label=Default storage PID
		storagePid =
	}
}
