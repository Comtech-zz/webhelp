<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns="http://www.w3.org/1999/xhtml"
  version="2.0">
  
  <xsl:include href="../../feedback.xsl"/>
  
  <!-- 
    Generates the JS and CSS references in the head element of the HTML pages.            
  -->
  <xsl:template name="jsAndCSS">
    <meta xmlns="http://www.w3.org/1999/xhtml" http-equiv="Content-Type" content="text/html; charset=utf-8"><xsl:comment/></meta>
    
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{$PATH2PROJ}oxygen-webhelp/resources/css/webhelp_topic.css"><xsl:comment/></link>
    <xsl:if test="$IS_FEEDBACK_ENABLED">
      <link rel="stylesheet" type="text/css" href="{$PATH2PROJ}oxygen-webhelp/resources/css/jquery.realperson.css"><xsl:comment/></link>
      <link rel="stylesheet" type="text/css" href="{$PATH2PROJ}oxygen-webhelp/resources/css/comments.css"><xsl:comment/></link>
      <link rel="stylesheet" type="text/css" href="{$PATH2PROJ}oxygen-webhelp/resources/css/jquery.cleditor.css"><xsl:comment/></link>
      <link rel="stylesheet" type="text/css" href="{$PATH2PROJ}oxygen-webhelp/resources/css/admin.css"><xsl:comment/></link>  
    </xsl:if>
    <!-- JS -->
    <!-- Generates the inline scripts. -->    
    <script type="text/javascript">
      <xsl:comment>
        <xsl:text><![CDATA[
          
          var prefix = "]]></xsl:text>
        <xsl:value-of select="$PATH2PROJ"/>
        <xsl:text><![CDATA[index.html";
          
          ]]></xsl:text>
      </xsl:comment>
    </script>    
    <!-- Generates the external script references. -->
    <script type="text/javascript" src="{$PATH2PROJ}oxygen-webhelp/resources/js/jquery.js"><xsl:comment/></script>
    <script type="text/javascript" src="{$PATH2PROJ}oxygen-webhelp/resources/js/jquery-ui.custom.min.js"><xsl:comment/></script>    
    <xsl:if test="string-length($CUSTOM_RATE_PAGE_URL) > 0">
      <script type="text/javascript" charset="utf-8" src="{$PATH2PROJ}oxygen-webhelp/resources/js/rate_article.js"><xsl:comment/></script>
    </xsl:if>    
    <script type="text/javascript" charset="utf-8" src="{$PATH2PROJ}oxygen-webhelp/resources/js/webhelp_topic.js"><xsl:comment/></script>
    <xsl:if test="$IS_FEEDBACK_ENABLED">      
      <script type="text/javascript" charset="utf-8" src="{$PATH2PROJ}oxygen-webhelp/resources/localization/strings.js"><xsl:comment/></script>
      <script type="text/javascript" charset="utf-8" src="{$PATH2PROJ}oxygen-webhelp/resources/js/init.js"><xsl:comment/></script>
      <script type="text/javascript" charset="utf-8" src="{$PATH2PROJ}oxygen-webhelp/resources/js/comments-functions.js"><xsl:comment/></script>      
    </xsl:if>
  </xsl:template>
  
</xsl:stylesheet>