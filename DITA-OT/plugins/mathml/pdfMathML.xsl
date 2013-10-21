<?xml version='1.0'?>

<!-- Oxygen add-on for EXM-11363, show in the PDF output the embeded MathML formulas-->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:fo="http://www.w3.org/1999/XSL/Format"
    version="1.0">

    <xsl:template match="*[contains(@class,' topic/foreign math-d/math ')]">
      <fo:instream-foreign-object>
        <xsl:copy-of select="child::mml:math" xmlns:mml="http://www.w3.org/1998/Math/MathML"/>
      </fo:instream-foreign-object>
    </xsl:template>

</xsl:stylesheet>