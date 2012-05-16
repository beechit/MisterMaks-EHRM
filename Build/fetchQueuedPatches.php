<?php
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 1);

class FetchHandler {

	/**
	 * @var string The path to this script
	 */
	protected $rootPath;

	/**
	 * @var string The path to the FLOW3 project root folder
	 */
	protected $projectPath;

	/**
	 * @var array List of command files to process
	 */
	protected $commandFiles = array();

	/**
	 * Construct the FetchHandler
	 */
	public function __construct() {
		$this->rootPath = dirname(__FILE__) . '/QueuedPatches/';
		$this->projectPath = dirname(__DIR__ . '../') . '/';
	}

	/**
	 * Fetch all command files and process them
	 * @return void
	 */
	public function fetch() {
		$this->getCommandFiles($this->rootPath);
		foreach ($this->commandFiles as $commandFile) {
			$packageDirectory = $this->projectPath . str_replace($this->rootPath, '', $commandFile);
			if (is_dir($packageDirectory)) {
				$this->patchPackage($packageDirectory, $commandFile);
			}
		}
	}

	public function patchPackage($path, $commandFile) {
		$commands = explode(chr(13), file_get_contents($commandFile));
		foreach ($commands as $command) {
			if (trim($command) === '') {
				continue;
			}
			$cmd = sprintf('cd %s && %s', $path, $command);
			echo exec($cmd);
		}
	}

	/**
	 * Recursively fetch all command files in a given path
	 * @param $path
	 * @return void
	 */
	protected function getCommandFiles($path) {
		$files = $this->getDirectoryContent($path);
		foreach ($files as $file) {
			if (is_dir($file)) {
				$this->getCommandFiles($file . '/');
			} else {
				$this->commandFiles[] = $file;
			}
		}
	}

	/**
	 * Get all files and directories in a given path
	 *
	 * @param $path
	 * @return array
	 */
	protected function getDirectoryContent($path) {
		$content = array();
		$directoryIterator = new DirectoryIterator($path);
		foreach ($directoryIterator as $entry) {
			if (!$entry->isDot()) {
				$content[] = $entry->getPathname();
			}
		}

		return $content;
	}
}

/**
 * Initialize the handler and fetch all changes
 */
$handler = new FetchHandler();
$handler->fetch();

?>