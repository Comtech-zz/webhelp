<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns="http://www.w3.org/1999/xhtml"
    version="2.0">
    
    <!-- 
        The jQuery mobile guidelines recommend that the CSS and JS files should be 
        the same for all the files from a system. 
        
        That is because when the user navigates to a different page, the jQuery 
        framework uses ajax to load pages, and the js and css files from target pages
        are not executed/applied. A different modality to listen for a page load 
        is used than in a standard JQuery.     
        
    -->
    
    <!-- Custom CSS set in param args.css -->
    <xsl:param name="ARGS_CSS" select="''"/>
    
    <xsl:template name="jsAndCSS">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" ><xsl:comment/></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1"><xsl:comment/></meta>
        
        <link type="text/css" href="{$PATH2PROJ}oxygen-webhelp/resources/skins/mobile/jquery.mobile-1.3.0/jquery.mobile-1.3.0.min.css" rel="stylesheet"><xsl:comment/></link>

        <link type="text/css" href="{$PATH2PROJ}oxygen-webhelp/resources/skins/mobile/toc.css" rel="stylesheet"><xsl:comment/></link>
        <link type="text/css" href="{$PATH2PROJ}oxygen-webhelp/resources/skins/mobile/topic.css" rel="stylesheet"><xsl:comment/></link>
        <xsl:if test="string-length($ARGS_CSS) > 0">
            <link rel="stylesheet" type="text/css" href="{$ARGS_CSS}"><xsl:comment/></link>
        </xsl:if>
        
        <script type="text/javascript" src="{$PATH2PROJ}oxygen-webhelp/resources/js/jquery-1.8.2.min.js"><xsl:comment/></script>
        <script type="text/javascript" src="{$PATH2PROJ}oxygen-webhelp/resources/js/browserDetect.js"><xsl:comment/></script>           
        <script type="text/javascript" src="{$PATH2PROJ}oxygen-webhelp/resources/skins/mobile/topic.js"><xsl:comment/></script>
        <script type="text/javascript" src="{$PATH2PROJ}oxygen-webhelp/resources/skins/mobile/toc.js"><xsl:comment/></script>
        <script type="text/javascript" src="{$PATH2PROJ}oxygen-webhelp/resources/skins/mobile/jquery.mobile-1.3.0/jquery.mobile-1.3.0.min.js"><xsl:comment/></script>
        
        <script type="text/javascript" src="{$PATH2PROJ}oxygen-webhelp/search/htmlFileList.js" charset="utf-8" ><xsl:comment/></script>
        <script type="text/javascript" src="{$PATH2PROJ}oxygen-webhelp/search/htmlFileInfoList.js" charset="utf-8" ><xsl:comment/></script>
        <script type="text/javascript" src="{$PATH2PROJ}oxygen-webhelp/search/nwSearchFnt.js" charset="utf-8" ><xsl:comment/></script>
        
        <!-- For Docbook is used Saxon 6. -->
        <xsl:variable name="langPart" select="substring($DEFAULTLANG, 1, 2)" />
        <xsl:variable name="lang"> 
            <xsl:choose>            
                <xsl:when test="function-available('lower-case')">
                    <xsl:value-of select="lower-case($langPart)"/>                            
                </xsl:when>
                <xsl:otherwise>
                    <xsl:value-of select="$langPart"/>
                </xsl:otherwise>
            </xsl:choose>
        </xsl:variable>
        
        <xsl:variable name="normalizedLang" select="normalize-space($lang)"/>
        <xsl:if test="$normalizedLang = 'en' or $normalizedLang = 'fr' or $normalizedLang = 'de'">
            <script type="text/javascript" src="{$PATH2PROJ}oxygen-webhelp/search/stemmers/{$normalizedLang}_stemmer.js" charset="utf-8" ><xsl:comment/></script>
        </xsl:if>
        <script type="text/javascript" src="{$PATH2PROJ}oxygen-webhelp/search/index-1.js" charset="utf-8" ><xsl:comment/></script>
        <script type="text/javascript" src="{$PATH2PROJ}oxygen-webhelp/search/index-2.js" charset="utf-8" ><xsl:comment/></script>
        <script type="text/javascript" src="{$PATH2PROJ}oxygen-webhelp/search/index-3.js" charset="utf-8" ><xsl:comment/></script>
        
        <script type="text/javascript" src="{$PATH2PROJ}oxygen-webhelp/resources/localization/strings.js" charset="utf-8" ><xsl:comment/></script>
        <script type="text/javascript" src="{$PATH2PROJ}oxygen-webhelp/resources/js/localization.js" charset="utf-8" ><xsl:comment/></script>
        <script type="text/javascript" src="{$PATH2PROJ}oxygen-webhelp/resources/js/parseuri.js" charset="utf-8" ><xsl:comment/></script>        
        <script type="text/javascript" src="{$PATH2PROJ}oxygen-webhelp/resources/js/log.js"><xsl:comment/></script>
    </xsl:template>
</xsl:stylesheet>