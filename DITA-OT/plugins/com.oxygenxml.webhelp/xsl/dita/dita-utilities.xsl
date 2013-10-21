<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" 
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:relpath="http://dita2indesign/functions/relpath"
  exclude-result-prefixes="relpath">

  
  <xsl:import href="original/dita-utilities.xsl"/>
  <xsl:include href="original/output-message.xsl"/>

  <xsl:variable name="msgprefix">DOTX</xsl:variable>
  
  <!-- Uses the DITA localization architecture, but our strings. -->
  <xsl:template name="getWebhelpString">
    <xsl:param name="stringName" />
    <xsl:param name="stringFileList" select="document('../../oxygen-webhelp/resources/localization/allstrings.xml')/allstrings/stringfile"/>
    <xsl:call-template name="getString">
      <xsl:with-param name="stringName" select="$stringName"/>
      <xsl:with-param name="stringFileList" select="$stringFileList"/>
    </xsl:call-template>
  </xsl:template>
  
   
</xsl:stylesheet>
