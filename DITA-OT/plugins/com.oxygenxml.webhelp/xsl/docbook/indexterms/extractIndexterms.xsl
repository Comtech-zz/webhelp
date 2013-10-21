<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
    xmlns:relpath="http://dita2indesign/functions/relpath"
    xpath-default-namespace="http://www.oxygenxml.com/ns/webhelp/index"
    exclude-result-prefixes="relpath"
    version="2.0">
    
    <xsl:import href="../../functions.xsl"/>
    <xsl:import href="../../dita/original/relpath_util.xsl"/>
    
    <!-- The folder of the HTML files containing the indexterms. -->
    <xsl:param name="BASEFOLDER"/>
    
    <xsl:template match="/">
        <index xmlns="http://www.oxygenxml.com/ns/webhelp/index">
            <xsl:apply-templates/>
        </index>
    </xsl:template>

    <xsl:template match="text()|@*"/>
    
    <xsl:template match="indexterm">
        <xsl:variable name="target" 
                select="substring-after(relpath:unencodeUri(document-uri(/)), concat(replace($BASEFOLDER, '\\', '/'), '/'))"/>
        <term name="{@primary}" xmlns="http://www.oxygenxml.com/ns/webhelp/index">
            <xsl:choose>
                <xsl:when test="@secondary">
                    <term name="{@secondary}">
                        <xsl:choose>
                            <xsl:when test="@tertiary">
                                <term name="{@tertiary}">
                                    <xsl:attribute name="target">
                                        <xsl:value-of select="$target"/>
                                    </xsl:attribute>
                                </term>
                            </xsl:when>
                            <xsl:otherwise>
                                <xsl:attribute name="target">
                                    <xsl:value-of select="$target"/>
                                </xsl:attribute>
                            </xsl:otherwise>
                        </xsl:choose>
                    </term>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:attribute name="target">
                        <xsl:value-of select="$target"/>
                    </xsl:attribute>
                </xsl:otherwise>
            </xsl:choose>
        </term>
    </xsl:template>
</xsl:stylesheet>