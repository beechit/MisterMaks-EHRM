<?php
namespace Beech\Ehrm\Utility\Domain;

use TYPO3\Flow\Annotations as Flow;
use Symfony\Component\Yaml\Yaml;

/**
 * Class which support generation controllers and crud action templates
 * by interpreting yaml files
 */
class ModelInterpreterUtility {

	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 * @Flow\Inject
	 */
	protected $configurationManager;

	/**
	 * @var \TYPO3\Flow\Package\PackageManagerInterface
	 * @Flow\Inject
	 */
	protected $packageManager;

	/**
	 * @var \TYPO3\Fluid\Core\Parser\TemplateParser
	 * @Flow\Inject
	 */
	protected $templateParser;

	/**
	 * Method to generate crud templates based on yaml file.
	 *
	 * @param string $packageKey
	 * @param string $subpackage
	 * @param string $modelName
	 * @param string $viewName
	 * @param string $templatePathAndFilename
	 * @param boolean $overwrite
	 */
	public function generateView($packageKey, $subpackage, $modelName, $viewName, $templatePathAndFilename, $overwrite = FALSE) {
		$contextVariables = $this->configurationManager->getConfiguration('Models', $packageKey . '.' . $modelName);
		$contextVariables['properties'] = $this->recurrentPrepareContext($contextVariables['properties']);
		$contextVariables['packageKey'] = $packageKey;
		$contextVariables['controllerName'] = $modelName;
		$contextVariables['entity'] = strtolower($modelName);
		$contextVariables['viewName'] = $viewName;

		$fileContent = $this->renderTemplate($templatePathAndFilename, $contextVariables);
		$targetPathAndFilename = 'resource://' . $packageKey . '/Private/Templates/' . $subpackage . '/' . $modelName . '/' . $viewName . '.html';
		$this->generateFile($targetPathAndFilename, $fileContent, $overwrite);
	}

	/**
	 * Method to generate controller based on yaml file
	 *
	 * @param string $packageKey
	 * @param string $subpackage
	 * @param string $modelName
	 * @param string $templatePathAndFilename
	 * @param boolean $overwrite
	 * @return void
	 */
	public function generateController($packageKey, $subpackage, $modelName, $templatePathAndFilename, $overwrite = FALSE) {
		$contextVariables = $this->configurationManager->getConfiguration('Models', $packageKey . '.' . $modelName);
		$contextVariables['properties'] = $this->recurrentPrepareContext($contextVariables['properties']);
		$contextVariables['packageKey'] = $packageKey;
		$contextVariables['packageNamespace'] = str_replace('.', '\\', $packageKey);
		$contextVariables['subpackage'] = $subpackage;
		$contextVariables['isInSubpackage'] = ($subpackage != '');
		$contextVariables['modelName'] = $modelName;
		$contextVariables['modelClassName'] = ucfirst($contextVariables['modelName']);
		$contextVariables['modelFullClassName'] = '\\' . str_replace('.', '\\', $packageKey) . '\Domain\Model\\' . $contextVariables['modelClassName'];
		$contextVariables['controllerClassName'] = $contextVariables['modelClassName'] . 'Controller';
		$contextVariables['repositoryClassName'] = '\\' . str_replace('.', '\\', $packageKey) . '\Domain\Repository\\' . $contextVariables['modelClassName'] . 'Repository';
		$contextVariables['entity'] = strtolower($modelName);
		$fileContent = $this->renderTemplate($templatePathAndFilename, $contextVariables);

		$controllerFilename = $contextVariables['controllerClassName'] . '.php';
		$controllerPath = $this->packageManager->getPackage($packageKey)->getClassesNamespaceEntryPath() . $subpackage . '/Controller/';
		$targetPathAndFilename = $controllerPath . $controllerFilename;

		$this->generateFile($targetPathAndFilename, $fileContent, $overwrite);
	}

	/**
	 * Get properties of model from YAML file
	 *
	 * @param string $packageKey
	 * @param string $modelName
	 * @param string $configurationPath
	 * @return array
	 */
	public function getModelProperties($packageKey, $modelName, $configurationPath = NULL) {
		if ($configurationPath === NULL) {
			$modelConfiguration = $this->configurationManager->getConfiguration('Models', $packageKey . '.' . $modelName);
		} else {
			$modelConfiguration = $this->configurationManager->getConfiguration('Models', $configurationPath);
		}
		if ($modelConfiguration !== NULL) {
			$modelProperties = \TYPO3\Flow\Utility\Arrays::getValueByPath($modelConfiguration, 'properties');
			return is_array($modelProperties) ? $modelProperties : array();
		}
		return array();
	}

