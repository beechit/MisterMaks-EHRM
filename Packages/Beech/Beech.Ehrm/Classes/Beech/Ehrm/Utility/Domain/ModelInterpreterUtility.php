<?php
namespace Beech\Ehrm\Utility\Domain;

use TYPO3\Flow\Annotations as Flow;
use Symfony\Component\Yaml\Yaml;

/**
 * Class which support generation controllers and crud action templates
 * by interpreting yaml files
 *
 */
class ModelInterpreterUtility {

	/**
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 * @Flow\Inject
	 */
	protected $objectManager;

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
	 * @var \TYPO3\Flow\Reflection\ReflectionService
	 * @Flow\Inject
	 */
	protected $reflectionService;

	/**
	 * Location of yaml file
	 * @var string
	 */
	protected $yamlModel;

	/**
	 * @var array
	 */
	protected $generatedFiles = array();

	/**
	 * Method to generate crud templates based on yaml file.
	 *
	 * @param $packageKey
	 * @param $subpackage
	 * @param $modelName
	 * @param $viewName
	 * @param $template
	 * @param bool $overwrite
	 */
	public function generateView($packageKey, $subpackage, $modelName, $viewName, $template, $overwrite = FALSE) {
		$contextVariables = Yaml::parse(\TYPO3\Flow\Utility\Files::getFileContents($this->yamlModel));
		$contextVariables['properties'] = $this->recurrentPrepareContext($contextVariables['properties']);
		$contextVariables['packageKey'] = $packageKey;
		$contextVariables['controllerName'] = $modelName;
		$contextVariables['entity'] = strtolower($modelName);
		$contextVariables['viewName'] = $viewName;
		/**
		 * TODO: Tweak template generator to keep nice formatting without using 'removeEmptyLines' method
		 */
		$fileContent = $this->removeEmptyLines($this->renderTemplate($template, $contextVariables));
		$viewFilename = $viewName . '.html';
		$viewPath = 'resource://' . $packageKey . '/Private/Templates/' . $subpackage . '/'. $modelName . '/';
		$targetPathAndFilename = $viewPath . $viewFilename;
		$this->generateFile($targetPathAndFilename, $fileContent, $overwrite);
	}

	/**
	 * Method to generate controller based on yaml file
	 *
	 * @param $packageKey
	 * @param $subpackage
	 * @param $modelName
	 * @param $template
	 * @param bool $overwrite
	 */
	public function generateController($packageKey, $subpackage, $modelName, $template, $overwrite = FALSE) {
		$contextVariables = Yaml::parse(\TYPO3\Flow\Utility\Files::getFileContents($this->yamlModel));
		$contextVariables['properties'] = $this->recurrentPrepareContext($contextVariables['properties']);
		$contextVariables['packageKey'] = $packageKey;
		$contextVariables['packageNamespace'] = str_replace('.', '\\', $packageKey);
		$contextVariables['subpackage'] = $subpackage;
		$contextVariables['isInSubpackage'] = ($subpackage != '');
		$contextVariables['controllerName'] = $modelName;
		$contextVariables['controllerClassName'] = $modelName.'Controller';
		$contextVariables['modelName'] = $modelName;
		$contextVariables['repositoryClassName'] = '\\' . str_replace('.', '\\', $packageKey) . '\Domain\Repository\\' . $modelName . 'Repository';
		$contextVariables['modelFullClassName'] = '\\' . str_replace('.', '\\', $packageKey) . '\Domain\Model\\' . $modelName;
		$contextVariables['modelClassName'] = ucfirst($contextVariables['modelName']);
		$contextVariables['entity'] = strtolower($modelName);
		$fileContent = $this->renderTemplate($template, $contextVariables);

		$controllerFilename = $modelName . 'Controller.php';
		$controllerPath = $this->packageManager->getPackage($packageKey)->getClassesNamespaceEntryPath() . $subpackage . '/Controller/';
		$targetPathAndFilename = $controllerPath . $controllerFilename;

		$this->generateFile($targetPathAndFilename, $fileContent, $overwrite);
	}

