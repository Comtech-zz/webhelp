<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    exclude-result-prefixes="xs"
    version="1.0">
      <!-- Add for "Support foreign content vocabularies such as 
    MathML and SVG with <unknown> (#35) " in DITA 1.1 -->
  <xsl:template match="*[contains(@class,' topic/foreign ') or contains(@class,' topic/unknown ')]" >
    <!-- Oxygen patch add-on for EXM-11363, show in the XHTML output the embeded MathML formulas-->
    <xsl:apply-templates select="mml:math" xmlns:mml="http://www.w3.org/1998/Math/MathML" mode="copyMathML"/>
    <xsl:apply-templates select="*[contains(@class,' topic/object ')][@type='DITA-foreign']"/>
  </xsl:template>
  
  <xsl:template match="*[namespace-uri()='http://www.w3.org/1998/Math/MathML']" mode="copyMathML">
    <xsl:element name="{local-name()}" namespace="http://www.w3.org/1998/Math/MathML">
      <xsl:apply-templates select="node() | @*" mode="copyMathML"/>
    </xsl:element>
  </xsl:template>
  
  <xsl:template match="node() | @*" mode="copyMathML">
    <xsl:copy>
      <xsl:apply-templates select="node() | @*" mode="copyMathML"/>
    </xsl:copy>
  </xsl:template>
  
</xsl:stylesheet>