	/**
	 * Rules for selecting field type
	 * Based on that field type, fluid create input field.
	 *
	 * @param array $fieldConfiguration
	 * @return string
	 */
	public function recognizeFieldType(array $fieldConfiguration) {
		$fieldType = 'textfield';

		if (isset($fieldConfiguration['visible']) && !$fieldConfiguration['visible']) {
			$fieldType = 'hidden';
		} elseif (isset($fieldConfiguration['type']) && substr($fieldConfiguration['type'], 0, 6) === 'string') {
			$fieldType = 'textfield';
			if (isset($fieldConfiguration['options']) || substr($fieldConfiguration['id'], 0, 7) === 'country') {
				$fieldType = 'select';
			}
		} elseif (isset($fieldConfiguration['type']) && $fieldConfiguration['type'] == 'array') {
			$fieldType = 'fieldset';
		}

		return $fieldType;
	}

	/**
	 * @param array $fieldConfiguration
	 * @return array
	 */
	protected function recurrentPrepareContext(array $fieldConfiguration) {
		foreach ($fieldConfiguration as $key => $value) {
			$fieldConfiguration[$key]['fieldType'] = $this->recognizeFieldType($value);
			if ($fieldConfiguration[$key]['fieldType'] == 'fieldset' && isset($fieldConfiguration[$key]['items'])) {
				$fieldConfiguration[$key]['items'] = $this->recurrentPrepareContext($fieldConfiguration[$key]['items']);
			}
			if ($fieldConfiguration[$key]['fieldType'] == 'select') {
				$fieldConfiguration[$key] = $this->prepareOptionsForSelect($key, $value);
			}
		}

		return $fieldConfiguration;
	}

	/**
	 * Prepare options array
	 *
	 * @param string $propertyName
	 * @param array $fieldConfiguration
	 * @return array
	 */
	private function prepareOptionsForSelect($propertyName, array $fieldConfiguration) {
		$values = array();
		if (isset($fieldConfiguration['options']['values']) && !empty($fieldConfiguration['options']['values']) || substr($propertyName, 0, 7) === 'country') {
			if (substr($propertyName, 0, 7) === 'country') {
				$fieldConfiguration['options']['values'] = $this->prepareCountries();
			}
			foreach ($fieldConfiguration['options']['values'] as $k => $value) {
				$values[] = $k . ":'" . $value . "'";
			}
			$fieldConfiguration['options']['values'] = '{' . implode(', ', $values) . '}';
		} else {
			$fieldConfiguration['options']['values'] = '{0: " "}';
		}

		return $fieldConfiguration;
	}

	/**
	 * @return array
	 */
	protected function prepareCountries() {
		$yamlCountries = Yaml::parse(file_get_contents('resource://Beech.Ehrm/Private/Generator/Yaml/country.yaml'));
		$keys = $yamlCountries['country']['values'];
		$values = $yamlCountries['country']['translation']['en'];
		$countries = array();
		foreach ($keys as $index => $key) {
			$countries[$key] = $values[$index];
		}

		return $countries;
	}

	/**
	 * Generate a file with the given content and add it to the
	 * generated files
	 *
	 * @param string $targetPathAndFilename
	 * @param string $fileContent
	 * @param boolean $overwrite
	 * @return void
	 */
	protected function generateFile($targetPathAndFilename, $fileContent, $overwrite = FALSE) {
		if (!is_dir(dirname($targetPathAndFilename))) {
			\TYPO3\Flow\Utility\Files::createDirectoryRecursively(dirname($targetPathAndFilename));
		}

		if (!file_exists($targetPathAndFilename) || $overwrite === TRUE) {
			file_put_contents($targetPathAndFilename, $fileContent);
		}
	}

	/**
	 * Render the given template file with the given variables
	 *
	 * @param string $templatePathAndFilename
	 * @param array $contextVariables
	 * @return string
	 * @throws \TYPO3\Fluid\Core\Exception
	 */
	protected function renderTemplate($templatePathAndFilename, array $contextVariables) {
		$templateSource = \TYPO3\Flow\Utility\Files::getFileContents($templatePathAndFilename, FILE_TEXT);
		if ($templateSource === FALSE) {
			throw new \TYPO3\Fluid\Core\Exception('The template file "' . $templatePathAndFilename . '" could not be loaded.', 1225709595);
		}
		$parsedTemplate = $this->templateParser->parse($templateSource);

		$renderingContext = $this->buildRenderingContext($contextVariables);

		return $parsedTemplate->render($renderingContext);
	}

	/**
	 * @param string $packageKey
	 * @param string $modelName
	 * @return boolean
	 */
	public function isModelConfigurationAvailable($packageKey, $modelName) {
		return $this->configurationManager->getConfiguration('Models', $packageKey . '.' . $modelName) ? TRUE : FALSE;
	}

	/**
	 * Build the rendering context
	 *
	 * @param array $contextVariables
	 * @return \TYPO3\Fluid\Core\Rendering\RenderingContext
	 */
	protected function buildRenderingContext(array $contextVariables) {
		$renderingContext = new \TYPO3\Fluid\Core\Rendering\RenderingContext();

		$renderingContext->injectTemplateVariableContainer(new \TYPO3\Fluid\Core\ViewHelper\TemplateVariableContainer($contextVariables));
		$renderingContext->injectViewHelperVariableContainer(new \TYPO3\Fluid\Core\ViewHelper\ViewHelperVariableContainer());

		return $renderingContext;
	}
}

?>