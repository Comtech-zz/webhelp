<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  version="2.0">
  
  <xsl:import href="../dita2webhelp.xsl"/>  
  <xsl:import href="fixup.xsl"/>
  
  <!-- Enable debugging from here. --> 
  <xsl:param name="WEBHELP_DEBUG" select="false()"/>
  
  <!-- Normal Webhelp transformation, filtered. -->
  <xsl:template match="/">
    
    <xsl:variable name="topicContent">
      <xsl:apply-imports/>
    </xsl:variable>    
    
    <xsl:choose>
      <xsl:when test="$WEBHELP_DEBUG">
        <!-- This generates Invalid HTML, but is OK for debugging. -->
        <html>
          <xsl:apply-templates select="$topicContent" mode="fixup_desktop"/>                 
          <hr/>
          <h1>Original content:</h1>
          <xsl:copy-of select="$topicContent"/>                
        </html>
      </xsl:when>
      <xsl:otherwise>
        <xsl:apply-templates select="$topicContent" mode="fixup_desktop"/>        
      </xsl:otherwise>
    </xsl:choose>
    
  </xsl:template>
  
</xsl:stylesheet>