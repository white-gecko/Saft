<?php
class Saft_Helper_LinkeddataHelper extends Saft_Helper
{
    /**
     * Array of memoryModels with URI as key
     */
    private $_resources = array();

    /**
     * This method gets the statements for the given resource which are available according to the
     * Linked Data rules {@link http://www.w3.org/DesignIssues/LinkedData.html}
     * This method caches the memory model for one HTTP-request.
     *
     * @warning This function sends a web request and might take a long time
     * @hint You should run this function asynchrounusly or independent of your UI
     *
     * @param $resourceUri the URI of the resource to get
     * @return array of Statements with the resource as subject
     */
    public function getResource ($resourceUri)
    {
        if (isset($this->_resources[$resourceUri])) {
            return $this->_resources[$resourceUri];
        }

        $bootstrap = $this->_app->getBootstrap();
        $logger = $bootstrap->getResource('Logger');
        $model = $bootstrap->getResource('Model');
        $modelUri = $model->getModelIri();

        $r = new Erfurt_Rdf_Resource($resourceUri);

        // Try to instanciate the requested wrapper
        $wrapperName = 'Linkeddata';
        $wrapper = Erfurt_Wrapper_Registry::getInstance()->getWrapperInstance($wrapperName);

        $wrapperResult = null;
        $wrapperResult = $wrapper->run($r, $modelUri, true);

        $newStatements = null;
        if ($wrapperResult === false) {
            // IMPORT_WRAPPER_NOT_AVAILABLE;
            $logger->error('LinkeddataHelper: Wrapper result is false on fetching: "' . $resourceUri . '"');
        } else if (is_array($wrapperResult)) {
            $newStatements = $wrapperResult['add'];
            // TODO make sure to only import the specified resource
            $newModel = new Erfurt_Rdf_MemoryModel($newStatements);
            $newStatements = array();
            $newStatements[$resourceUri] = $newModel->getPO($resourceUri);
        } else {
            // IMPORT_WRAPPER_ERR;
            $logger->error('LinkeddataHelper: Import error on fetching: "' . $resourceUri . '"');
        }

        $this->_resources[$resourceUri] = $newStatements;
        return $newStatements;
    }

    /**
     * Method checks if a resource given by a URI exists or not
     * @param string $resourceUri
     * @return boolean
     */
    public function resourceDescriptionExists ($resourceUri) {
        $newStatements = $this->getResource($resourceUri);
        if (
            $newStatements === null ||
            empty($newStatements) ||
            empty($newStatements[$resourceUri])
        ) {
            return false;
        } else {
            return true;
        }

    }
}