	/**
	 * Rules for selecting field type
	 * Based on that field type, fluid create input field.
	 *
	 * @param $field
	 * @return string
	 */
	public function recognizeFieldType($field) {
		$fieldType = 'textfield';

		if (isset($field['visible']) && !$field['visible'] ) {
			$fieldType = 'hidden';
		} elseif (isset($field['type']) && substr($field['type'], 0, 6) === 'string') {
			$fieldType = 'textfield';
			if (isset($field['options']) || substr($field['id'], 0, 7) === 'country') {
				$fieldType = 'select';
			}
		} elseif (isset($field['type']) && $field['type'] == 'array') {
			$fieldType = 'fieldset';
		}
		return $fieldType;
	}

	/**
	 *
	 * @param $content
	 * @return mixed
	 */
	protected function recurrentPrepareContext($content) {
		foreach ($content as $key => $field) {
			$content[$key]['fieldType'] = $this->recognizeFieldType($field);
			if ($content[$key]['fieldType'] == 'fieldset' && isset($content[$key]['items'])) {
				$content[$key]['items'] = $this->recurrentPrepareContext($content[$key]['items']);
			}
			if ($content[$key]['fieldType'] == 'select') {
				$content[$key] = $this->prepareOptionsForSelect($field);
			}
		}
		return $content;
	}

	/**
	 * Prepare options array
	 *
	 * @param $field
	 */
	private function prepareOptionsForSelect($field) {
		$values = array();
		if (isset($field['options']['values']) && !empty($field['options']['values']) || substr($field['id'], 0, 7) === 'country') {
			if (substr($field['id'], 0, 7) === 'country') {
				$field['options']['values'] = $this->prepareCountries();
			}
			foreach ($field['options']['values'] as $k => $value) {
				$values[] = $k . ':' . '\'' . $value .'\'';
			}
			$field['options']['values'] = '{' . implode(', ', $values) . '}';
		} else {
			$field['options']['values'] = '{0: " "}';
		}
		return $field;
	}

	/**
	 * @return array
	 */
	protected function prepareCountries() {
		$yamlCountries = Yaml::parse(file_get_contents('resource://Beech.Ehrm/Private/Generator/Yaml/country.yaml'));
		$keys = $yamlCountries['country']['values'];
		$values = $yamlCountries['country']['translation']['en'];
		$countries = array();
		foreach($keys as $index => $key) {
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
	 * @param boolean $force
	 * @return void
	 */
	protected function generateFile($targetPathAndFilename, $fileContent, $force = FALSE) {
		if (!is_dir(dirname($targetPathAndFilename))) {
			\TYPO3\Flow\Utility\Files::createDirectoryRecursively(dirname($targetPathAndFilename));
		}

		if (substr($targetPathAndFilename, 0, 11) === 'resource://') {
			list($packageKey, $resourcePath) = explode('/', substr($targetPathAndFilename, 11), 2);
			$relativeTargetPathAndFilename = $packageKey . '/Resources/' . $resourcePath;
		} else {
			$relativeTargetPathAndFilename = substr($targetPathAndFilename, strrpos(substr($targetPathAndFilename, 0, strpos($targetPathAndFilename, 'Classes/') - 1), '/') + 1);
		}

		if (!file_exists($targetPathAndFilename) || $force === TRUE) {
			file_put_contents($targetPathAndFilename, $fileContent);
			$this->generatedFiles[] = 'Created .../' . $relativeTargetPathAndFilename;
		} else {
			$this->generatedFiles[] = 'Omitted .../' . $relativeTargetPathAndFilename;
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

	public function setYamlModel($packageKey, $modelName, $yamlName) {
		if (empty($yamlName)) {
			$yamlName = strtolower($modelName).'.yaml';
		}
		$this->yamlModel = sprintf('resource://%s/Private/Templates/Domain/Model/%s', $packageKey, $yamlName);
	}

	public function isYamlModelAvailable() {
		return file_exists($this->yamlModel) ? TRUE : FALSE;
	}

	/**
	 * Remove from file content white lines which are not necessary
	 * @param $content
	 * @return string
	 */
	private function removeEmptyLines($content) {
		$contentArray = explode("\n", $content);
		foreach ($contentArray as $lineNumber => $lineContent) {
			if (strlen(trim($lineContent)) === 0) {
				unset($contentArray[$lineNumber]);
			};
		}
		return implode("\n", $contentArray);
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