<?php
class Saft_Helper_LinkeddataHelper extends Saft_Helper
{
    /**
     * This method gets the statements for the given resource which are available according to the
     * Linked Data rules {@link http://www.w3.org/DesignIssues/LinkedData.html}
     *
     * @warning This function sends a web request and might take a long time
     * @hint You should run this function asynchrounusly or independent of your UI
     *
     * @param $resourceUri the URI of the resource to get
     * @return array of Statements with the resource as subject
     */
    public function getResource ($resourceUri)
    {
        return Saft_Tools::getLinkedDataResource($this->_app, $resourceUri);
    }
}
