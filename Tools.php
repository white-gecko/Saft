<?php

class Saft_Tools
{
    /**
     * Looks up if a natural word instead of $uri exists and returns this
     *
     * TODO: move this function to NameHelper and user predicate labels
     * @param unknown_type $uri a uri
     */
    public static function getSpokenWord($uri)
    {

        $nsXsd      = 'http://www.w3.org/2001/XMLSchema#';
        $nsRdf      = 'http://www.w3.org/1999/02/22-rdf-syntax-ns#';
        $nsSioc     = 'http://rdfs.org/sioc/ns#';
        $nsSioct    = 'http://rdfs.org/sioc/types#';
        $nsAtom     = 'http://www.w3.org/2005/Atom/';
        $nsAair     = 'http://xmlns.notu.be/aair#';
        $nsXodx     = 'http://xodx.org/ns#';
        $nsFoaf     = 'http://xmlns.com/foaf/0.1/';
        $nsOv       = 'http://open.vocab.org/docs/';
        $nsPingback = 'http://purl.org/net/pingback/';
        $nsDssn     = 'http://purl.org/net/dssn/';

        $words = array(
            $nsAair  . 'MakeFriend' => 'friended',
            $nsAair  . 'Post'       => 'posted',
            $nsAair  . 'Share'      => 'shared',
            $nsAair  . 'Note'       => 'status note',
            $nsAair  . 'Bookmark'   => 'link',
            $nsAair  . 'Photo'      => 'image',
            $nsAair  . 'Comment'    => 'comment',
            $nsSioct . 'Comment'    => 'comment',
            $nsSioc  . 'Comment'    => 'comment',
            $nsSioc  . 'Note'       => 'status note',
            $nsFoaf  . 'Image'      => 'image'
        );

        if (isset($words[$uri])) {
            return $words[$uri];
        } else {
            return $uri;
        }
    }
}
